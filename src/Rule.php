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
class Rule
{
    /**
     * Default validation message for custom rules.
     */
    const DEFAULT_MESSAGE = '[[Condition "{condition}" does not meet.]]';



    public function isRequired(): bool
    {

    }

    public function validates($value): bool
    {
        return true;
    }

    public function getMessage($value): ?string
    {
        return null;
    }
}