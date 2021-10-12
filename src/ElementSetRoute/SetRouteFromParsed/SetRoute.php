<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParams;
use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\SlimAppFactory;
use craft\base\Element;
use craft\events\SetElementRouteEvent;
use Slim\App;
use yii\base\InvalidConfigException;

use function assert;
use function class_exists;
use function class_implements;
use function in_array;
use function is_a;
use function is_array;
use function ltrim;

class SetRoute implements SetRouteContract
{
    public function __construct(
        private ParsedRoute $parsedRoute,
        private SetElementRouteEvent $event,
        private SlimAppFactory $slimAppFactory,
    ) {
    }

    /**
     * @throws InvalidConfigException
     */
    public function set(): void
    {
        $element = $this->event->sender;

        assert($element instanceof Element);

        $uri = '/' . ltrim((string) $element->uri, '/');

        $this->slimAppFactory->make(firstRunCallback: function (
            App $app,
        ) use (
            $uri,
            $element
        ): void {
            RouteParams::addParam(name: 'element', val: $element);

            $routeString = $this->parsedRoute->routeString();

            $route = $app->get($uri, $routeString);

            $interfaces = class_implements($routeString);

            if (
                ! is_array($interfaces) ||
                ! in_array(
                    RoutingCallbackContract::class,
                    $interfaces,
                    true
                )
            ) {
                return;
            }

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
        });

        $this->event->route = ['slim-bridge/route-handler/index'];
    }
}
