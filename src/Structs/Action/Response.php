<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs\Action;

use Nivseb\PhpMockServerConnector\Structs\Action;

final readonly class Response implements Action
{
    public function __construct(
        private int|string $statusCode = '',
        private string $reasonPhrase = '',
        private array $headers = [],
        private array $cookies = [],
        private array|string $body = '',
    ) {}

    /**
     * @return array{
     *     statusCode: int|string,
     *     reasonPhrase?: string,
     *     headers?: array,
     *     cookies?: array,
     *     body?: array|string,
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'statusCode' => $this->statusCode,
            'reasonPhrase' => $this->reasonPhrase,
            'headers' => $this->buildPropertyMatcher($this->headers),
            'cookies' => $this->buildPropertyMatcher($this->cookies),
            'body' => $this->body,
        ]);
    }

    /**
     * @param array<string, array|bool|float|int|string> $properties
     */
    private function buildPropertyMatcher(array $properties): array
    {
        return array_map(
            fn (string $name, array|bool|float|int|string $expectedValue): array => [
                'name'   => $name,
                'values' => [$expectedValue],
            ],
            array_keys($properties),
            $properties,
        );
    }
}
