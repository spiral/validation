<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;


use Spiral\Validation\Exceptions\ValidationException;

interface ValidatorInterface
{
    /**
     * Get all validation data passed into validator.
     *
     * @return array|\ArrayAccess
     */
    public function getData();

    /**
     * Receive field from context data or return default value.
     *
     * @param string $field
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getValue(string $field, $default = null);

    /**
     * Check if context data valid accordingly to provided rules.
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public function isValid(): bool;

    /**
     * Evil tween of isValid() method should return true if data is not valid.
     *
     * @return bool
     *
     * @throws ValidationException
     */
    public function hasErrors(): bool;

    /**
     * List of errors associated with parent field, every field should have only one error assigned.
     *
     * @return array
     */
    public function getErrors(): array;

    /**
     * Register outer validation error. Registered error persists until context data are changed
     * or flushRegistered method not called.
     *
     * @param string $field
     * @param string $error
     * @return self
     */
    public function addError(string $field, string $error): ValidatorInterface;

    /**
     * Flush all registered errors.
     */
    public function flushRegistered();

    /**
     * Reset validation state.
     */
    public function reset();

    /**
     * Get context data (not validated).
     *
     * @return mixed
     */
    public function getContext();

    /**
     * Set context data (not validated).
     *
     * @param $context
     *
     * @return mixed
     */
    public function setContext($context);
}