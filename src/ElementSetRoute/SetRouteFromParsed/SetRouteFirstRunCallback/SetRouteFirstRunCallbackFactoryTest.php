<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use craft\base\Element;
use PHPUnit\Framework\TestCase;

/** @psalm-suppress PropertyNotSetInConstructor */
class SetRouteFirstRunCallbackFactoryTest extends TestCase
{
    public function testMakeWhenNoRoutingCallback(): void
    {
        $elementStub = $this->createMock(Element::class);

        $parsedRouteStub = new ParsedRoute(
            isMatch: true,
            routeString: RoutingCallbackForTesting::class,
        );

        $factory = new SetRouteFirstRunCallbackFactory();

        $contractReturn = $factory->make(
            element: $elementStub,
            parsedRoute: $parsedRouteStub,
        );

        self::assertInstanceOf(
            CallbackForNoRoutingCallback::class,
            $contractReturn,
        );
    }

    public function testMakeWhenRoutingCallback(): void
    {
        $elementStub = $this->createMock(Element::class);

        $parsedRouteStub = new ParsedRoute(
            isMatch: true,
            routeString: RoutingCallbackContractImplementationForTesting::class,
        );

        $factory = new SetRouteFirstRunCallbackFactory();

        $contractReturn = $factory->make(
            element: $elementStub,
            parsedRoute: $parsedRouteStub,
        );

        self::assertInstanceOf(
            CallbackForRoutingCallback::class,
            $contractReturn,
        );
    }
}
