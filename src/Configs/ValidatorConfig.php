<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Configs;

use Spiral\Core\Container\Autowire;
use Spiral\Core\InjectableConfig;
use Spiral\Core\Traits\Config\AliasTrait;
use Spiral\Validation\Exceptions\ValidationException;

class ValidatorConfig extends InjectableConfig
{
    use AliasTrait;

    const CONFIG = 'validation';

    /**
     * @var array
     */
    protected $config = [
        'conditions' => [],
        'checkers'   => [],
        'aliases'    => [],
    ];

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasChecker(string $name): bool
    {
        return isset($this->config['checkers'][$name]);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasCondition(string $name): bool
    {
        return isset($this->config['conditions'][$name]);
    }

    /**
     * @todo AutowireTrait or Autowire::parse
     *
     * @param string $name
     *
     * @return Autowire
     *
     * @throws ValidationException
     */
    public function getChecker(string $name): Autowire
    {
        if (!$this->hasChecker($name)) {
            throw new ValidationException("Undefined checker {$name}.");
        }

        $instance = $this->wire('checkers', $name);
        if (!empty($instance)) {
            return $instance;
        }

        throw new ValidationException("Invalid checker definition for `{$name}`.");
    }

    /**
     * @param string $name
     *
     * @return Autowire
     */
    public function getCondition(string $name): Autowire
    {
        if (!$this->hasCondition($name)) {
            throw new ValidationException("Undefined condition {$name}.");
        }

        $instance = $this->wire('conditions', $name);
        if (!empty($instance)) {
            return $instance;
        }

        throw new ValidationException("Invalid condition definition for `{$name}`.");
    }

    /**
     * @param string $section
     * @param string $name
     *
     * @return null|Autowire
     */
    private function wire(string $section, string $name): ?Autowire
    {
        if (is_string($this->config[$section][$name])) {
            return new Autowire($this->config[$section][$name]);
        }

        if (isset($this->config[$section][$name]['class'])) {
            return new Autowire(
                $this->config[$section][$name]['class'],
                $this->config[$section][$name]['options'] ?? []
            );
        }

        return null;
    }
}