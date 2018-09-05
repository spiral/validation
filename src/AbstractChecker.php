<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

use Spiral\Translator\Traits\TranslatorTrait;

/**
 * @inherit-messages
 */
abstract class AbstractChecker implements CheckerInterface
{
    use TranslatorTrait;

    /**
     * Error messages associated with checker method by name.
     */
    const MESSAGES = [];

    /**
     * List of methods which are allowed to handle empty values.
     */
    const ON_EMPTY = [];

    /** @var ValidatorInterface */
    private $validator = null;

    /**
     * {@inheritdoc}
     */
    public function ignoreEmpty(string $method, $value, array $args): bool
    {
        if (!empty($value)) {
            return false;
        }

        return !in_array($method, static::ON_EMPTY);
    }

    /**
     * {@inheritdoc}
     */
    public function check(
        ValidatorInterface $v,
        string $method,
        string $field,
        $value,
        array $args = []
    ): bool {

        try {
            $this->validator = $v;
            array_unshift($args, $value);

            return call_user_func_array([$this, $method], $args);
        } finally {
            $this->validator = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(string $method, string $field, $value, array $arguments = []): string
    {
        $messages = static::MESSAGES;
        if (isset($messages[$method])) {
            array_unshift($arguments, $value);
            array_unshift($arguments, $field);

            return $this->say(static::MESSAGES[$method], $arguments);
        }

        return 'Condition does not met.';
    }

    /**
     * @return ValidatorInterface
     */
    protected function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }
}