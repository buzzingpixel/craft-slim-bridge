<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback\SetRouteFirstRunCallbackFactory;
use BuzzingPixel\SlimBridge\SlimAppFactory;
use craft\base\Element;
use craft\events\SetElementRouteEvent;
use yii\base\InvalidConfigException;

use function assert;

class SetRoute implements SetRouteContract
{
    public function __construct(
        private ParsedRoute $parsedRoute,
        private SetElementRouteEvent $event,
        private SlimAppFactory $slimAppFactory,
        private SetRouteFirstRunCallbackFactory $setRouteFirstRunCallbackFactory,
    ) {
    }

    /**
     * @throws InvalidConfigException
     */
    public function set(): void
    {
        $element = $this->event->sender;

        assert($element instanceof Element);

        $callback = $this->setRouteFirstRunCallbackFactory->make(
            element: $element,
            parsedRoute: $this->parsedRoute,
        );

        $this->slimAppFactory->make(firstRunCallback: [
            $callback,
            'call',
        ]);

        $this->event->route = ['slim-bridge/route-handler/index'];
    }
}
