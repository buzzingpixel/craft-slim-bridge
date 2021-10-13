<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\RoutingCallbackContract;
use craft\base\Element;

use function class_implements;
use function in_array;
use function is_array;

class SetRouteFirstRunCallbackFactory
{
    /**
     * @phpstan-ignore-next-line
     */
    public function make(
        Element $element,
        ParsedRoute $parsedRoute,
    ): SetRouteFirstRunCallbackContract {
        $interfaces = class_implements(
            $parsedRoute->routeString()
        );

        if (
            ! is_array($interfaces) ||
            ! in_array(
                RoutingCallbackContract::class,
                $interfaces,
                true
            )
        ) {
            return new CallbackForNoRoutingCallback(
                element: $element,
                parsedRoute: $parsedRoute,
            );
        }

        return new CallbackForRoutingCallback(
            element: $element,
            parsedRoute: $parsedRoute,
        );
    }
}
