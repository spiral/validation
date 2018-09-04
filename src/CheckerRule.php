<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

use Spiral\Translator\Translator;

class CheckerRule implements RuleInterface
{
    /** @var CheckerInterface */
    private $checker;

    /** @var string */
    private $method;

    /** @var ConditionInterface[] */
    private $conditions;

    /** @var array */
    private $args = [];

    /** @var string|null */
    private $message;

    /**
     * @param CheckerInterface $checker
     * @param string           $method
     * @param array            $conditions
     * @param array            $args
     * @param null|string      $message
     */
    public function __construct(
        CheckerInterface $checker,
        string $method,
        array $conditions,
        array $args,
        ?string $message
    ) {
        $this->checker = $checker;
        $this->method = $method;
        $this->conditions = $conditions;
        $this->args = $args;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function isRequired($value): bool
    {
        return $this->checker->isRequired($this->method, $value, $this->args);
    }

    /**
     * @inheritdoc
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @inheritdoc
     */
    public function validates(ValidatorInterface $v, string $field, $value): bool
    {
        return $this->checker->check($v, $this->method, $field, $value, $this->args);
    }

    /**
     * @inheritdoc
     */
    public function getMessage($value): string
    {
        if (!empty($this->message)) {
            return Translator::interpolate($this->message, array_merge([$value], $this->args));
        }

        return $this->checker->getMessage($this->method, $value, $this->args);
    }
}