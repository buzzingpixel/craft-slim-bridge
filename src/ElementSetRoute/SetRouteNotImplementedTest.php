<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute;

use PHPUnit\Framework\TestCase;

/** @psalm-suppress PropertyNotSetInConstructor */
class SetRouteNotImplementedTest extends TestCase
{
    public function testSet(): void
    {
        $set = new SetRouteNotImplemented();

        $set->set();

        self::assertTrue(true);
    }
}
