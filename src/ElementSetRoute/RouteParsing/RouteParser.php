<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing;

use function explode;

class RouteParser
{
    public function parseRouteString(string $fullRouteString): ParsedRoute
    {
        $routeParts = explode('/', $fullRouteString);

        $specifier = $routeParts[0];

        $routeString = ($routeParts[1] ?? '');

        return new ParsedRoute(
            isMatch: $specifier === '_slimBridge',
            routeString: $routeString,
        );
    }
}
