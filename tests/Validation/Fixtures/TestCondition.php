<?php


namespace Spiral\Validation\Tests\Fixtures;

use Spiral\Validation\AbstractCondition;
use Spiral\Validation\ValidatorInterface;

class TestCondition extends AbstractCondition
{
    public function isMet(ValidatorInterface $validator, string $field, $value): bool
    {
        return true;
    }
}
