<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

use Spiral\Validation\AbstractChecker;
use Spiral\Validation\Checkers\TypeChecker;

class CallableTest extends BaseTest
{
    const CONFIG = [
        'checkers' => [
            'type' => TypeChecker::class,
            'test' => TestChecher::class,
        ],
        'aliases'  => [
            'notEmpty' => 'type::notEmpty',
        ],
    ];

    public function testInArray()
    {
        $this->assertValid([
            'i' => 'value'
        ], [
            'i' => [
                ['in_array', ['value', 'other']]
            ]
        ]);

        $this->assertFail('i', [
            'i' => 'third'
        ], [
            'i' => [
                ['in_array', ['value', 'other']]
            ]
        ]);
    }

    public function testEmptyInArray()
    {
        $this->assertValid([
            'i' => null
        ], [
            'i' => [
                ['in_array', ['value', 'other']]
            ]
        ]);

        $this->assertFail('i', [
            'i' => null
        ], [
            'i' => [
                ['notEmpty'],
                ['in_array', ['value', 'other']]
            ]
        ]);
    }

    public function testDefaultMessage()
    {
        $v = $this->validation->validate([
            'i' => 'third'
        ], [
            'i' => [
                ['in_array', ['value', 'other']]
            ]
        ]);

        $this->assertSame('The condition `in_array` was not met.', $v->getErrors()['i']);
    }

    public function testDefaultMessageStatic()
    {
        $v = $this->validation->validate([
            'i' => 'third'
        ], [
            'i' => [
                [[self::class, 'check']]
            ]
        ]);

        $this->assertSame(
            'The condition `Spiral\Validation\Tests\CallableTest::check` was not met.',
            $v->getErrors()['i']
        );
    }

    public function testDefaultMessageRuntime()
    {
        $v = $this->validation->validate([
            'i' => 'third'
        ], [
            'i' => [
                [[$this, 'check']]
            ]
        ]);

        $this->assertSame(
            'The condition `Spiral\Validation\Tests\CallableTest::check` was not met.',
            $v->getErrors()['i']
        );
    }

    public function testDefaultMethodClosure()
    {
        $v = $this->validation->validate([
            'i' => 'third'
        ], [
            'i' => function () {
                return false;
            }
        ]);

        $this->assertSame('The condition `~user-defined~` was not met.', $v->getErrors()['i']);
    }

    public function testCheckerDefault()
    {
        $validator = $this->validation->validate(
            ['i' => 'value'],
            ['i' => 'test:test']
        );

        $this->assertSame(['i' => 'The condition `test` was not met.'], $validator->getErrors());
    }

    public static function check($value)
    {
        return false;
    }
}

class TestChecher extends AbstractChecker
{
    public function test()
    {
        return false;
    }
}