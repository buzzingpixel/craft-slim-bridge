<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use function dd;

class DoNotSetRoute implements SetRouteContract
{
    public function set(): void
    {
        // TODO: Implement set() method.
        dd('DoNotSetRoute');
    }
}
