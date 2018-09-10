<?php

namespace Spiral\Validation\Checkers;

use Spiral\Core\Container\SingletonInterface;
use Spiral\Core\FactoryInterface;
use Spiral\Validation\AbstractChecker;

class EntityChecker extends AbstractChecker implements SingletonInterface
{
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
     * @param mixed  $value
     * @param string $source
     * @param string $column
     *
     * @return bool
     */
    public function unique($value, string $source, string $column): bool
    {
        $entity = $this->getValidator()->getContext();
        $source = $this->getSource($source);

        if (!empty($entity) && !$source->hasUpdates($entity, $column, $value)) {
            return true;
        }

        return empty($source->findByColumn($column, $value));
    }

    /**
     * @param string $class
     *
     * @return EntityChecker\SourceInterface
     */
    private function getSource(string $class): EntityChecker\SourceInterface
    {
        return $this->factory->make($class);
    }
}