<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback\SetRouteFirstRunCallbackFactory;
use BuzzingPixel\SlimBridge\SlimAppFactory;
use craft\events\SetElementRouteEvent;

class SetRouteFromParsedFactory
{
    public function __construct(
        private SlimAppFactory $slimAppFactory,
        private SetRouteFirstRunCallbackFactory $setRouteFirstRunCallbackFactory,
    ) {
    }

    public function make(
        ParsedRoute $parsedRoute,
        SetElementRouteEvent $event
    ): SetRouteContract {
        if ($parsedRoute->isMatch()) {
            return new SetRoute(
                event: $event,
                parsedRoute: $parsedRoute,
                slimAppFactory: $this->slimAppFactory,
                setRouteFirstRunCallbackFactory: $this->setRouteFirstRunCallbackFactory,
            );
        }

        return new DoNotSetRoute();
    }
}
