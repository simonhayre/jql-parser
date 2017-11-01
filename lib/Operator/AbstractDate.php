<?php
namespace SHJQLParser\Operator;

abstract class AbstractDate implements Operator
{
    const DATE_REGEX = '(?:\d{2}\/\d{2}\/\d{2,4})|(?:\d{2,4}\-\d{2}\-\d{2})|(?:\d{2,4}\-\d{2}\-\d{2}\s+\d{1,2}\:\d{1,2}(?:\:\d{1,2})?)';
}