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
        preg_match_all('/(?<key>\w+)\s+?(?<operator>in|not\s+in)\s+?(?<values>\(\"?.*\"?\))/i', $query, $result);

        $this->setKeyValues($filterCollection, $result);

        return $filterCollection;
    }

    private function setKeyValues(FilterCollection $filterCollection, array $result)
    {
        if (!empty($result['key']) && !empty($result['values'])) {
            for ($i = 0; $i < min(count($result['key']), count($result['values'])); $i++) {
                $key = $result['key'][$i];
                $values = $result['values'][$i];
                $operator = $result['operator'][$i];

                preg_match_all('/(?<values>([A-Za-z0-9_.\-@]+)|(\"[A-Za-z0-9_.\s]+\"))/i', $values, $valueResult);

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

}
