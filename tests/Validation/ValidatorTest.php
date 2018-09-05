<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

class ValidatorTest extends BaseTest
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
}