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
     * @return bool
     */
    public function isRequired(): bool;

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