<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

use Psr\Container\ContainerInterface;
use Spiral\Core\Container\SingletonInterface;
use Spiral\Core\FactoryInterface;
use Spiral\Validation\Configs\ValidatorConfig;

class ValidationProvider implements ValidationInterface, RulesInterface, SingletonInterface
{
    const ARGUMENTS = ['args', 'params', 'arguments', 'parameters'];
    const MESSAGES = ['message', 'msg', 'error', 'err'];

    /** @var ValidatorConfig */
    private $config;

    /** @var ContainerInterface */
    private $factory;

    /**
     * Rules cache.
     *
     * @var RuleInterface[]
     */
    private $rules = [];

    /**
     * @param ValidatorConfig  $config
     * @param FactoryInterface $factory
     */
    public function __construct(ValidatorConfig $config, FactoryInterface $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    /**
     * @param array|\ArrayAccess $data
     * @param array              $rules
     * @param null               $context
     *
     * @return ValidatorInterface
     */
    public function createValidator($data, array $rules, $context = null): ValidatorInterface
    {
        return new Validator($data, $rules, $context, $this);
    }

    /**
     * Reset rules cache.
     */
    public function resetCache()
    {
        $this->rules = [];
    }

    /**
     * @inheritdoc
     *
     * Attention, for performance reasons method would cache all defined rules.
     */
    public function getRules(array $rules): array
    {
        $result = [];
        foreach ($rules as $rule) {
            $id = json_encode($rule);
            if (isset($this->rules[$id])) {
                $result[] = $this->rules[$id];
                continue;
            }

            if (is_string($rule)) {
                $check = $rule;
            } else {
                $check = $rule[0];
            }

            if (is_string($check)) {
                $check = $this->config->resolveAlias($check);
                if (strpos($check, ':') !== false) {
                    $check = explode(':', str_replace('::', ':', $check));
                }
            }

            // todo: getConditions

            if (is_array($check)) {
                if (is_string($check[0]) && $this->config->hasChecker($check[0])) {
                    $check[0] = $this->config->getChecker($check[0])->resolve($this->factory);

                    $result[] = $this->rules[$id] = new CheckerRule(
                        $check[0],
                        $check[1],
                        [],
                        $this->fetchArgs($rule),
                        $this->fetchMessage($rule)
                    );

                    continue;
                }

                $check[0] = is_object($check[0]) ? $check[0] : $this->factory->get($check[0]);
            }

            $result[] = $this->rules[$id] = new CallableRule(
                $check,
                [],
                $this->fetchArgs($rule),
                $this->fetchMessage($rule)
            );
        }

        return $result;
    }

    /**
     * Destruct the service.
     */
    public function __destruct()
    {
        $this->config = null;
        $this->factory = null;
        $this->resetCache();
    }

    /**
     * Fetch validation rule arguments from rule definition.
     *
     * @param array $rule
     *
     * @return array
     */
    private function fetchArgs(array $rule): array
    {
        if (!is_array($rule)) {
            return [];
        }

        foreach (self::ARGUMENTS as $index) {
            if (isset($rule[$index])) {
                return $rule[$index];
            }
        }

        foreach (self::MESSAGES as $index) {
            unset($rule[0], $rule[$index], $rule[$index]);
        }

        return array_values($rule);
    }

    /**
     * Fetch error message from rule definition or use default message. Method will check "message"
     * and "error" properties of definition.
     *
     * @param array $rule
     *
     * @return string
     */
    private function fetchMessage($rule): ?string
    {
        if (!is_array($rule)) {
            return null;
        }

        foreach (self::MESSAGES as $index) {
            if (isset($rule[$index])) {
                return $rule[$index];
            }
        }

        return null;
    }
}