<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Parsers;

class RuleParser
{
    const ARGUMENTS  = ['args', 'params', 'arguments', 'parameters'];
    const MESSAGES   = ['message', 'msg', 'error', 'err'];
    const CONDITIONS = ['if', 'condition', 'conditions', 'where'];

    /**
     * {@inheritdoc}
     */
    public function parse($rules): array
    {
        return is_array($rules) ? $rules : [$rules];
    }
}