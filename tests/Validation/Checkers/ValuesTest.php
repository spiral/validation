<?php

namespace Spiral\Validation\Tests\Checkers;

use Spiral\Core\FactoryInterface;
use Spiral\Validation\Checkers\ValuesChecker;
use Spiral\Validation\Tests\BaseTest;
use Spiral\Validation\ValidatorInterface;

class ValuesTest extends BaseTest
{
    public function testAny()
    {
        $checker = new ValuesChecker($this->container->get(FactoryInterface::class));

        $mock = $this->mockValidator();
        $mock->method('getValue')->with('fields')->will($this->returnValue([1, 2, 3]));

        $this->assertTrue($checker->check($mock, 'any', '', 'fields'));

        $mock = $this->mockValidator();
        $mock->method('getValue')->with('fields')->will($this->returnValue([]));
        $this->assertFalse($checker->check($mock, 'any', '', 'fields'));

        $mock = $this->mockValidator();
        $mock->method('getValue')->with('fields')->will($this->returnValue(null));
        $this->assertFalse($checker->check($mock, 'any', '', 'fields'));
    }

    public function testAllowed()
    {
        $checker = new ValuesChecker($this->container->get(FactoryInterface::class));

        $mock = $this->mockValidator();
        $mock->method('getValue')->with('fields')->will($this->returnValue([1, 2, 3]));
        $this->assertTrue($checker->check($mock, 'allowed', 'fields', 'fields', [Registry::class, 'column']));

        $mock = $this->mockValidator();
        $mock->method('getValue')->with('fields')->will($this->returnValue([1, 2, 6]));
        $this->assertFalse($checker->check($mock, 'allowed', 'fields', 'fields', [Registry::class, 'column']));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function mockValidator()
    {
        return $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();
    }
}

class Registry implements ValuesChecker\RegistryInterface
{
    public function populate(?string $column = null): array
    {
        return [1, 2, 3, 4, 5];
    }
}