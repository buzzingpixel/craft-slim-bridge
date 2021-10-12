<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use yii\base\InvalidConfigException;

/** @psalm-suppress PropertyNotSetInConstructor */
class SlimAppFactoryTest extends TestCase
{
    private int $firstRunCallbackRuns = 0;

    private ?App $firstRunCallbackApp = null;

    private int $appCreatedCallbackRuns = 0;

    private ?App $appCreatedCallbackApp = null;

    protected function setUp(): void
    {
        $this->firstRunCallbackRuns = 0;

        $this->firstRunCallbackApp = null;
    }

    /**
     * @throws InvalidConfigException
     */
    public function testMake(): void
    {
        $containerStub = $this->createMock(
            ContainerInterface::class,
        );

        $retrieveContainerStub = $this->createMock(
            RetrieveContainer::class,
        );

        $retrieveContainerStub->method('retrieve')
            ->willReturn($containerStub);

        $responseFactoryStub = $this->createMock(
            ResponseFactoryInterface::class,
        );

        $retrieveAppCreatedCallback = $this->createMock(
            RetrieveAppCreatedCallback::class,
        );

        $retrieveAppCreatedCallback->method('retrieve')
            ->willReturn(function (App $app): void {
                $this->appCreatedCallbackRuns += 1;

                $this->appCreatedCallbackApp = $app;
            });

        $instance = new SlimAppFactory(
            responseFactory: $responseFactoryStub,
            retrieveContainer: $retrieveContainerStub,
            retrieveAppCreatedCallback: $retrieveAppCreatedCallback,
        );

        $firstRunCallback = function (App $app): void {
            $this->firstRunCallbackRuns += 1;

            $this->firstRunCallbackApp = $app;
        };

        $app1 = $instance->make($firstRunCallback);

        $app = $instance->make($firstRunCallback);

        self::assertSame($app1, $app);

        self::assertSame(
            $responseFactoryStub,
            $app->getResponseFactory(),
        );

        self::assertSame(
            $containerStub,
            $app->getContainer(),
        );

        self::assertSame(1, $this->firstRunCallbackRuns);

        self::assertSame(
            $app,
            $this->firstRunCallbackApp,
        );

        self::assertSame(1, $this->appCreatedCallbackRuns);

        self::assertSame(
            $app,
            $this->appCreatedCallbackApp,
        );

        self::assertSame(
            $containerStub,
            $app->getContainer(),
        );
    }
}
