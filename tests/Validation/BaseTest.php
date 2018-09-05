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
use Spiral\Validation\Checkers\AddressChecker;
use Spiral\Validation\Checkers\TypeChecker;
use Spiral\Validation\Configs\ValidatorConfig;
use Spiral\Validation\ValidationInterface;

abstract class BaseTest extends TestCase
{
    const CONFIG = [
        'checkers' => [
            'type'    => TypeChecker::class,
            'address' => AddressChecker::class,
        ],
        'aliases'  => [
            'notEmpty' => 'type::notEmpty',
            'email'    => 'address::email',
            'url'      => 'address::url',
        ],
    ];

    /**
     * @var ValidationInterface
     */
    protected $validation;

    public function setUp()
    {
        $container = new Container();
        (new BootloadManager($container))->bootload([ValidationBootloader::class]);

        $container->bind(ValidatorConfig::class, new ValidatorConfig(static::CONFIG));

        $this->validation = $container->get(ValidationInterface::class);
    }
}