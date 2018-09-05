<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

use Spiral\Validation\Validator;

class ErrorsTest extends BaseTest
{
    public function testHasError()
    {
        /**
         * @var Validator $validator
         */
        $validator = $this->validation->validate([], ['name' => ['type::notEmpty']]);
        $this->assertTrue($validator->hasError('name'));
    }

    public function testRegisterError()
    {
        /**
         * @var Validator $validator
         */
        $validator = $this->validation->validate([], []);

        $this->assertFalse($validator->hasError('name'));

        $validator->registerError('name', 'Name error');
        $this->assertTrue($validator->hasError('name'));

        $this->assertSame(['name' => 'Name error'], $validator->getErrors());
    }
}