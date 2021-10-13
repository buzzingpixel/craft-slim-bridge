<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParams;
use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\RoutingCallbackContract;
use craft\base\Element;
use Slim\App;

use function assert;
use function class_exists;
use function is_a;
use function ltrim;

// phpcs:disable Squiz.Functions.MultiLineFunctionDeclaration.FirstParamSpacing

class CallbackForRoutingCallback implements SetRouteFirstRunCallbackContract
{
    /**
     * @phpstan-ignore-next-line
     */
    public function __construct(
        /** @phpstan-ignore-next-line */
        private Element $element,
        private ParsedRoute $parsedRoute,
    ) {
    }

    public function call(App $app): void
    {
        $routeString = $this->parsedRoute->routeString();

        $uri = '/' . ltrim(
            (string) $this->element->uri,
            '/',
        );

        RouteParams::addParam(name: 'element', val: $this->element);

        $route = $app->get($uri, $routeString);

        assert(class_exists($routeString));

        assert(is_a(
            $routeString,
            RoutingCallbackContract::class,
            true
        ));

        $routeString::routingCallback(
            route: $route,
            parsedRoute: $this->parsedRoute,
        );
    }
}
