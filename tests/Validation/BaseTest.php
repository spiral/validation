<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Validation\Tests;

use PHPUnit\Framework\TestCase;
use Spiral\Core\Container;
use Spiral\Validation\Checker\AddressChecker;
use Spiral\Validation\Checker\FileChecker;
use Spiral\Validation\Checker\ImageChecker;
use Spiral\Validation\Checker\StringChecker;
use Spiral\Validation\Checker\TypeChecker;
use Spiral\Validation\Config\ValidatorConfig;
use Spiral\Validation\ParserInterface;
use Spiral\Validation\RuleParser;
use Spiral\Validation\RulesInterface;
use Spiral\Validation\ValidationInterface;
use Spiral\Validation\ValidationProvider;

abstract class BaseTest extends TestCase
{
    const CONFIG = [
        'checkers' => [
            'file'    => FileChecker::class,
            'image'   => ImageChecker::class,
            'type'    => TypeChecker::class,
            'address' => AddressChecker::class,
            'string'  => StringChecker::class
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

        $this->container->bindSingleton(ValidationInterface::class, ValidationProvider::class);
        $this->container->bindSingleton(RulesInterface::class, ValidationProvider::class);
        $this->container->bindSingleton(ParserInterface::class, RuleParser::class);

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

    protected function assertNotValid(string $error, array $data, array $rules)
    {
        $validator = $this->validation->validate($data, $rules);

        $this->assertFalse($validator->isValid(), 'Validation PASSED');
        $this->assertArrayHasKey($error, $validator->getErrors());
    }
}