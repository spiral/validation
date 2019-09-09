<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

class ParserTest extends BaseTest
{
    public function testClosure()
    {
        $validator = $this->validation->validate([
            'name' => 'string'
        ], [
            'name' => [
                function () {
                    return false;
                }
            ]
        ]);

        $this->assertFalse($validator->isValid());
    }

    /**
     * @expectedException \Spiral\Validation\Exception\ParserException
     */
    public function testParseError()
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
