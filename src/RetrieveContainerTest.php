<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use craft\services\Config;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Throwable;
use yii\base\InvalidConfigException;

/** @psalm-suppress PropertyNotSetInConstructor */
class RetrieveContainerTest extends TestCase
{
    public function testRetrieveWhenThrows(): void
    {
        $configSpy = $this->createMock(
            Config::class,
        );

        $configSpy->expects(self::once())
            ->method('getConfigFromFile')
            ->with(self::equalTo('slim-bridge'))
            ->willReturn(['containerInterface' => 'asdf']);

        $instance = new RetrieveContainer(config: $configSpy);

        $exception = null;

        try {
            $instance->retrieve();
        } catch (Throwable $e) {
            $exception = $e;
        }

        self::assertInstanceOf(
            InvalidConfigException::class,
            $exception
        );

        self::assertSame(
            'The config file "slim-bridge.php" must have a config ' .
                'item with the key `containerInterface` that returns an ' .
                'implementation of ' . ContainerInterface::class,
            $exception->getMessage(),
        );
    }

    /**
     * @throws InvalidConfigException
     */
    public function testRetrieve(): void
    {
        $containerStub = $this->createMock(
            ContainerInterface::class,
        );

        $configSpy = $this->createMock(
            Config::class,
        );

        $configSpy->expects(self::once())
            ->method('getConfigFromFile')
            ->with(self::equalTo('slim-bridge'))
            ->willReturn(['containerInterface' => $containerStub]);

        $instance = new RetrieveContainer(config: $configSpy);

        self::assertSame(
            $instance->retrieve(),
            $containerStub
        );
    }
}
