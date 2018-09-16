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

    public function testMultipleErrors()
    {
        /**
         * @var Validator $validator
         */
        $validator = $this->validation->validate(
            [
                'name' => 'email'
            ],
            [
                'name' => [
                    'notEmpty',
                    'email',
                    ['string::regexp', '/^email@domain\.com$/']
                ]
            ]
        );

        $this->assertSame(
            'Must be a valid email address.',
            $validator->getErrors()['name']
        );

        /**
         * @var Validator $validator
         */
        $validator = $this->validation->validate(
            [
                'name' => 'other@domain.com'
            ],
            [
                'name' => [
                    'notEmpty',
                    'email',
                    ['string::regexp', '/^email@domain\.com$/']
                ]
            ]
        );

        $this->assertSame(
            'Value does not match required pattern.',
            $validator->getErrors()['name']
        );
    }
}