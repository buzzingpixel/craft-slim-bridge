<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing;

use PHPUnit\Framework\TestCase;

/** @psalm-suppress PropertyNotSetInConstructor */
class RouteParserTest extends TestCase
{
    public function testParseRouteStringWhenRouteInvalid(): void
    {
        $routeParser = new RouteParser();

        $parsedRoute = $routeParser->parseRouteString(
            fullRouteString: 'test-template.twig',
        );

        self::assertFalse($parsedRoute->isMatch());

        self::assertSame('', $parsedRoute->routeString());
    }

    public function testParseRouteStringWhenRouteInvalidTwo(): void
    {
        $routeParser = new RouteParser();

        $parsedRoute = $routeParser->parseRouteString(
            fullRouteString: '_slimBridges/\App\Http\Response\DoSomeAction',
        );

        self::assertFalse($parsedRoute->isMatch());

        self::assertSame(
            '\App\Http\Response\DoSomeAction',
            $parsedRoute->routeString(),
        );
    }

    public function testParseRouteStringWhenRouteIsValid(): void
    {
        $routeParser = new RouteParser();

        $parsedRoute = $routeParser->parseRouteString(
            fullRouteString: '_slimBridge/\App\Http\Response\DoSomeAction',
        );

        self::assertTrue($parsedRoute->isMatch());

        self::assertSame(
            '\App\Http\Response\DoSomeAction',
            $parsedRoute->routeString(),
        );
    }
}
