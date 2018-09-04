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
     * @param mixed $schema
     *
     * @return RuleInterface[]
     */
    public function getRules($schema): array;
}