<?php
namespace SHJQLParser\Operator;

class between extends AbstractDate
{
    /**
     * @return string
     */
    public function getCaseRegEx()
    {
        return '\w+\s+?(?:between|not\s+between)\s+?' . '"(?:' . self::DATE_REGEX. ')"' . '\s+and\s+"(?:' . self::DATE_REGEX. ')"';
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
        preg_match('/(?<key>\w+)\s+?(?<operator>between|not\s+between)\s+?"(?<from>' . self::DATE_REGEX. ')"\s+and\s+"(?<to>' . self::DATE_REGEX. ')\"/i', $case, $matches);

        $key = $matches['key'];
        $operator = $matches['operator'];

        $valueCollection = (new \SHJQLParser\Filter\ValueCollection())
            ->add($matches['from'])
            ->add($matches['to']);

        return (new \SHJQLParser\Filter\KeyBetweenValue())
            ->setKey($key)
            ->setValues($valueCollection)
            ->setNot((bool)preg_match('/not\s+between/i', $operator));
    }
}