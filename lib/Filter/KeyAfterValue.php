<?php

namespace SHJQLParser\Filter;

class KeyAfterValue implements Filter
{
    /** @var string */
    private $key;
    /** @var mixed */
    private $value;
    /** @var bool */
    private $not = false;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return KeyAfterValue
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return KeyAfterValue
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNot()
    {
        return $this->not;
    }

    /**
     * @param bool $not
     *
     * @return KeyAfterValue
     */
    public function setNot($not)
    {
        $this->not = $not;
        return $this;
    }
}
