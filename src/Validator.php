<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Validation;

final class Validator implements ValidatorInterface
{
    /** @var RulesInterface */
    private $provider;

    /** @var array|\ArrayAccess */
    private $data;

    /** @var mixed */
    private $context;

    /** @var array */
    private $rules;

    /** @var array */
    private $errors = [];

    /**
     * @param array|\ArrayAccess $data
     * @param array              $rules
     * @param mixed              $context
     * @param RulesInterface     $provider
     */
    public function __construct($data, array $rules, $context, RulesInterface $provider)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->context = $context;
        $this->provider = $provider;
    }

    /**
     * @inheritdoc
     */
    public function getValue(string $field, $default = null)
    {
        $value = isset($this->data[$field]) ? $this->data[$field] : $default;

        if (is_object($value) && method_exists($value, 'packValue')) {
            return $value->packValue();
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @inheritdoc
     */
    public function isValid(): bool
    {
        return empty($this->getErrors());
    }

    /**
     * @inheritdoc
     */
    public function getErrors(): array
    {
        $this->validate();

        return $this->errors;
    }

    /**
     * Check if value has any error associated.
     *
     * @param string $field
     *
     * @return bool
     */
    public function hasError(string $field): bool
    {
        return isset($this->getErrors()[$field]);
    }

    /**
     * Destruct the service.
     */
    public function __destruct()
    {
        $this->data = null;
        $this->rules = [];
        $this->provider = null;
        $this->errors = [];
    }

    /**
     * Validate data over given rules and context.
     *
     * @throws \Spiral\Validation\Exception\ValidationException
     */
    protected function validate()
    {
        if (!empty($this->errors)) {
            // already validated
            return;
        }

        $this->errors = [];

        foreach ($this->rules as $field => $rules) {
            $value = $this->getValue($field);

            foreach ($this->provider->getRules($rules) as $rule) {
                if ($rule->ignoreEmpty($value) && empty($rule->hasConditions())) {
                    continue;
                }

                foreach ($rule->getConditions() as $condition) {
                    if (!$condition->isMet($this, $field, $value)) {
                        // condition is not met, skipping validation
                        continue 2;
                    }
                }

                if (!$rule->validate($this, $field, $value)) {
                    // got error, jump to next field
                    $this->errors[$field] = $rule->getMessage($field, $value);
                    break;
                }
            }
        }
    }
}
