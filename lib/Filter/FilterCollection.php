<?php

namespace SHJQLParser\Filter;

class FilterCollection extends \ArrayObject implements Filter
{
    /**
     * FilterCollection constructor.
     *
     * @param Filter[] $input
     */
    public function __construct(array $input = [])
    {
        parent::__construct([], 0, \ArrayIterator::class);

        foreach ($input as $filter) {
            $this->add($filter);
        }
    }

    /**
     * @param Item $filter
     *
     * @return FilterCollection
     */
    public function add(Item $filter)
    {
        $this->append($filter);

        return $this;
    }

    /**
     * @param Item $filter
     *
     * @return FilterCollection
     */
    public function append($filter)
    {
        if (!$filter instanceof Item) {
            throw new \InvalidArgumentException(sprintf('$filter must be an instance of %s', Item::class));
        }

        parent::append($filter);

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return null;
    }
}
