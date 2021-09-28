<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Psr7\Factory\ResponseFactory;

use function DI\autowire;

$diCacheDir = dirname(__DIR__) . '/storage/di-cache';

$containerBuilder = (new ContainerBuilder())
    ->useAnnotations(true)
    ->useAutowiring(true)
    ->ignorePhpDocErrors(true)
    // You probably would require another file that returns an array of definitions
    ->addDefinitions([
        ResponseFactoryInterface::class => autowire(ResponseFactory::class),
    ]);

// Make sure to provide a cache dir to PHP-DI in production. Much better
// performance for prod
if ((bool) getenv('ENABLE_DI_COMPILATION')) {
    if (! is_dir($diCacheDir)) {
        mkdir($diCacheDir, 0777, true);
    }

    $containerBuilder->enableCompilation($diCacheDir);

    $containerBuilder->writeProxiesToFile(
        true,
        $diCacheDir
    );
}

$container = $containerBuilder->build();

// phpcs:disable SlevomatCodingStandard.Functions.StaticClosure.ClosureNotStatic

return [
    'containerInterface' => $container,
    'appCreatedCallback' => static function (App $app): void {
        $app->get('/', function () use (
            $app,
        ): ResponseInterface {
            /**
             * @psalm-suppress PossiblyNullReference
             * @phpstan-ignore-next-line
             */
            $responseFactory = $app->getContainer()->get(
                ResponseFactoryInterface::class
            );

            assert(
                $responseFactory instanceof ResponseFactoryInterface
            );

            $response = $responseFactory->createResponse();

            $response->getBody()->write('hello world');

            return $response;
        });

        $app->get('/test/route', function () use (
            $app,
        ): ResponseInterface {
            /**
             * @psalm-suppress PossiblyNullReference
             * @phpstan-ignore-next-line
             */
            $responseFactory = $app->getContainer()->get(
                ResponseFactoryInterface::class
            );

            assert(
                $responseFactory instanceof ResponseFactoryInterface
            );

            $response = $responseFactory->createResponse();

            $response->getBody()->write('test-thing');

            return $response;
        });
    },
];
