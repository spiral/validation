<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

interface RuleInterface
{
    /**
     * Check if value validation is required (for example some rules would not accept empty values
     * as this must be clearly stated by the rule).
     *
     * Example:
     * ["notEmpty", "email"] // fails on empty
     * ["email"]             // passed empty values
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function isRequired($value): bool;

    /**
     * Conditions associated with the rule.
     *
     * @return ConditionInterface[]
     */
    public function getConditions(): array;

    /**
     * @param ValidatorInterface $validator
     * @param string             $field
     * @param mixed              $value
     *
     * @return bool
     */
    public function validates(ValidatorInterface $validator, string $field, $value): bool;

    /**
     * Get validation error message.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function getMessage($value): string;
}