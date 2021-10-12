<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteEvent;
use Craft;
use craft\base\Element;
use craft\base\Plugin;
use craft\events\SetElementRouteEvent;
use craft\services\Categories;
use craft\services\Config;
use craft\services\Sites;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Yii;
use yii\base\Event;

use function assert;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SlimBridgePlugin extends Plugin
{
    public function init(): void
    {
        /**
         * @psalm-suppress UndefinedClass
         * @phpstan-ignore-next-line
         */
        Yii::$container->set(
            Config::class,
            /** @phpstan-ignore-next-line */
            Craft::$app->getConfig(),
        );

        /**
         * @psalm-suppress UndefinedClass
         * @phpstan-ignore-next-line
         */
        Yii::$container->set(
            ResponseFactoryInterface::class,
            ResponseFactory::class,
        );

        /**
         * @psalm-suppress UndefinedClass
         * @phpstan-ignore-next-line
         */
        Yii::$container->set(
            Categories::class,
            /** @phpstan-ignore-next-line */
            Craft::$app->getCategories(),
        );

        /**
         * @psalm-suppress UndefinedClass
         * @phpstan-ignore-next-line
         */
        Yii::$container->set(
            Sites::class,
            /** @phpstan-ignore-next-line */
            Craft::$app->getSites(),
        );

        Event::on(
            Element::class,
            Element::EVENT_SET_ROUTE,
            static function (SetElementRouteEvent $event): void {
                /**
                 * @psalm-suppress UndefinedClass
                 * @phpstan-ignore-next-line
                 */
                $elementSetRoute = Yii::$container->get(
                    SetRouteEvent::class,
                );

                assert($elementSetRoute instanceof SetRouteEvent);

                $elementSetRoute->set(event: $event);
            }
        );

        $this->controllerMap = [
            'route-handler' => RouteHandlerController::class,
        ];

        parent::init();
    }
}
