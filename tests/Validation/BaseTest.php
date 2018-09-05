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
use Spiral\Validation\Checkers\FileChecker;
use Spiral\Validation\Checkers\TypeChecker;
use Spiral\Validation\Configs\ValidatorConfig;
use Spiral\Validation\ValidationInterface;

abstract class BaseTest extends TestCase
{
    const CONFIG = [
        'checkers' => [
            'file'    => FileChecker::class,
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

    /**
     * @var Container
     */
    protected $container;

    public function setUp()
    {
        $this->container = new Container();
        (new BootloadManager($this->container))->bootload([ValidationBootloader::class]);

        $this->container->bind(ValidatorConfig::class, new ValidatorConfig(static::CONFIG));

        $this->validation = $this->container->get(ValidationInterface::class);
    }

    protected function assertValid(array $data, array $rules)
    {
        $this->assertTrue(
            $this->validation->validate($data, $rules)->isValid(),
            'Validation FAILED'
        );
    }

    protected function assertFail(string $error, array $data, array $rules)
    {
        $validator = $this->validation->validate($data, $rules);

        $this->assertFalse($validator->isValid(), 'Validation PASSED');
        $this->assertArrayHasKey($error, $validator->getErrors());
    }
}