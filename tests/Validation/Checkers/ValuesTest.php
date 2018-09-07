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

        $mock = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();
        $mock->method('getValue')->with('fields')->will($this->returnValue([1, 2, 3]));

        $this->assertTrue($checker->check($mock, 'any', 'holder', 'fields'));

        $mock = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();
        $mock->method('getValue')->with('fields')->will($this->returnValue([]));
        $this->assertFalse($checker->check($mock, 'any', 'holder', 'fields'));

        $mock = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();
        $mock->method('getValue')->with('fields')->will($this->returnValue(null));
        $this->assertFalse($checker->check($mock, 'any', 'holder', 'fields'));
    }

    //Failed mock to test registry
//    public function testAllowed()
//    {
//        $checker = new ValuesChecker($this->container->get(FactoryInterface::class));
//
//        $mock = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();
//        $mock->method('getValue')->with('fields')->will($this->returnValue([1, 2, 3]));
//
//        $this->assertTrue($checker->check($mock, 'allowed', '', '', ['fields', Registry::class, 'column']));
//    }
}

class Registry implements ValuesChecker\RegistryInterface
{
    public function populate(string $column): array
    {
        return [1, 2, 3, 4, 5];
    }
}