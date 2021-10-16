<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback\SetRouteFirstRunCallbackFactory;
use BuzzingPixel\SlimBridge\SlimAppFactory;
use craft\base\Element;
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

    public function testMakeWhenMatchAndUriIsNormal(): void
    {
        $parsedRouteStub = new ParsedRoute(
            isMatch: true,
            routeString: 'testRouteString',
        );

        $elementStub = $this->createMock(Element::class);

        $elementStub->uri = 'test/uri';

        $eventStub = $this->createMock(
            SetElementRouteEvent::class,
        );

        $eventStub->sender = $elementStub;

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

    public function testMakeWhenUriIsHome(): void
    {
        $parsedRouteStub = new ParsedRoute(
            isMatch: true,
            routeString: 'testRouteString',
        );

        $elementStub = $this->createMock(Element::class);

        $elementStub->uri = '__home__';

        $eventStub = $this->createMock(
            SetElementRouteEvent::class,
        );

        $eventStub->sender = $elementStub;

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

    public function testMakeWhenUriStartsWithUnderscore(): void
    {
        $parsedRouteStub = new ParsedRoute(
            isMatch: true,
            routeString: 'testRouteString',
        );

        $elementStub = $this->createMock(Element::class);

        $elementStub->uri = '__404__';

        $eventStub = $this->createMock(
            SetElementRouteEvent::class,
        );

        $eventStub->sender = $elementStub;

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
            SetRouteAsNotFound::class,
            $factory->make(
                parsedRoute: $parsedRouteStub,
                event: $eventStub,
            ),
        );
    }
}
