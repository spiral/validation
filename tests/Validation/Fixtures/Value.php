<?php

namespace Spiral\Validation\Tests\Fixtures;

class Value
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function packValue()
    {
        return $this->value;
    }
}
