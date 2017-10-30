<?php

namespace SHJQLParser\Filter;

class ValueCollection extends \ArrayObject
{
    /**
     * ValueCollection constructor.
     *
     * @param mixed[] $input
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input, 0, \ArrayIterator::class);
    }

    /**
     * @param mixed $value
     *
     * @return ValueCollection
     */
    public function add($value)
    {
        $this->append($value);

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return ValueCollection
     */
    public function append($value)
    {
        parent::append($value);

        return $this;
    }
}
