<?php

namespace Spiral\Validation\Checkers\EntityChecker;

interface SourceInterface
{
    /**
     * @param string $column
     * @param mixed  $value
     *
     * @return mixed
     */
    public function findByColumn(string $column, $value);

    /**
     * @param        $entity
     * @param string $field
     * @param mixed  $value
     *
     * @return bool
     */
    public function hasUpdates($entity, string $field, $value): bool;
}