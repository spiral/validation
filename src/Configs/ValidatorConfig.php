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
        'checkers'   => [],
        'conditions' => [],
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
     * @todo AutowireTrait
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

        if (is_string($this->config['checkers'][$name])) {
            return new Autowire($this->config['checkers'][$name]);
        }

        if (isset($this->config['checkers'][$name]['class'])) {
            return new Autowire(
                $this->config['checkers'][$name]['class'],
                $this->config['checkers'][$name]['options'] ?? []
            );
        }

        throw new ValidationException("Invalid checker definition for `{$name}`.");
    }
}