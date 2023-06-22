<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParams;
use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use craft\base\Element;
use Slim\App;

use function ltrim;

// phpcs:disable Squiz.Functions.MultiLineFunctionDeclaration.FirstParamSpacing

class CallbackForNoRoutingCallback implements SetRouteFirstRunCallbackContract
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
        $uri = '/' . ltrim(
            (string) $this->element->uri,
            '/',
        );

        $uri = $this->getFromMap($uri);

        RouteParams::addParam(name: 'element', val: $this->element);

        $app->get($uri, $this->parsedRoute->routeString());
    }
}
