<?php

declare(strict_types=1);

namespace BuzzingPixel\SlimBridge\ElementSetRoute\RouteParsing;

class ParsedRoute
{
    public function __construct(
        private bool $isMatch,
        private string $routeString,
    ) {
    }

    public function isMatch(): bool
    {
        return $this->isMatch;
    }

    public function routeString(): string
    {
        return $this->routeString;
    }
}
