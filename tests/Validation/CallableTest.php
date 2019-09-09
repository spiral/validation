<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

use Spiral\Validation\AbstractChecker;
use Spiral\Validation\Checker\TypeChecker;
use Spiral\Validation\Tests\Fixtures\TestChecker;
use Spiral\Validation\Tests\Fixtures\Value;

class CallableTest extends BaseTest
{
    const CONFIG = [
        'checkers' => [
            'type' => TypeChecker::class,
            'test' => TestChecker::class,
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

        $this->assertNotValid('i', [
            'i' => 'third'
        ], [
            'i' => [
                ['in_array', ['value', 'other']]
            ]
        ]);
    }

    public function testInArrayAccessor()
    {
        $this->assertValid([
            'i' => new Value('value')
        ], [
            'i' => [
                ['in_array', ['value', 'other']]
            ]
        ]);

        $this->assertNotValid('i', [
            'i' => new Value('third')
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

        $this->assertNotValid('i', [
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

    public function testCustomMessage()
    {
        $v = $this->validation->validate([
            'i' => 'third'
        ], [
            'i' => [
                ['notEmpty'],
                ['in_array', ['value', 'other'], 'msg' => 'error']
            ]
        ]);

        $this->assertSame('error', $v->getErrors()['i']);
    }

    public function testCheckerDefault()
    {
        $validator = $this->validation->validate(
            ['i' => 'value'],
            ['i' => 'test:test']
        );

        $this->assertSame(['i' => 'The condition `test` was not met.'], $validator->getErrors());
    }

    public function testCheckerByCallableClass()
    {
        $validator = $this->validation->validate(
            [
                'i' => 'value'
            ],
            [
                'i' => [
                    [
                        [TestChecker::class, 'test'],
                        'err' => 'ERROR'
                    ]
                ]
            ]
        );

        $this->assertSame(['i' => 'ERROR'], $validator->getErrors());
    }

    public function testCheckerByCallableObject()
    {
        $checker = new TestChecker();
        $validator = $this->validation->validate(
            ['i' => 'value'],
            [
                'i' => [
                    [[$checker, 'test'], 'err' => 'ERROR']
                ]
            ]
        );

        $this->assertSame(['i' => 'ERROR'], $validator->getErrors());
    }

    public static function check($value)
    {
        return false;
    }
}
