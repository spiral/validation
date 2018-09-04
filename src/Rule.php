<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

/**
 * Represents options to describe singular validation rule.
 */
final class Rule
{
    /**
     * Default validation message for custom rules.
     */
    const DEFAULT_MESSAGE = '[[Condition "{condition}" does not meet.]]';

    public function getFunction(): callable
    {
        return 'in_array';
    }

    public function getArguments(): array
    {
        return [];
    }

    public function getMessage(): ?string
    {
        return null;
    }

    /**
     * @return ConditionInterface[]
     */
    public function getConditions(): array
    {
        return [];
    }

    public static function parse($rule): Rule
    {
        return new Rule();
    }

}