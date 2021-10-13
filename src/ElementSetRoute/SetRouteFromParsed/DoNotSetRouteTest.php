<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed;

use PHPUnit\Framework\TestCase;

/** @psalm-suppress PropertyNotSetInConstructor */
class DoNotSetRouteTest extends TestCase
{
    public function testSet(): void
    {
        $doNotSetRoute = new DoNotSetRoute();

        $doNotSetRoute->set();

        self::assertTrue(true);
    }
}
