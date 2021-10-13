<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\RouteParser;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFromParsedFactory;
use craft\base\Element;
use craft\elements\Category;
use craft\elements\Entry;
use craft\events\SetElementRouteEvent;
use craft\services\Categories;
use craft\services\Sites;
use PHPUnit\Framework\TestCase;

/** @psalm-suppress PropertyNotSetInConstructor */
class SetRouteFactoryTest extends TestCase
{
    public function testMakeWhenElementIsCategory(): void
    {
        $elementAsCategoryStub = $this->createMock(
            Category::class,
        );

        $eventStub = $this->createMock(
            SetElementRouteEvent::class,
        );

        $eventStub->sender = $elementAsCategoryStub;

        $sitesServiceStub = $this->createMock(Sites::class);

        $routeParserStub = $this->createMock(
            RouteParser::class,
        );

        $categoriesServiceStub = $this->createMock(
            Categories::class,
        );

        $setRouteFromParsedFactoryStub = $this->createMock(
            SetRouteFromParsedFactory::class,
        );

        $factory = new SetRouteFactory(
            sitesService: $sitesServiceStub,
            routeParser: $routeParserStub,
            categoriesService: $categoriesServiceStub,
            setRouteFromParsedFactory: $setRouteFromParsedFactoryStub,
        );

        self::assertInstanceOf(
            SetRouteForCategory::class,
            $factory->make($eventStub),
        );
    }

    public function testMakeWhenElementIsEntry(): void
    {
        $elementAsCategoryStub = $this->createMock(
            Entry::class,
        );

        $eventStub = $this->createMock(
            SetElementRouteEvent::class,
        );

        $eventStub->sender = $elementAsCategoryStub;

        $sitesServiceStub = $this->createMock(Sites::class);

        $routeParserStub = $this->createMock(
            RouteParser::class,
        );

        $categoriesServiceStub = $this->createMock(
            Categories::class,
        );

        $setRouteFromParsedFactoryStub = $this->createMock(
            SetRouteFromParsedFactory::class,
        );

        $factory = new SetRouteFactory(
            sitesService: $sitesServiceStub,
            routeParser: $routeParserStub,
            categoriesService: $categoriesServiceStub,
            setRouteFromParsedFactory: $setRouteFromParsedFactoryStub,
        );

        self::assertInstanceOf(
            SetRouteForEntry::class,
            $factory->make($eventStub),
        );
    }

    public function testMakeWhenElementIsUnsupported(): void
    {
        $elementAsCategoryStub = $this->createMock(
            Element::class,
        );

        $eventStub = $this->createMock(
            SetElementRouteEvent::class,
        );

        $eventStub->sender = $elementAsCategoryStub;

        $sitesServiceStub = $this->createMock(Sites::class);

        $routeParserStub = $this->createMock(
            RouteParser::class,
        );

        $categoriesServiceStub = $this->createMock(
            Categories::class,
        );

        $setRouteFromParsedFactoryStub = $this->createMock(
            SetRouteFromParsedFactory::class,
        );

        $factory = new SetRouteFactory(
            sitesService: $sitesServiceStub,
            routeParser: $routeParserStub,
            categoriesService: $categoriesServiceStub,
            setRouteFromParsedFactory: $setRouteFromParsedFactoryStub,
        );

        self::assertInstanceOf(
            SetRouteNotImplemented::class,
            $factory->make($eventStub),
        );
    }
}
