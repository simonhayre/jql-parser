<?php
namespace SHJQLParser\Operator;

class eq implements Operator
{
    /**
     * @return string
     */
    public function getCaseRegEx()
    {
        return '\w+\s+?(?:\=|\!\=)\s+?(?:\"(?:\\\\\"|[^\"])+\"|(?:\\\\\"|[^\"\s])+)';
    }

    /**
     * @param string $case
     *
     * @return bool
     */
    public function isCase($case)
    {
        return preg_match('/^' . $this->getCaseRegEx() . '$/i', $case) > 0;
    }

    /**
     * @param string $case
     *
     * @return \SHJQLParser\Filter\Filter
     */
    public function createFilter($case)
    {
        preg_match('/(?<key>\w+)\s+?(?<operator>\=|\!\=)\s+?(?<value>\"(?:\\\\\"|[^\"])+\"|(?:\\\\\"|[^\"\s])+)/i', $case, $result);

        $key = $result['key'];
        $value = $result['value'];
        $operator = $result['operator'];

        return (new \SHJQLParser\Filter\KeyValue())
            ->setKey($key)
            ->setValue(str_replace(['"'], '', $value))
            ->setNot((bool)preg_match('/\!\=/i', $operator));
    }
}