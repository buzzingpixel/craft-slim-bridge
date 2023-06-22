<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Stream;
use Yii;
use yii\base\Application;
use yii\base\InvalidConfigException;
use yii\base\Request;
use yii\base\Response;
use yii\web\Cookie;
use yii\web\CookieCollection;
use yii\web\Response as YiiResponse;

/** @psalm-suppress PropertyNotSetInConstructor */
class RouteHandlerControllerTest extends TestCase
{
    /**
     * @throws InvalidConfigException
     */
    public function testActionIndex(): void
    {
        $yiiAppStub = $this->createMock(
            Application::class,
        );

        /**
         * @psalm-suppress UndefinedClass
         * @phpstan-ignore-next-line
         */
        Yii::$app = $yiiAppStub;

        $yiiRequest = $this->createMock(Request::class);

        /**
         * @psalm-suppress UndefinedClass
         * @phpstan-ignore-next-line
         */
        Yii::$container->set('request', $yiiRequest);

        $yiiResponse = $this->createMock(Response::class);

        /**
         * @psalm-suppress UndefinedClass
         * @phpstan-ignore-next-line
         */
        Yii::$container->set('response', $yiiResponse);

        $appSpy = $this->createMock(App::class);

        $bodyStreamStub = $this->createMock(Stream::class);

        $bodyStreamStub->method('__toString')
            ->willReturn('test-body-string');

        $serverRequestStub = $this->createMock(
            ServerRequestInterface::class,
        );

        $responseInterfaceStub = $this->createMock(
            ResponseInterface::class,
        );

        $responseInterfaceStub->method('getStatusCode')
            ->willReturn(404);

        $responseInterfaceStub->method('getHeaders')
            ->willReturn([
                'test-header-1' => [
                    'test-val-1',
                    'test-val-2',
                ],
                'test-header-2' => [
                    'test-val-3',
                    'test-val-4',
                ],
            ]);

        $responseInterfaceStub->method('getBody')
            ->willReturn($bodyStreamStub);

        $appSpy->expects(self::once())
            ->method('handle')
            ->with(self::equalTo($serverRequestStub))
            ->willReturn($responseInterfaceStub);

        $slimAppFactorySpy = $this->createMock(
            SlimAppFactory::class,
        );

        $slimAppFactorySpy->expects(self::once())
            ->method('make')
            ->willReturn($appSpy);

        $serverRequestFactoryStub = $this->createMock(
            ServerRequestFactory::class,
        );

        $serverRequestFactoryStub->method('make')
            ->willReturn($serverRequestStub);

        $craftResponseStub = $this->createMock(
            \craft\web\Response::class,
        );

        $testCookie = new Cookie();

        $testCookie->name = 'test-cookie-foo';

        $testCookieCollection = new CookieCollection();

        $testCookieCollection->add($testCookie);

        $craftResponseStub->method('getCookies')
            ->willReturn($testCookieCollection);

        $craftAppStub = $this->createMock(
            \craft\web\Application::class,
        );

        $craftAppStub->method('getResponse')
            ->willReturn($craftResponseStub);

        $instance = new RouteHandlerController(
            id: 'test-id',
            /** @phpstan-ignore-next-line */
            module: null,
            config: [],
            slimAppFactory: $slimAppFactorySpy,
            serverRequestFactory: $serverRequestFactoryStub,
            craftApp: $craftAppStub,
        );

        $response = $instance->actionIndex();

        self::assertSame(404, $response->statusCode);

        $headers = $response->getHeaders();

        self::assertSame(2, $headers->count());

        $headersArray = $headers->toArray();

        self::assertSame(
            'test-val-1',
            $headersArray['test-header-1'][0],
        );

        self::assertSame(
            'test-val-2',
            $headersArray['test-header-1'][1],
        );

        self::assertSame(
            'test-val-3',
            $headersArray['test-header-2'][0],
        );

        self::assertSame(
            'test-val-4',
            $headersArray['test-header-2'][1],
        );

        self::assertSame(
            'test-body-string',
            $response->content,
        );

        self::assertSame(
            YiiResponse::FORMAT_RAW,
            $response->format,
        );

        $responseCookieArray = $response->getCookies()->toArray();

        self::assertCount(1, $responseCookieArray);

        self::assertSame(
            $testCookie,
            $responseCookieArray['test-cookie-foo'],
        );
    }
}
