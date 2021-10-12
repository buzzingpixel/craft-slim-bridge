<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

class DoNotSetRoute implements SetRouteContract
{
    public function set(): void
    {
    }
}
