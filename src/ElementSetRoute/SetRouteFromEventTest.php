<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute;

use craft\events\SetElementRouteEvent;
use PHPUnit\Framework\TestCase;

/** @psalm-suppress PropertyNotSetInConstructor */
class SetRouteFromEventTest extends TestCase
{
    public function testSet(): void
    {
        $setRouteSpy = $this->createMock(
            SetRouteContract::class,
        );

        $setRouteSpy->expects(self::once())
            ->method('set');

        $eventStub = $this->createMock(
            SetElementRouteEvent::class,
        );

        $setRouteFactorySpy = $this->createMock(
            SetRouteFactory::class,
        );

        $setRouteFactorySpy->expects(self::once())
            ->method('make')
            ->with(self::equalTo($eventStub))
            ->willReturn($setRouteSpy);

        $setRouteEvent = new SetRouteFromEvent(
            setRouteFactory: $setRouteFactorySpy,
        );

        $setRouteEvent->set(event: $eventStub);
    }
}
