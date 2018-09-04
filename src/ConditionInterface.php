<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

/**
 * Enables and disabled field validation.
 */
interface ConditionInterface
{
    /**
     * Checks if condition is met and field must be validated by the following rule.
     *
     * @param ValidatorInterface $validator
     * @param string             $field
     * @param mixed              $value
     *
     * @return bool
     */
    public function isMet(ValidatorInterface $validator, string $field, $value): bool;
}