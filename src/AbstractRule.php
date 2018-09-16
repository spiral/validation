<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

abstract class AbstractRule implements RuleInterface
{
    /** @var \SplObjectStorage|ConditionInterface[]|null */
    private $conditions;

    /**
     * @inheritdoc
     */
    public function withConditions(\SplObjectStorage $conditions = null): RuleInterface
    {
        $rule = clone $this;
        $rule->conditions = $conditions;

        return $rule;
    }

    /**
     * @inheritdoc
     */
    public function getConditions(): \Generator
    {
        if (empty($this->conditions)) {
            return;
        }

        foreach ($this->conditions as $condition) {
            yield $condition->withOptions($this->conditions->offsetGet($condition));
        }
    }
}