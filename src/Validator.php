<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

use Spiral\Models\AccessorInterface;
use Spiral\Translator\Traits\TranslatorTrait;

class Validator implements ValidatorInterface
{
    use TranslatorTrait;

    /**
     * Provides checkers, rules and conditions access.
     *
     * @var ValidationsInterface
     */
    private $provider;

    /**
     * @var array|\ArrayAccess
     */
    private $data;

    /**
     * Custom validation context, not validated but available for checkers and conditions.
     *
     * @var mixed
     */
    private $context;

    /**
     * Validation rules, see class title for description.
     *
     * @var array
     */
    private $rules;

    /**
     * Validation errors.
     *
     * @var array
     */
    private $errors;

    /**
     * Manually registered errors.
     *
     * @var array
     */
    private $registeredErrors;

    /**
     * @param array|\ArrayAccess   $data
     * @param array                $rules
     * @param mixed                $context
     * @param ValidationsInterface $provider
     */
    public function __construct($data, array $rules, $context, ValidationsInterface $provider)
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
        $this->registeredErrors[$field] = $error;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getErrors(): array
    {
        $this->validate();

        return $this->registeredErrors + $this->errors;
    }

    /**
     * @inheritdoc
     */
    public function resetState()
    {
        $this->errors = [];
        $this->registeredErrors = [];
    }

    /**
     * Validate data over given rules and context.
     */
    protected function validate()
    {
        $this->errors = [];
        foreach ($this->rules as $field => $rules) {
            foreach ($this->parseRules($field, $rules) as $rule) {
                print_r($rule);

            }
        }
    }

    /**
     * Parse all given validation rules.
     *
     * @param string       $field
     * @param string|array $rules
     * @return Rule[]
     */
    protected function parseRules(string $field, $rules): array
    {

    }
}