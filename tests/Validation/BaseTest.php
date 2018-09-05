<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Core\BootloadManager;
use Spiral\Core\Container;
use Spiral\Validation\Bootloaders\ValidationBootloader;
use Spiral\Validation\Configs\ValidatorConfig;
use Spiral\Validation\ValidationInterface;
use Spiral\Validation\ValidatorInterface;

abstract class BaseTest extends TestCase
{
    const CONFIG = [
        'aliases'  => [],
        'checkers' => [],
    ];

    protected function makeValidator($data, array $rules, $context = null): ValidatorInterface
    {
        $container = new Container();
        $bootloader = new BootloadManager($container);
        $bootloader->bootload([ValidationBootloader::class]);

        $container->bind(ValidatorConfig::class, new ValidatorConfig(static::CONFIG));

        /**
         * @var ValidationInterface $provider
         */
        $provider = $container->get(ValidationInterface::class);

        return $provider->validate($data, $rules, $context);
    }
}