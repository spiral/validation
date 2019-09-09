<?php

namespace Spiral\Validation\Tests\Fixtures;

use Spiral\Validation\AbstractChecker;

class TestChecker extends AbstractChecker
{
    public function test()
    {
        return false;
    }
}
