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

    /** @var ValidatorInterface */
    private $validator = null;

    /**
     * {@inheritdoc}
     */
    public function withValidator(ValidatorInterface $validator): CheckerInterface
    {
        $checker = clone $this;
        $checker->validator = $validator;

        return $checker;
    }

    /**
     * {@inheritdoc}
     */
    public function check(string $method, $value, array $arguments = []): bool
    {
        array_unshift($arguments, $value);
        return call_user_func_array([$this, $method], $arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function errorMessage(string $method, $value, array $arguments = []): string
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