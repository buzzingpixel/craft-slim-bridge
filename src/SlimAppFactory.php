<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;

class SlimAppFactory
{
    public function __construct(
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function make(ContainerInterface $container): App
    {
        return AppFactory::create(
            responseFactory: $this->responseFactory,
            container: $container
        );
    }
}
