<?php

namespace Spiral\Validation\Checkers\ValuesChecker;

interface RegistryInterface
{
    /**
     * @param null|string $column
     *
     * @return array
     */
    public function populate(?string $column = null): array;
}