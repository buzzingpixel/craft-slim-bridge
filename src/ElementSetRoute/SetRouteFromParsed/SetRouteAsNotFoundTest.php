<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\Route404Handler\Route404Handler;
use BuzzingPixel\SlimBridge\SlimAppFactory;
use craft\base\Element;
use craft\events\SetElementRouteEvent;
use PHPUnit\Framework\TestCase;
use Slim\App;
use yii\base\InvalidConfigException;

/** @psalm-suppress PropertyNotSetInConstructor */
class SetRouteAsNotFoundTest extends TestCase
{
    /**
     * @throws InvalidConfigException
     */
    public function testSet(): void
    {
        $elementStub = $this->createMock(Element::class);

        $elementStub->uri = 'test/uri';

        $eventStub = $this->createMock(
            SetElementRouteEvent::class,
        );

        $eventStub->route = null;

        $eventStub->sender = $elementStub;

        $appSpy = $this->createMock(App::class);

        $appSpy->expects(self::once())
            ->method('get')
            ->with(
                self::equalTo('/test/uri'),
                self::equalTo(Route404Handler::class),
            );

        $slimAppFactoryStub = $this->createMock(
            SlimAppFactory::class,
        );

        $slimAppFactoryStub->method('make')->willReturnCallback(
            static function (
                callable $firstRunCallback,
            ) use ($appSpy): App {
                $firstRunCallback($appSpy);

                return $appSpy;
            }
        );

        $setRouteAsNotFound = new SetRouteAsNotFound(
            event: $eventStub,
            slimAppFactory: $slimAppFactoryStub,
        );

        $setRouteAsNotFound->set();

        self::assertSame(
            ['slim-bridge/route-handler/index'],
            $eventStub->route,
        );
    }
}
