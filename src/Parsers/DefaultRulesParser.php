<?php

namespace Spiral\Validation\Parsers;

use Spiral\Validation\RulesParserInterface;

class DefaultRulesParser implements RulesParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse($rules): array
    {
        return is_array($rules) ? $rules : [$rules];
    }
}