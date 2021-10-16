<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\Route404Handler;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Throwable;

use function assert;

/** @psalm-suppress PropertyNotSetInConstructor */
class Route404HandlerTest extends TestCase
{
    public function testInvoke(): void
    {
        $serverRequestStub = $this->createMock(
            ServerRequestInterface::class,
        );

        $route404Handler = new Route404Handler();

        $exception = null;

        try {
            $route404Handler(request: $serverRequestStub);
        } catch (Throwable $e) {
            $exception = $e;
        }

        self::assertInstanceOf(
            HttpNotFoundException::class,
            $exception,
        );

        assert($exception instanceof HttpNotFoundException);

        self::assertSame(
            $serverRequestStub,
            $exception->getRequest(),
        );
    }
}
