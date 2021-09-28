<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class SlimAppFactoryTest extends TestCase
{
    public function testInit(): void
    {
        $responseFactoryStub = $this->createMock(
            ResponseFactoryInterface::class,
        );

        $containerStub = $this->createMock(
            ContainerInterface::class,
        );

        $instance = new SlimAppFactory(responseFactory: $responseFactoryStub);

        $app = $instance->make(container: $containerStub);

        self::assertSame(
            $responseFactoryStub,
            $app->getResponseFactory(),
        );

        self::assertSame(
            $containerStub,
            $app->getContainer(),
        );
    }
}
