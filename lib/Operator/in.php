<?php
namespace SHJQLParser\Operator;

class in implements Operator
{
    /**
     * @return string
     */
    public function getCaseRegEx()
    {
        return '\w+\s+?(?:in|not\s+in)\s+?\((?:(?:\"[A-Za-z0-9_\.\-@\s]+\")|[A-Za-z0-9_\.\-@]+|\s+)+\)';
    }

    /**
     * @param string $case
     *
     * @return bool
     */
    public function isCase($case)
    {
        return preg_match('/' . $this->getCaseRegEx() . '/i', $case) > 0;
    }

    /**
     * @param string $case
     *
     * @return \SHJQLParser\Filter\Filter
     */
    public function createFilter($case)
    {
        preg_match('/(?<key>\w+)\s+?(?<operator>in|not\s+in)\s+?(?<values>\((?:(?:\"[A-Za-z0-9_\.\-@\s]+\")|[A-Za-z0-9_\.\-@]+|\s+)+\))/i', $case, $result);

        $key = $result['key'];
        $values = $result['values'];
        $operator = $result['operator'];

        preg_match_all('/(?<values>([A-Za-z0-9_.\-@]+)|(\"[A-Za-z0-9_.\-@\s]+\"))/i', $values, $valueResult);

        $valueCollection = new \SHJQLParser\Filter\ValueCollection();

        foreach ($valueResult['values'] as $value) {
            $valueCollection->add(str_replace(['"'], '', $value));
        }

        return (new \SHJQLParser\Filter\KeyInValues())
            ->setKey($key)
            ->setValues($valueCollection)
            ->setNot((bool)preg_match('/not\s+in/i', $operator))
        ;
    }
}