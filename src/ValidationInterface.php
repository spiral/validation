<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

/**
 * Creates validators with given rules and data.
 */
interface ValidationInterface
{
    public function createValidator($data, array $rules): ValidatorInterface;
}