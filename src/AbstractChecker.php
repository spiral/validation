<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

use Spiral\Translator\Traits\TranslatorTrait;
use Spiral\Validation\Exceptions\CheckerException;

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
     * List of check rules to be used for any value, including empty.
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
    public function getMessage(string $method, $value, array $arguments = []): string
    {
        $messages = static::MESSAGES;
        if (isset($messages[$method])) {
            array_unshift($arguments, $value);

            return $this->say(static::MESSAGES[$method], $arguments);
        }

        return '';
    }

    /**
     * Currently active validator instance.
     *
     * @return ValidatorInterface
     */
    protected function getValidator(): ValidatorInterface
    {
        if (empty($this->validator)) {
            throw new CheckerException("Unable to receive associated checker validator");
        }

        return $this->validator;
    }
}