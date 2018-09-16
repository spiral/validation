<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

abstract class AbstractCondition implements ConditionInterface
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