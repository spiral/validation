<?php

namespace Spiral\Validation;

abstract class Condition implements ConditionInterface
{
    /** @var array */
    protected $options;

    /**
     * {@inheritdoc}
     */
    public function withOptions(?array $options): ConditionInterface
    {
        $condition = clone $this;
        $condition->options = $options ?? [];

        return $condition;
    }
}