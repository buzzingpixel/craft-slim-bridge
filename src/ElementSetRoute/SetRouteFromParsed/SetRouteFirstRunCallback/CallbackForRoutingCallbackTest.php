<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\SetRouteFromParsed\SetRouteFirstRunCallback;

use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParams;
use BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing\ParsedRoute;
use craft\base\Element;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Interfaces\RouteInterface;

/** @psalm-suppress PropertyNotSetInConstructor */
class CallbackForRoutingCallbackTest extends TestCase
{
    public function testCall(): void
    {
        $elementStub = $this->createMock(Element::class);

        $elementStub->uri = 'test/uri';

        $parsedRouteStub = new ParsedRoute(
            isMatch: true,
            routeString: RoutingCallbackContractImplementationForTesting::class,
        );

        $routeSpy = $this->createMock(RouteInterface::class);

        $routeSpy->expects(self::once())
            ->method('setArgument')
            ->with(
                self::equalTo('testName'),
                self::equalTo('testValue'),
            );

        $appSpy = $this->createMock(App::class);

        $appSpy->expects(self::once())
            ->method('get')
            ->with(
                self::equalTo('/test/uri'),
                self::equalTo($parsedRouteStub->routeString())
            )
            ->willReturn($routeSpy);

        $callback = new CallbackForRoutingCallback(
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
