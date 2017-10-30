<?php

namespace SHJQLParser\Filter;

class OrderBy implements Filter
{
    const DIRECTION_ASC = 'ASC';
    const DIRECTION_DESC = 'DESC';

    /** @var string */
    private $key;
    /** @var string */
    private $direction;

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
     * @return OrderBy
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     *
     * @return OrderBy
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }
}
