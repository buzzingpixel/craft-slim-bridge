<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute;

class RouteParams
{
    /** @var mixed[] */
    private static array $params = [];

    public static function addParam(string $name, mixed $val): void
    {
        /** @psalm-suppress MixedAssignment */
        self::$params[$name] = $val;
    }

    /**
     * @return mixed[]
     */
    public function params(): array
    {
        return self::$params;
    }

    public function getParam(string $name): mixed
    {
        return self::$params[$name] ?? null;
    }
}
