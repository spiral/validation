<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Conditions;

use Spiral\Validation\AbstractCondition;
use Spiral\Validation\ValidatorInterface;

/**
 * Fires when all of listed values are empty.
 */
class WithoutAllCondition extends AbstractCondition
{
    /**
     * @param ValidatorInterface $validator
     * @param string             $field
     * @param mixed              $value
     * @return bool
     */
    public function isMet(ValidatorInterface $validator, string $field, $value): bool
    {
        foreach ($this->options as $field) {
            if (!empty($validator->getValue($field))) {
                return false;
            }
        }

        return true;
    }
}