<?php

namespace Spiral\Validation\Tests\Checkers;

use Spiral\Core\FactoryInterface;
use Spiral\Validation\Checkers\EntityChecker;
use Spiral\Validation\Tests\BaseTest;
use Spiral\Validation\ValidatorInterface;

class EntityTest extends BaseTest
{
    /**
     * @dataProvider uniqueProvider
     *
     * @param $context
     * @param $value
     * @param $success
     */
    public function testAllowed($context, $value, $success)
    {
        $checker = new EntityChecker($this->container->get(FactoryInterface::class));

        $mock = $this->mockValidator();
        $mock->method('getContext')->will($this->returnValue($context));
        $this->assertEquals($success, $checker->check($mock, 'unique', '', $value, [Source::class, '']));
    }

    public function uniqueProvider(): array
    {
        return [
            [null, 'unique', true],
            [null, 'not unique', false],
            ['context', 'same value', true],
            ['context', 'unique', true],
            ['context', 'not unique', false],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function mockValidator()
    {
        return $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();
    }
}


class Source implements EntityChecker\SourceInterface
{
    public function findByColumn(string $column, $value)
    {
        if ($value === 'unique') {
            return null;
        }

        return 'another entity';
    }

    public function hasUpdates($entity, string $field, $value): bool
    {
        return $value !== 'same value';
    }
}