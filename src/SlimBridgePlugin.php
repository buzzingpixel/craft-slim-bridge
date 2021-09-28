<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use Craft;
use craft\base\Plugin;
use craft\services\Config;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Yii;

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

        $this->controllerMap = [
            'route-handler' => RouteHandlerController::class,
        ];

        parent::init();
    }
}
