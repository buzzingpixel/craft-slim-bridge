<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback\SetRouteFirstRunCallbackFactory;
use BuzzingPixel\SlimBridge\SlimAppFactory;
use craft\events\SetElementRouteEvent;
use PHPUnit\Framework\TestCase;

/** @psalm-suppress PropertyNotSetInConstructor */
class SetRouteFromParsedFactoryTest extends TestCase
{
    public function testMakeWhenNoMatch(): void
    {
        $parsedRouteStub = new ParsedRoute(
            isMatch: false,
            routeString: 'testRouteString',
        );

        $eventStub = $this->createMock(
            SetElementRouteEvent::class,
        );

        $slimAppFactoryStub = $this->createMock(
            SlimAppFactory::class,
        );

        $setRouteFirstRunCallbackFactoryStub = $this->createMock(
            SetRouteFirstRunCallbackFactory::class,
        );

        $factory = new SetRouteFromParsedFactory(
            slimAppFactory: $slimAppFactoryStub,
            setRouteFirstRunCallbackFactory: $setRouteFirstRunCallbackFactoryStub,
        );

        self::assertInstanceOf(
            DoNotSetRoute::class,
            $factory->make(
                parsedRoute: $parsedRouteStub,
                event: $eventStub,
            ),
        );
    }

    public function testMakeWhenMatch(): void
    {
        $parsedRouteStub = new ParsedRoute(
            isMatch: true,
            routeString: 'testRouteString',
        );

        $eventStub = $this->createMock(
            SetElementRouteEvent::class,
        );

        $slimAppFactoryStub = $this->createMock(
            SlimAppFactory::class,
        );

        $setRouteFirstRunCallbackFactoryStub = $this->createMock(
            SetRouteFirstRunCallbackFactory::class,
        );

        $factory = new SetRouteFromParsedFactory(
            slimAppFactory: $slimAppFactoryStub,
            setRouteFirstRunCallbackFactory: $setRouteFirstRunCallbackFactoryStub,
        );

        self::assertInstanceOf(
            SetRoute::class,
            $factory->make(
                parsedRoute: $parsedRouteStub,
                event: $eventStub,
            ),
        );
    }
}
