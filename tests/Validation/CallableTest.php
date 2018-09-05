<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

class CallableTest extends BaseTest
{
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

        $this->assertSame('Condition `in_array` does not meet.', $v->getErrors()['i']);
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
            'Condition `Spiral\Validation\Tests\CallableTest::check` does not meet.',
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
            'Condition `Spiral\Validation\Tests\CallableTest::check` does not meet.',
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

        $this->assertSame('Condition `~user-defined~` does not meet.', $v->getErrors()['i']);
    }

    public static function check($value)
    {
        return false;
    }
}