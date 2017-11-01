<?php
namespace SHJQLParser;

use SHJQLParser\Filter\FilterCollection;

class JQLParser
{
    /** @var Operator\Operator[] */
    private $operators = [];

    public function __construct()
    {
        $this->operators = [
            new Operator\eq(),
            new Operator\in(),
            new Operator\between(),
            new Operator\after(),
            new Operator\before(),
        ];
    }

    /**
     * @param string $query
     *
     * @return FilterCollection
     */
    public function parse($query)
    {
        $filterCollection = (new FilterCollection());

        $this->processOperators($filterCollection, $query);
        $this->processOrderBy($filterCollection, $query);

        return $filterCollection;
    }

    /**
     * @param FilterCollection $filterCollection
     * @param string $query
     *
     * @return JQLParser
     */
    private function processOperators(FilterCollection $filterCollection, $query)
    {
        $cases = [];
        foreach ($this->operators as $operator) {
            $cases[] = '(?:' . $operator->getCaseRegEx() . ')';
        }

        preg_match_all('/(?<cases>(?:' . implode('|', $cases) . '))/i',
            $query,
            $result
        );

        if (!empty($result['cases'])) {
            foreach ($result['cases'] as $case) {
                foreach ($this->operators as $operator) {
                    if ($operator->isCase($case)) {
                        $filterCollection->add($operator->createFilter($case));
                        break;
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param FilterCollection $filterCollection
     * @param string           $query
     *
     * @return $this
     */
    private function processOrderBy(FilterCollection $filterCollection, $query)
    {
        if (preg_match('/order\s+by\s+(?<orderByMatch>[\w|\s]+)/i', $query, $result)) {
            preg_match_all('/(?<key_with_direction>(\w+(\s+(asc|desc))?))/i', $result['orderByMatch'], $result);

            $this->setOrderBy($filterCollection, $result);
        }

        return $this;
    }

    /**
     * @param FilterCollection $filterCollection
     * @param array            $result
     */
    private function setOrderBy(FilterCollection $filterCollection, array $result)
    {
        if (!empty($result['key_with_direction'])) {
            foreach ($result['key_with_direction'] as $keyWithDirection) {

                preg_match('/(?<key>\w+)\s+?(?<direction>asc|desc)?/i', $keyWithDirection, $orderByResult);

                $filterCollection
                    ->add(
                        (new Filter\OrderBy())
                            ->setKey($orderByResult['key'])
                            ->setDirection($orderByResult['direction'])
                    );
            }
        }
    }
}
