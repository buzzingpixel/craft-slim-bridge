<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback\SetRouteFirstRunCallbackFactory;
use BuzzingPixel\SlimBridge\SlimAppFactory;
use craft\base\Element;
use craft\events\SetElementRouteEvent;

use function assert;
use function mb_strpos;

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
            $element = $event->sender;

            assert($element instanceof Element);

            $uri = (string) $element->uri;

            if ($uri !== '__home__' && mb_strpos($uri, '_') === 0) {
                return new SetRouteAsNotFound(
                    event: $event,
                    slimAppFactory: $this->slimAppFactory,
                );
            }

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
