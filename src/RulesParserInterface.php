<?php

namespace Spiral\Validation;

interface RulesParserInterface
{
    /**
     * Parse rules to an array syntax.
     *
     * @param $rules
     *
     * @return array
     */
    public function parse($rules): array;
}