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
        $uri = '/' . ltrim(
            (string) $this->element->uri,
            '/',
        );

        RouteParams::addParam(name: 'element', val: $this->element);

        $app->get($uri, $this->parsedRoute->routeString());
    }
}
