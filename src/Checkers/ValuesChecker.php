<?php

namespace Spiral\Validation\Checkers;

use Spiral\Core\Container\SingletonInterface;
use Spiral\Core\FactoryInterface;
use Spiral\Validation\AbstractChecker;

class ValuesChecker extends AbstractChecker implements SingletonInterface
{
    /**
     * {@inheritdoc}
     */
    const MESSAGES = [
        'any'     => '[[At least one value is required.]]',
        'allowed' => '[[The selection contains unexpected value.]]'
    ];

    /**
     * {@inheritdoc}
     */
    const ALLOW_EMPTY_VALUES = ['any', 'allowed'];

    /** @var FactoryInterface */
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Check for values in given field array. At least one value should be presented.
     * Should be applied to a global error-handling field (meaning this will be an empty field with no data),
     * so this method is registered for handling empty values.
     *
     * @param string $field
     *
     * @return bool
     */
    public function any($field): bool
    {
        $value = $this->getValidator()->getValue($field);
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!empty($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for values in given field array. Should have only allowed values from registry holder.
     * Should be applied to a global error-handling field (meaning this will be an empty field with no data),
     * so this method is registered for handling empty values.
     *
     * @param string $field
     * @param string $registry
     * @param string $column
     *
     * @return bool
     */
    public function allowed($field, string $registry, string $column): bool
    {
        $values = (array)$this->getValidator()->getValue($field);
        $registry = $this->getRegistry($registry);
        $diff = array_diff($values, $registry->populate($column));

        return empty($diff);
    }

    /**
     * @param string $class
     *
     * @return ValuesChecker\RegistryInterface
     */
    private function getRegistry(string $class): ValuesChecker\RegistryInterface
    {
        return $this->factory->make($class);
    }
}