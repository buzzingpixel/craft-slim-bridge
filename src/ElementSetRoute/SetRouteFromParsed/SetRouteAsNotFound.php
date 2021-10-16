<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\Route404Handler\Route404Handler;
use BuzzingPixel\SlimBridge\SlimAppFactory;
use craft\base\Element;
use craft\events\SetElementRouteEvent;
use Slim\App;
use yii\base\InvalidConfigException;

use function assert;
use function ltrim;

class SetRouteAsNotFound implements SetRouteContract
{
    public function __construct(
        private SetElementRouteEvent $event,
        private SlimAppFactory $slimAppFactory,
    ) {
    }

    /**
     * @throws InvalidConfigException
     */
    public function set(): void
    {
        $this->slimAppFactory->make(
            firstRunCallback: function (App $app): void {
                $element = $this->event->sender;

                assert($element instanceof Element);

                $uri = '/' . ltrim(
                    (string) $element->uri,
                    '/',
                );

                $app->get($uri, Route404Handler::class);
            },
        );

        $this->event->route = ['slim-bridge/route-handler/index'];
    }
}
