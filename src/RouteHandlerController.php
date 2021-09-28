<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge;

use craft\web\Controller;
use craft\web\Response;
use yii\base\InvalidConfigException;

// phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
// phpcs:disable PEAR.Functions.ValidDefaultValue.NotAtEnd
// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint

class RouteHandlerController extends Controller
{
    protected $allowAnonymous = true;

    /**
     * @psalm-suppress MissingParamType
     * @phpstan-ignore-next-line
     */
    public function __construct(
        $id,
        $module,
        $config,
        private SlimAppFactory $slimAppFactory,
        private RetrieveContainer $retrieveContainer,
        private ServerRequestFactory $serverRequestFactory,
        private RetrieveAppCreatedCallback $retrieveAppCreatedCallback,
    ) {
        /** @psalm-suppress MixedArgument */
        parent::__construct($id, $module, $config);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionIndex(): Response
    {
        $app = $this->slimAppFactory->make(
            container: $this->retrieveContainer->retrieve()
        );

        $this->retrieveAppCreatedCallback->retrieve()($app);

        $responseInterface = $app->handle(
            $this->serverRequestFactory->make()
        );

        $response = new Response();

        $response->statusCode = $responseInterface->getStatusCode();

        foreach ($responseInterface->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                /** @psalm-suppress MixedArgumentTypeCoercion */
                $response->headers->add($name, $value);
            }
        }

        $response->content = (string) $responseInterface->getBody();

        return $response;
    }
}
