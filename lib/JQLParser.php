<?php
namespace SHJQLParser;

use SHJQLParser\Filter\FilterCollection;

class JQLParser
{
    /**
     * @param string $query
     *
     * @return FilterCollection
     */
    public function parse($query)
    {
        $filterCollection = (new FilterCollection());

        $this->processKeyValues($filterCollection, $query);
        $this->processOrderBy($filterCollection, $query);

        return $filterCollection;
    }

    /**
     * @param FilterCollection $filterCollection
     * @param string $query
     *
     * @return JQLParser
     */
    private function processKeyValues(FilterCollection $filterCollection, $query)
    {
        preg_match_all('/(?<key>\w+)\s+?(?<operator>in|not\s+in)\s+?(?<values>\(\"[A-Za-z0-9_.\-@\s]+\"\)|\([A-Za-z0-9_.\-@]+\))/i', $query, $result);

        $this->setKeyValues($filterCollection, $result);

        return $this;
    }

    /**
     * @param FilterCollection $filterCollection
     * @param array            $result
     */
    private function setKeyValues(FilterCollection $filterCollection, array $result)
    {
        if (!empty($result['key']) && !empty($result['values'])) {
            for ($i = 0; $i < min(count($result['key']), count($result['values'])); $i++) {
                $key = $result['key'][$i];
                $values = $result['values'][$i];
                $operator = $result['operator'][$i];

                preg_match_all('/(?<values>([A-Za-z0-9_.\-@]+)|(\"[A-Za-z0-9_.\-@\s]+\"))/i', $values, $valueResult);

                $valueCollection = new Filter\ValueCollection();

                foreach ($valueResult['values'] as $value) {
                    $valueCollection->add(str_replace(['"'], '', $value));
                }

                $filterCollection
                    ->add(
                        (new Filter\KeyValue())
                            ->setKey($key)
                            ->setValues($valueCollection)
                            ->setNot((bool) preg_match('/not\s+in/i', $operator))
                    );
            }
        }
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
