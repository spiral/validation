<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

use Spiral\Validation\Exceptions\CheckerException;

interface CheckerInterface
{
    /**
     * Check value using checker method.
     *
     * @param ValidatorInterface $validator
     * @param string             $method
     * @param mixed              $value
     * @param array              $arguments
     *
     * @return bool
     *
     * @throws CheckerException
     */
    public function check(
        ValidatorInterface $validator,
        string $method,
        $value,
        array $arguments = []
    ): bool;

    /**
     * Return error message associated with check method.
     *
     * @param string $method
     * @param mixed  $value
     * @param array  $arguments
     *
     * @return string
     *
     * @throws CheckerException
     */
    public function getMessage(string $method, $value, array $arguments = []): string;
}