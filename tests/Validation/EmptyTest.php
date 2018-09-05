<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

class EmptyTest extends BaseTest
{
    public function testNoRules()
    {
        $validator = $this->validation->validate([], []);
        $this->assertTrue($validator->isValid());
        $this->assertSame([], $validator->getErrors());

        $validator = $this->validation->validate(['email' => 'user@example.com'], []);
        $this->assertTrue($validator->isValid());
        $this->assertSame([], $validator->getErrors());
    }

    public function testNotEmpty()
    {
        $validator = $this->validation->validate([], ['name' => ['type::notEmpty']]);
        $this->assertFalse($validator->isValid());

        $validator = $this->validation->validate(['name' => null], ['name' => ['type::notEmpty']]);
        $this->assertFalse($validator->isValid());

        $validator = $this->validation->validate(['name' => ''], ['name' => ['type::notEmpty']]);
        $this->assertFalse($validator->isValid());

        $validator = $this->validation->validate(['name' => 'John Doe'], ['name' => ['type::notEmpty']]);
        $this->assertTrue($validator->isValid());
    }

    public function testNotEmptyShorter()
    {
        $validator = $this->validation->validate([], ['name' => 'notEmpty']);
        $this->assertFalse($validator->isValid());

        $validator = $this->validation->validate(['name' => null], ['name' => 'notEmpty']);
        $this->assertFalse($validator->isValid());

        $validator = $this->validation->validate(['name' => ''], ['name' => 'notEmpty']);
        $this->assertFalse($validator->isValid());

        $validator = $this->validation->validate(['name' => 'John Doe'], ['name' => 'notEmpty']);
        $this->assertTrue($validator->isValid());
    }
}