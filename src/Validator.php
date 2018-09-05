<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

use Spiral\Models\AccessorInterface;

class Validator implements ValidatorInterface
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
    private $errors;

    /** @var array */
    private $userErrors;

    /**
     * @param array|\ArrayAccess $data
     * @param array              $rules
     * @param mixed              $context
     * @param RulesInterface     $provider
     */
    public function __construct($data, array $rules, $context, RulesInterface $provider)
    {
        $this->provider = $provider;
        $this->data = $data;
        $this->rules = $rules;
        $this->context = $context;
    }

    /**
     * @inheritdoc
     */
    public function getValue(string $field, $default = null)
    {
        $value = isset($this->data[$field]) ? $this->data[$field] : $default;

        return ($value instanceof AccessorInterface) ? $value->packValue() : $value;
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
    public function registerError(string $field, string $error): ValidatorInterface
    {
        $this->userErrors[$field] = $error;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getErrors(): array
    {
        $this->validate();

        return $this->userErrors + $this->errors;
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
     * @inheritdoc
     */
    public function resetState()
    {
        $this->errors = [];
        $this->userErrors = [];
    }

    /**
     * Destruct the service.
     */
    public function __destruct()
    {
        $this->data = null;
        $this->rules = [];
        $this->provider = null;
        $this->resetState();
    }

    /**
     * Validate data over given rules and context.
     *
     * @throws \Spiral\Validation\Exceptions\ValidationException
     */
    protected function validate()
    {
        $this->errors = [];

        foreach ($this->rules as $field => $rules) {

            $value = $this->getValue($field);

            foreach ($this->provider->getRules(is_array($rules) ? $rules : [$rules]) as $rule) {
                if ($this->hasError($field)) {
                    break;
                }

                if ($rule->ignoreEmpty($value)) {
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
                    $this->errors[$field] = $rule->getMessage($value);
                    break;
                }
            }
        }
    }
}