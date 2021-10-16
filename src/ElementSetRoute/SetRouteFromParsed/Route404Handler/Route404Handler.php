<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\Route404Handler;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

class Route404Handler
{
    /**
     * @throws HttpNotFoundException
     */
    public function __invoke(ServerRequestInterface $request): void
    {
        throw new HttpNotFoundException($request);
    }
}
