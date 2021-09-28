<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Stream;
use stdClass;
use Yii;
use yii\base\Application;
use yii\base\InvalidConfigException;
use yii\base\Request;
use yii\base\Response;

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

        $callbackStorage             = new stdClass();
        $callbackStorage->hasBeenRun = false;

        $callBackSpy = static function ($app) use (
            $appSpy,
            $callbackStorage,
        ): void {
            $callbackStorage->hasBeenRun = true;

            self::assertSame($appSpy, $app);
        };

        $containerStub = $this->createMock(
            ContainerInterface::class
        );

        $slimAppFactorySpy = $this->createMock(
            SlimAppFactory::class,
        );

        $slimAppFactorySpy->expects(self::once())
            ->method('make')
            ->with(self::equalTo($containerStub))
            ->willReturn($appSpy);

        $retrieveContainerStub = $this->createMock(
            RetrieveContainer::class,
        );

        $retrieveContainerStub->method('retrieve')
            ->willReturn($containerStub);

        $serverRequestFactoryStub = $this->createMock(
            ServerRequestFactory::class,
        );

        $serverRequestFactoryStub->method('make')
            ->willReturn($serverRequestStub);

        $retrieveAppCreatedCallbackStub = $this->createMock(
            RetrieveAppCreatedCallback::class,
        );

        $retrieveAppCreatedCallbackStub->method('retrieve')
            ->willReturn($callBackSpy);

        $instance = new RouteHandlerController(
            id: 'test-id',
            /** @phpstan-ignore-next-line */
            module: null,
            config: [],
            slimAppFactory: $slimAppFactorySpy,
            retrieveContainer: $retrieveContainerStub,
            serverRequestFactory: $serverRequestFactoryStub,
            retrieveAppCreatedCallback: $retrieveAppCreatedCallbackStub,
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

        self::assertTrue($callbackStorage->hasBeenRun);
    }
}
