<?php
namespace SHJQLParser\Operator;

interface Operator
{
    /**
     * @return string
     */
    public function getCaseRegEx();

    /**
     * @param string $case
     *
     * @return bool
     */
    public function isCase($case);


    /**
     * @param string $case
     *
     * @return \SHJQLParser\Filter\Filter
     */
    public function createFilter($case);
}