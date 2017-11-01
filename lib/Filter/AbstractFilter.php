<?php

namespace SHJQLParser\Filter;

abstract class AbstractFilter implements Filter
{
    /** @var string */
    private $joiningOperator = self::AND_JOIN;

    /**
     * @return string
     */
    public function getJoiningOperator()
    {
        return $this->joiningOperator;
    }

    /**
     * @param string $joiningOperator
     *
     * @return AbstractFilter
     */
    public function setJoiningOperator($joiningOperator)
    {
        $this->joiningOperator = $joiningOperator;
        return $this;
    }
}
