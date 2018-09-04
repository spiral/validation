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
use Spiral\Validation\Configs\ValidatorConfig;

class ValidationProvider implements ValidationInterface, RulesInterface, SingletonInterface
{
    private $config;
    private $container;

    /**
     * @param ValidatorConfig    $config
     * @param ContainerInterface $container
     */
    public function __construct(ValidatorConfig $config, ContainerInterface $container)
    {
        $this->config = $config;
        $this->container = $container;
    }
}