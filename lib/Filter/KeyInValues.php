<?php

namespace SHJQLParser\Filter;

class KeyInValues implements Filter
{
    /** @var string */
    private $key;
    /** @var ValueCollection */
    private $values;
    /** @var bool */
    private $not = false;

    public function __construct()
    {
        $this->values = new ValueCollection();
    }

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
     * @return KeyInValues
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return ValueCollection
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param ValueCollection $values
     *
     * @return KeyInValues
     */
    public function setValues(ValueCollection $values)
    {
        $this->values = $values;
        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return KeyInValues
     */
    public function addValue($value)
    {
        $this->values->add($value);
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
     * @return KeyInValues
     */
    public function setNot($not)
    {
        $this->not = $not;
        return $this;
    }
}
