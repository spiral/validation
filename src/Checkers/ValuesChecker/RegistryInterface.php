<?php

namespace Spiral\Validation\Checkers\ValuesChecker;

interface RegistryInterface
{
    /**
     * @param string $column
     *
     * @return array
     */
    public function populate(string $column): array;
}