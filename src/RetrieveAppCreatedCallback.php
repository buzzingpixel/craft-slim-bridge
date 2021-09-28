<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use craft\services\Config;

use function is_callable;

class RetrieveAppCreatedCallback
{
    public function __construct(
        private Config $config
    ) {
    }

    public function retrieve(): callable
    {
        $configArray = $this->config->getConfigFromFile(
            'slim-bridge'
        );

        /** @psalm-suppress MixedAssignment */
        $appCreatedCallback = $configArray['appCreatedCallback'] ?? null;

        if (is_callable($appCreatedCallback)) {
            return $appCreatedCallback;
        }

        return static function (): void {
        };
    }
}
