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

    /** @var \SplObjectStorage */
    private $conditions;

    /** @var array */
    private $args = [];

    /** @var string|null */
    private $message;

    /**
     * @param CheckerInterface  $checker
     * @param string            $method
     * @param \SplObjectStorage $conditions
     * @param array             $args
     * @param null|string       $message
     */
    public function __construct(
        CheckerInterface $checker,
        string $method,
        \SplObjectStorage $conditions,
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
    public function ignoreEmpty($value): bool
    {
        return $this->checker->ignoreEmpty($this->method, $value, $this->args);
    }

    /**
     * @inheritdoc
     */
    public function getConditions(): \Generator
    {
        /** @var ConditionInterface $condition */
        foreach ($this->conditions as $condition) {
            yield $condition->withOptions($this->conditions->offsetGet($condition));
        }
    }

    /**
     * @inheritdoc
     */
    public function validate(ValidatorInterface $v, string $field, $value): bool
    {
        return $this->checker->check($v, $this->method, $field, $value, $this->args);
    }

    /**
     * @inheritdoc
     */
    public function getMessage(string $field, $value): string
    {
        if (!empty($this->message)) {
            return Translator::interpolate($this->message, array_merge([$value, $field], $this->args));
        }

        return $this->checker->getMessage($this->method, $field, $value, $this->args);
    }
}