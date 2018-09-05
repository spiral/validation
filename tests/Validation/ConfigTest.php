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

    public function testGetChecker()
    {
        $config = new ValidatorConfig([
            'checkers' => [
                'checker' => self::class
            ]
        ]);

        $this->assertInstanceOf(Autowire::class, $config->getChecker('checker'));
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
}