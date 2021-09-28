<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use craft\services\Config;
use Psr\Container\ContainerInterface;
use yii\base\InvalidConfigException;

class RetrieveContainer
{
    public function __construct(
        private Config $config
    ) {
    }

    /**
     * @throws InvalidConfigException
     */
    public function retrieve(): ContainerInterface
    {
        $configArray = $this->config->getConfigFromFile(
            'slim-bridge'
        );

        /** @psalm-suppress MixedAssignment */
        $container = $configArray['containerInterface'] ?? null;

        if ($container instanceof ContainerInterface) {
            return $container;
        }

        throw new InvalidConfigException(
            'The config file "slim-bridge.php" must have a config ' .
                'item with the key `containerInterface` that returns an ' .
                'implementation of ' . ContainerInterface::class,
        );
    }
}
