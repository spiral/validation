<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

class MessagesTest extends BaseTest
{
    public function testDefault()
    {
        $validator = $this->validation->validate([], ['name' => ['type::notEmpty']]);
        $this->assertSame(['name' => 'This value is required.'], $validator->getErrors());
    }

    public function testMessage()
    {
        $validator = $this->validation->validate([], [
            'name' => [
                ['type::notEmpty', 'message' => 'Value is empty.']
            ]
        ]);
        $this->assertSame(['name' => 'Value is empty.'], $validator->getErrors());
    }

    public function testMsg()
    {
        $validator = $this->validation->validate([], [
            'name' => [
                ['type::notEmpty', 'msg' => 'Value is empty.']
            ]
        ]);
        $this->assertSame(['name' => 'Value is empty.'], $validator->getErrors());
    }

    public function testError()
    {
        $validator = $this->validation->validate([], [
            'name' => [
                ['type::notEmpty', 'error' => 'Value is empty.']
            ]
        ]);
        $this->assertSame(['name' => 'Value is empty.'], $validator->getErrors());
    }

    public function testErr()
    {
        $validator = $this->validation->validate([], [
            'name' => [
                ['type::notEmpty', 'err' => 'Value is empty.']
            ]
        ]);
        $this->assertSame(['name' => 'Value is empty.'], $validator->getErrors());
    }
}