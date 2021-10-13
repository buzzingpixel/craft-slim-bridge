<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\RoutingCallbackContract;
use Slim\Interfaces\RouteInterface;

class RoutingCallbackContractImplementationForTesting implements RoutingCallbackContract
{
    public static function routingCallback(RouteInterface $route, ParsedRoute $parsedRoute): void
    {
        $route->setArgument(name: 'testName', value: 'testValue');
    }
}
