<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

class ContextTest extends BaseTest
{
    public function testNoRules()
    {
        $validator = $this->validation->validate([], [], ['context']);
        $this->assertSame(['context'], $validator->getContext());
    }
}
