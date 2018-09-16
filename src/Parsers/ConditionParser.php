<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Parsers;

use Spiral\Core\Container\Autowire;
use Spiral\Core\FactoryInterface;
use Spiral\Validation\Configs\ValidatorConfig;

class ConditionParser
{
    const CONDITIONS = ['if', 'condition', 'conditions', 'where'];

    /** @var ValidatorConfig */
    private $config;

    /** @var FactoryInterface */
    private $factory;

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
     * @param $condition
     *
     * @return \SplObjectStorage
     */
    public function parse($condition): \SplObjectStorage
    {
        $storage = new \SplObjectStorage();
        foreach ((array)$condition as $key => $value) {
            if (is_numeric($key)) {
                $this->fill($storage, $value);
            } else {
                $this->fill($storage, $key, (array)$value);
            }
        }

        return $storage;
    }

    /**
     * @param \SplObjectStorage $storage
     * @param string            $condition
     * @param null              $data
     */
    private function fill(\SplObjectStorage $storage, string $condition, $data = null)
    {
        $storage->attach($this->wire($condition)->resolve($this->factory), $data);
    }

    /**
     * @param string $condition
     *
     * @return Autowire
     */
    private function wire(string $condition): Autowire
    {
        if ($this->config->hasCondition($condition)) {
            return $this->config->getCondition($condition);
        }

        return new Autowire($condition);
    }
}