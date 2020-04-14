<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Tests\Validation;

class ParserTest extends BaseTest
{
    public function testClosure(): void
    {
        $validator = $this->validation->validate([
            'name' => 'string'
        ], [
            'name' => [
                static function () {
                    return false;
                }
            ]
        ]);

        $this->assertFalse($validator->isValid());
    }

    /**
     * @expectedException \Spiral\Validation\Exception\ParserException
     */
    public function testParseError(): void
    {
        $validator = $this->validation->validate([
            'name' => 'string'
        ], [
            'name' => [
                []
            ]
        ]);

        $this->assertFalse($validator->isValid());
    }
}
