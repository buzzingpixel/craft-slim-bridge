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
    private const SPECIAL_URI_MAP = ['/__home__' => '/'];

    public function __construct(
        private Element $element,
        private ParsedRoute $parsedRoute,
    ) {
    }

    private function getFromMap(string $uri): string
    {
        return self::SPECIAL_URI_MAP[$uri] ?? $uri;
    }

    public function call(App $app): void
    {
        $routeString = $this->parsedRoute->routeString();

        $uri = '/' . ltrim(
            (string) $this->element->uri,
            '/',
        );

        $uri = $this->getFromMap($uri);

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
