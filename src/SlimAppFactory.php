<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use yii\base\InvalidConfigException;

class SlimAppFactory
{
    private static ?App $app = null;

    public function __construct(
        private RetrieveContainer $retrieveContainer,
        private ResponseFactoryInterface $responseFactory,
        private RetrieveAppCreatedCallback $retrieveAppCreatedCallback,
    ) {
    }

    /**
     * @throws InvalidConfigException
     */
    public function make(?callable $firstRunCallback = null): App
    {
        if (self::$app !== null) {
            return self::$app;
        }

        self::$app = AppFactory::create(
            $this->responseFactory,
            $this->retrieveContainer->retrieve(),
        );

        if ($firstRunCallback !== null) {
            $firstRunCallback(self::$app);
        }

        $this->retrieveAppCreatedCallback->retrieve()(self::$app);

        return self::$app;
    }
}
