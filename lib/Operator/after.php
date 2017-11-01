<?php
namespace SHJQLParser\Operator;

class after extends AbstractDate
{
    /**
     * @return string
     */
    public function getCaseRegEx()
    {
        return '\w+\s+?(?:after|not\s+after)\s+?' . '"(?:' . self::DATE_REGEX. ')"';
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
        preg_match('/(?<key>\w+)\s+?(?<operator>after|not\s+after)\s+?"(?<value>' . self::DATE_REGEX. ')"/i', $case, $matches);

        $key = $matches['key'];
        $operator = $matches['operator'];

        return (new \SHJQLParser\Filter\KeyAfterValue())
            ->setKey($key)
            ->setValue($matches['value'])
            ->setNot((bool)preg_match('/not\s+after/i', $operator));
    }
}