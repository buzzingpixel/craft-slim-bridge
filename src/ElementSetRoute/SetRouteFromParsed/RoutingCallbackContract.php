<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use Slim\Interfaces\RouteInterface;

interface RoutingCallbackContract
{
    public static function routingCallback(
        RouteInterface $route,
        ParsedRoute $parsedRoute,
    ): void;
}
