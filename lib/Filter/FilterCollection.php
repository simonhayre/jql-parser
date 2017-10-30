<?php

namespace SHJQLParser\Filter;

class FilterCollection extends \ArrayObject
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
     * @param Filter $filter
     *
     * @return FilterCollection
     */
    public function add(Filter $filter)
    {
        $this->append($filter);

        return $this;
    }

    /**
     * @param Filter $filter
     *
     * @return FilterCollection
     */
    public function append($filter)
    {
        if (!$filter instanceof Filter) {
            throw new \InvalidArgumentException(sprintf('$filter must be an instance of %s', Filter::class));
        }

        parent::append($filter);

        return $this;
    }
}
