<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback\SetRouteFirstRunCallbackContract;
use BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback\SetRouteFirstRunCallbackFactory;
use BuzzingPixel\SlimBridge\SlimAppFactory;
use craft\base\Element;
use craft\events\SetElementRouteEvent;
use PHPUnit\Framework\TestCase;
use yii\base\InvalidConfigException;

/** @psalm-suppress PropertyNotSetInConstructor */
class SetRouteTest extends TestCase
{
    /**
     * @throws InvalidConfigException
     */
    public function testSet(): void
    {
        $parsedRouteStub = new ParsedRoute(
            isMatch: true,
            routeString: 'testRouteString',
        );

        $elementStub = $this->createMock(Element::class);

        $eventStub = new SetElementRouteEvent();

        $eventStub->sender = $elementStub;

        self::assertNull($eventStub->route);

        $callBackStub = $this->createMock(
            SetRouteFirstRunCallbackContract::class,
        );

        $setRouteFirstRunCallbackFactorySpy = $this->createMock(
            SetRouteFirstRunCallbackFactory::class,
        );

        $setRouteFirstRunCallbackFactorySpy
            ->expects(self::once())
            ->method('make')
            ->with(
                self::equalTo($elementStub),
                self::equalTo($parsedRouteStub),
            )
            ->willReturn($callBackStub);

        $slimAppFactorySpy = $this->createMock(
            SlimAppFactory::class,
        );

        $slimAppFactorySpy->expects(self::once())
            ->method('make')
            ->with(self::equalTo([
                $callBackStub,
                'call',
            ]));

        $setRoute = new SetRoute(
            parsedRoute: $parsedRouteStub,
            event: $eventStub,
            slimAppFactory: $slimAppFactorySpy,
            setRouteFirstRunCallbackFactory: $setRouteFirstRunCallbackFactorySpy,
        );

        $setRoute->set();

        self::assertSame(
            ['slim-bridge/route-handler/index'],
            $eventStub->route,
        );
    }
}
