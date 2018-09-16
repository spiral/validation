<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation;

use Psr\Container\ContainerExceptionInterface;
use Spiral\Core\Container\Autowire;
use Spiral\Core\Container\SingletonInterface;
use Spiral\Core\FactoryInterface;
use Spiral\Validation\Configs\ValidatorConfig;

class ValidationProvider implements ValidationInterface, RulesInterface, SingletonInterface
{
    const ARGUMENTS  = ['args', 'params', 'arguments', 'parameters'];
    const MESSAGES   = ['message', 'msg', 'error', 'err'];
    const CONDITIONS = ['if', 'condition', 'conditions', 'where'];

    /** @var ValidatorConfig */
    private $config;

    /** @var FactoryInterface */
    private $factory;

    /** @var ParserInterface */
    private $parser;

    /** @var RuleInterface[] */
    private $rules = [];

    /**
     * @param ValidatorConfig  $config
     * @param FactoryInterface $factory
     * @param ParserInterface  $parser
     */
    public function __construct(
        ValidatorConfig $config,
        FactoryInterface $factory,
        ParserInterface $parser
    ) {
        $this->config = $config;
        $this->factory = $factory;
        $this->parser = $parser;
    }

    /**
     * @param array|\ArrayAccess $data
     * @param array              $rules
     * @param null               $context
     *
     * @return ValidatorInterface
     */
    public function validate($data, array $rules, $context = null): ValidatorInterface
    {
        return new Validator($data, $rules, $context, $this);
    }

    /**
     * @inheritdoc
     *
     * Attention, for performance reasons method would cache all defined rules.
     */
    public function getRules($rules): \Generator
    {
        foreach ($this->parser->split($rules) as $id => $rule) {
            if (empty($id) || $rule instanceof \Closure) {
                yield new CallableRule($rule);
                continue;
            }

            // fetch from cache
            if (isset($this->rules[$id])) {
                yield $this->rules[$id];
                continue;
            }

            $function = $this->parser->parseCheck($rule);
            $conditions = $this->parser->parseConditions($rule);

            $check = $this->makeRule(
                $this->config->mapFunction($function),
                $rule
            );

            yield $this->rules[$id] = $check->withConditions($this->makeConditions($conditions));
        }
    }

    /**
     * Reset rules cache.
     *
     * @codeCoverageIgnore
     */
    public function resetCache()
    {
        $this->rules = [];
    }

    /**
     * Destruct the service.
     *
     * @codeCoverageIgnore
     */
    public function __destruct()
    {
        $this->config = null;
        $this->factory = null;
        $this->resetCache();
    }

    /**
     * Construct rule object.
     *
     * @param mixed $check
     * @param mixed $rule
     * @return RuleInterface
     *
     * @throws ContainerExceptionInterface
     */
    protected function makeRule($check, $rule): RuleInterface
    {
        $args = $this->parser->parseArgs($rule);
        $message = $this->parser->parseMessage($rule);

        if (!is_array($check)) {
            return new CallableRule($check, $args, $message);
        }

        if (is_string($check[0]) && $this->config->hasChecker($check[0])) {
            $check[0] = $this->config->getChecker($check[0])->resolve($this->factory);

            return new CheckerRule($check[0], $check[1], $args, $message);
        }

        if (!is_object($check[0])) {
            $check[0] = (new Autowire($check[0]))->resolve($this->factory);
        }

        return new CallableRule($check, $args, $message);
    }

    /**
     * @param array $conditions
     * @return \SplObjectStorage
     *
     * @throws ContainerExceptionInterface
     */
    protected function makeConditions(array $conditions): ?\SplObjectStorage
    {
        if (empty($conditions)) {
            return null;
        }

        $storage = new \SplObjectStorage();
        foreach ($conditions as $condition => $options) {
            $condition = $this->config->resolveAlias($condition);
            
            if ($this->config->hasCondition($condition)) {
                $autowire = $this->config->getCondition($condition);
            } else {
                $autowire = new Autowire($condition);
            }

            $storage->attach($autowire->resolve($this->factory), $options);
        }

        return $storage;
    }
}