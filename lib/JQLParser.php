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
    private function processOperators(FilterCollection $filterCollection, $query, $lastJoiningOperator = null)
    {
        $cases = ['(?:\(((?>[^()]+)|(?R))*\))'];
        foreach ($this->operators as $operator) {
            $cases[] = '(?:' . $operator->getCaseRegEx() . ')';
        }

        preg_match_all('/(?<cases>(?:' . implode('|', $cases) . '))(?:\s+)?(?<joiningOperator>(?:and|or))?/i',
            $query,
            $result
        );

        if (!empty($result['cases'])) {
            $casesCount = count($result['cases']);
            for ($i = 0; $i < $casesCount; $i++) {

                $case = $result['cases'][$i];
                $joiningOperator = empty($result['joiningOperator'][$i]) ? 'and' : $result['joiningOperator'][$i];

                if (substr($case, 0, 1) !== '(' || substr($case, -1) !== ')') {
                    foreach ($this->operators as $operator) {
                        if ($operator->isCase($case)) {
                            $filterCollection->add(
                                $operator
                                    ->createFilter($case)
                                    ->setJoiningOperator(
                                        $i < $casesCount - 1 ? strtoupper($joiningOperator) : $lastJoiningOperator
                                    )
                            );
                            break;
                        }
                    }
                } else {
                    $nestedFilters = (new FilterCollection());
                    $this->processOperators($nestedFilters, substr($case, 1, strlen($case) - 2), strtoupper($joiningOperator));

                    if ($nestedFilters->count()) {
                        $filterCollection
                            ->add($nestedFilters);
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
