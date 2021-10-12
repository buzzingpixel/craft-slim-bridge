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

use function assert;

class SetRouteFactory
{
    public function __construct(
        private Sites $sitesService,
        private RouteParser $routeParser,
        private Categories $categoriesService,
        private SetRouteFromParsedFactory $setRouteFromParsedFactory
    ) {
    }

    public function make(SetElementRouteEvent $event): SetRouteContract
    {
        $element = $event->sender;

        assert($element instanceof Element);

        if ($element instanceof Category) {
            return new SetRouteForCategory(
                event: $event,
                category: $element,
                routeParser: $this->routeParser,
                sitesService: $this->sitesService,
                categoriesService: $this->categoriesService,
                setRouteFromParsedFactory: $this->setRouteFromParsedFactory,
            );
        }

        if ($element instanceof Entry) {
            return new SetRouteForEntry(
                event: $event,
                entry: $element,
                routeParser: $this->routeParser,
                sitesService: $this->sitesService,
                setRouteFromParsedFactory: $this->setRouteFromParsedFactory,
            );
        }

        return new SetRouteNotImplemented();
    }
}
