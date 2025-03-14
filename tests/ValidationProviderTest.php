<?php

declare(strict_types=1);

namespace Spiral\Tests\Validation;

use Mockery as m;
use Spiral\Core\InvokerInterface;
use Spiral\Validation\ValidationInterface;
use Spiral\Validation\ValidationProvider;

final class ValidationProviderTest extends TestCase
{
    private m\MockInterface|InvokerInterface $invoker;
    private ValidationProvider $provider;

    public function testRegisterValidator(): void
    {
        $validation = m::mock(ValidationInterface::class);
        $resolver = static fn() => $validation;
        $params = ['baz' => 'bar'];

        $this->provider->register('foo', $resolver);

        $this->invoker->shouldReceive('invoke')
            ->once()
            ->with($resolver, $params)
            ->andReturn($validation);

        self::assertSame($validation, $this->provider->getValidation('foo', $params));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->invoker = m::mock(InvokerInterface::class);
        $this->provider = new ValidationProvider($this->invoker);
    }
}
