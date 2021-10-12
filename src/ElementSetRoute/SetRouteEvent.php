<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute;

use craft\events\SetElementRouteEvent;

class SetRouteEvent
{
    public function __construct(
        private SetRouteFactory $setRouteFactory,
    ) {
    }

    public function set(SetElementRouteEvent $event): void
    {
        $this->setRouteFactory->make(event: $event)->set();
    }
}
