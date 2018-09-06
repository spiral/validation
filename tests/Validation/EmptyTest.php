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
        $result = $this->validation->validate([], []);
        $this->assertTrue($result->isValid());
        $this->assertSame([], $result->getErrors());

        $result = $this->validation->validate(['email' => 'user@example.com'], []);
        $this->assertTrue($result->isValid());
        $this->assertSame([], $result->getErrors());
    }

    public function testNotEmpty()
    {
        $result = $this->validation->validate([], ['name' => ['type::notEmpty']]);
        $this->assertFalse($result->isValid());
        $this->assertSame(['name' => 'This value is required.'], $result->getErrors());

        $result = $this->validation->validate(['name' => null], ['name' => ['type::notEmpty']]);
        $this->assertFalse($result->isValid());

        $result = $this->validation->validate(['name' => ''], ['name' => ['type::notEmpty']]);
        $this->assertFalse($result->isValid());

        $result = $this->validation->validate(['name' => 'John Doe'], ['name' => ['type::notEmpty']]);
        $this->assertTrue($result->isValid());
    }

    public function testNotEmptyShorter()
    {
        $result = $this->validation->validate([], ['name' => 'notEmpty']);
        $this->assertFalse($result->isValid());
        $this->assertSame(['name' => 'This value is required.'], $result->getErrors());

        $result = $this->validation->validate(['name' => null], ['name' => 'notEmpty']);
        $this->assertFalse($result->isValid());

        $result = $this->validation->validate(['name' => ''], ['name' => 'notEmpty']);
        $this->assertFalse($result->isValid());

        $result = $this->validation->validate(['name' => 'John Doe'], ['name' => 'notEmpty']);
        $this->assertTrue($result->isValid());
    }
}