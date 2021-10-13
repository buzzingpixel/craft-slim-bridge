<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParams;
use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use craft\base\Element;
use PHPUnit\Framework\TestCase;
use Slim\App;

/** @psalm-suppress PropertyNotSetInConstructor */
class CallbackForNoRoutingCallbackTest extends TestCase
{
    public function testCall(): void
    {
        $elementStub = $this->createMock(Element::class);

        $elementStub->uri = 'test/uri';

        $parsedRouteStub = new ParsedRoute(
            isMatch: true,
            routeString: 'testRouteString',
        );

        $appSpy = $this->createMock(App::class);

        $appSpy->expects(self::once())
            ->method('get')
            ->with(
                self::equalTo('/test/uri'),
                self::equalTo('testRouteString')
            );

        $callback = new CallbackForNoRoutingCallback(
            element: $elementStub,
            parsedRoute: $parsedRouteStub,
        );

        $callback->call($appSpy);

        $routeParams = new RouteParams();

        self::assertSame(
            $elementStub,
            $routeParams->getParam(name: 'element'),
        );
    }
}
