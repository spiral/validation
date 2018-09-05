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
 * Represents options to describe singular validation rule.
 */
class CallableRule implements RuleInterface
{
    use TranslatorTrait;

    /**
     * Default validation message for custom rules.
     */
    const DEFAULT_MESSAGE = '[[Condition "{condition}" does not meet.]]';

    /** @var callable */
    private $check;

    /** @var ConditionInterface[] */
    private $conditions;

    /** @var array */
    private $args = [];

    /** @var string|null */
    private $message;

    /**
     * @param callable    $check
     * @param array       $conditions
     * @param array       $args
     * @param null|string $message
     */
    public function __construct(callable $check, array $conditions, array $args, ?string $message)
    {
        $this->check = $check;
        $this->conditions = $conditions;
        $this->args = $args;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     *
     * Attention: callable conditions are required for non empty values only.
     */
    public function ignoreEmpty($value): bool
    {
        return empty($value);
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
    public function validate(ValidatorInterface $v, string $field, $value): bool
    {
        $args = $this->args;
        array_unshift($args, $value);

        return call_user_func_array($this->check, $args);
    }

    /**
     * @inheritdoc
     */
    public function getMessage($value): string
    {
        return $this->say(
            $this->message ?? static::DEFAULT_MESSAGE,
            array_merge([$value], $this->args)
        );
    }
}