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
    /** @var ValidatorConfig */
    private $config;

    /** @var ContainerInterface */
    private $container;

//    /** @var RuleInterface[] */
//    private $rules;

    /**
     * @param ValidatorConfig    $config
     * @param ContainerInterface $container
     */
    public function __construct(ValidatorConfig $config, ContainerInterface $container)
    {
        $this->config = $config;
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function getRules($schema): array
    {
        if (is_string($schema)) {
            $schema = $this->config->resolveAlias($schema);
        }

        // todo: fetch parameters
        // todo: fetch message
        // todo: fetch conditions
        // todo: fetch method name
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
}