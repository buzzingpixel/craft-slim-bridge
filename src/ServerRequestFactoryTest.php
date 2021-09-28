<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use PHPUnit\Framework\TestCase;
use Slim\Psr7\Request;

/** @psalm-suppress PropertyNotSetInConstructor */
class ServerRequestFactoryTest extends TestCase
{
    public function testMake(): void
    {
        $instance = new ServerRequestFactory();

        self::assertInstanceOf(
            Request::class,
            $instance->make(),
        );
    }
}
