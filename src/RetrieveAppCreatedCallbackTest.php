<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use craft\services\Config;
use PHPUnit\Framework\TestCase;

/** @psalm-suppress PropertyNotSetInConstructor */
class RetrieveAppCreatedCallbackTest extends TestCase
{
    public function testRetrieveWhenNoCallable(): void
    {
        $configSpy = $this->createMock(
            Config::class,
        );

        $configSpy->expects(self::once())
            ->method('getConfigFromFile')
            ->with(self::equalTo('slim-bridge'))
            ->willReturn(['appCreatedCallback' => 'asdf']);

        $instance = new RetrieveAppCreatedCallback(config: $configSpy);

        self::assertIsCallable($instance->retrieve());
    }

    public function testRetrieve(): void
    {
        // @codeCoverageIgnoreStart
        $callableStub = static function (): void {
        };
        // @codeCoverageIgnoreEnd

        $configSpy = $this->createMock(
            Config::class,
        );

        $configSpy->expects(self::once())
            ->method('getConfigFromFile')
            ->with(self::equalTo('slim-bridge'))
            ->willReturn(['appCreatedCallback' => $callableStub]);

        $instance = new RetrieveAppCreatedCallback(config: $configSpy);

        self::assertSame(
            $callableStub,
            $instance->retrieve(),
        );
    }
}
