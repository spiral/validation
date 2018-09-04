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
        'aliases'  => [],
        'checkers' => [],
    ];

    /**
     * @param string $name
     *
     * @return Autowire
     *
     * @throws ValidationException
     */
    public function getChecker(string $name): Autowire
    {
        if (!isset($this->config['checkers'][$name])) {
            throw new ValidationException("Undefined checker {$name}.");
        }

        // todo: more options
        return new Autowire($this->config['checkers'][$name]);
    }
}