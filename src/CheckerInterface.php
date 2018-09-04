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
     * Version of checker with active local validator.
     *
     * @param ValidatorInterface $validator
     * @return CheckerInterface
     */
    public function withValidator(ValidatorInterface $validator): CheckerInterface;

    /**
     * Check value using checker method.
     *
     * @param string $method
     * @param mixed  $value
     * @param array  $arguments
     * @return bool
     *
     * @throws CheckerException
     */
    public function check(string $method, $value, array $arguments = []): bool;

    /**
     * Return default error message for checker condition.
     *
     * @param string $method
     * @param mixed  $value
     * @param array  $arguments
     * @return string
     *
     * @throws CheckerException
     */
    public function errorMessage(string $method, $value, array $arguments = []): string;
}