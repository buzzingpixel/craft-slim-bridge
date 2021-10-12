<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\SlimAppFactory;
use craft\events\SetElementRouteEvent;

class SetRouteFromParsedFactory
{
    public function __construct(
        private SlimAppFactory $slimAppFactory,
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
            );
        }

        return new DoNotSetRoute();
    }
}
