<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Core\Container\Autowire;
use Spiral\Validation\Configs\ValidatorConfig;

class ConfigTest extends TestCase
{
    public function testHasChecker()
    {
        $config = new ValidatorConfig([
            'checkers' => [
                'checker' => self::class
            ]
        ]);

        $this->assertTrue($config->hasChecker('checker'));
        $this->assertFalse($config->hasChecker('other'));
    }

    public function testHasCondition()
    {
        $config = new ValidatorConfig([
            'conditions' => [
                'condition' => self::class
            ]
        ]);

        $this->assertTrue($config->hasCondition('condition'));
        $this->assertFalse($config->hasCondition('other'));
    }

    public function testGetChecker()
    {
        $config = new ValidatorConfig([
            'checkers' => [
                'checker' => self::class
            ]
        ]);

        $this->assertInstanceOf(Autowire::class, $config->getChecker('checker'));
    }

    public function testGetCondition()
    {
        $config = new ValidatorConfig([
            'conditions' => [
                'condition' => self::class
            ]
        ]);

        $this->assertInstanceOf(Autowire::class, $config->getCondition('condition'));
    }


    /**
     * @expectedException \Spiral\Validation\Exceptions\ValidationException
     */
    public function testGetCheckerException()
    {
        $config = new ValidatorConfig([
            'checkers' => [
                'checker' => self::class
            ]
        ]);

        $config->getChecker('other');
    }

    /**
     * @expectedException \Spiral\Validation\Exceptions\ValidationException
     */
    public function getConditionException()
    {
        $config = new ValidatorConfig([
            'conditions' => [
                'condition' => self::class
            ]
        ]);

        $config->getCondition('other');
    }

    public function testGetCheckerExtended()
    {
        $config = new ValidatorConfig([
            'checkers' => [
                'checker' => [
                    'class' => self::class
                ]
            ]
        ]);

        $this->assertInstanceOf(Autowire::class, $config->getChecker('checker'));
    }

    public function testGetConditionExtended()
    {
        $config = new ValidatorConfig([
            'conditions' => [
                'condition' => [
                    'class' => self::class
                ]
            ]
        ]);

        $this->assertInstanceOf(Autowire::class, $config->getCondition('condition'));
    }

    public function testGetCheckerExtendedWithOptions()
    {
        $config = new ValidatorConfig([
            'checkers' => [
                'checker' => [
                    'class'   => self::class,
                    'options' => []
                ]
            ]
        ]);

        $this->assertInstanceOf(Autowire::class, $config->getChecker('checker'));
    }

    public function testGetConditionWithOptions()
    {
        $config = new ValidatorConfig([
            'conditions' => [
                'condition' => [
                    'class'   => self::class,
                    'options' => []
                ]
            ]
        ]);

        $this->assertInstanceOf(Autowire::class, $config->getCondition('condition'));
    }

    /**
     * @expectedException \Spiral\Validation\Exceptions\ValidationException
     */
    public function testInvalid()
    {
        $config = new ValidatorConfig([
            'checkers' => [
                'checker' => []
            ]
        ]);

        $config->getChecker('checker');
    }

    /**
     * @expectedException \Spiral\Validation\Exceptions\ValidationException
     */
    public function testInvalidCondition()
    {
        $config = new ValidatorConfig([
            'conditions' => [
                'condition' => []
            ]
        ]);

        $config->getCondition('condition');
    }
}