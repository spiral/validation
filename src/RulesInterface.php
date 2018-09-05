<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

/**
 * Responsible for providing validation rules based on given schema.
 */
interface RulesInterface
{
    /**
     * Parse rule definition into array of rules.
     *
     * @param array|string $rules
     *
     * @return RuleInterface[]
     *
     * @throws \Spiral\Validation\Exceptions\ValidationException
     */
    public function getRules($rules): array;
}