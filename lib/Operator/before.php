<?php
namespace SHJQLParser\Operator;

class before extends AbstractDate
{
    /**
     * @return string
     */
    public function getCaseRegEx()
    {
        return '\w+\s+?(?:before|not\s+before)\s+?' . '"(?:' . self::DATE_REGEX. ')"';
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
        preg_match('/(?<key>\w+)\s+?(?<operator>before|not\s+before)\s+?"(?<value>' . self::DATE_REGEX. ')"/i', $case, $matches);

        $key = $matches['key'];
        $operator = $matches['operator'];

        return (new \SHJQLParser\Filter\KeyBeforeValue())
            ->setKey($key)
            ->setValue($matches['value'])
            ->setNot((bool)preg_match('/not\s+before/i', $operator));
    }
}