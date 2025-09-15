<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs\Action;

final class Response implements \Nivseb\PhpMockServerConnector\Structs\Action
{
    public function __construct(
        public int|string $statusCode = 200,
        public string $reasonPhrase = '',
        public array $headers = [],
        public array $cookies = [],
        public array|string $body = '',
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
     * @param array<string, array|scalar> $properties
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
