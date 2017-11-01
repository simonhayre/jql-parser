<?php

namespace SHJQLParser\Filter;

class KeyValue extends AbstractFilter
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
     * @return KeyValue
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
     * @return KeyValue
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
     * @return KeyValue
     */
    public function setNot($not)
    {
        $this->not = $not;
        return $this;
    }
}
