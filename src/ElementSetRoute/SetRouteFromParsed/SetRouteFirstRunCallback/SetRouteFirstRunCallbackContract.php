<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback;

use Slim\App;

interface SetRouteFirstRunCallbackContract
{
    public function call(App $app): void;
}
