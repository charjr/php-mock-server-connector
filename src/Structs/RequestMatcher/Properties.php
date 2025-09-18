<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs\RequestMatcher;

final class Properties implements \Nivseb\PhpMockServerConnector\Structs\RequestMatcher
{
    public function __construct(
        public string $method = '',
        public string $path = '',
        public array $pathParameters = [],
        public array $queryStringParameters = [],
        public array $headers = [],
        public array $cookies = [],
        public array|string $body = '',
    ) {}

    /**
     * @return array{
     *     method?: string,
     *     path?: string,
     *     pathParameters?: array<array{name:string, value:array|scalar}>,
     *     queryStringParameters?: array<array{name:string, value:array|scalar}>,
     *     headers?: array<array{name:string, value:array|scalar}>,
     *     cookies?: array<array{name:string, value:array|scalar}>,
     *     body?: array|string,
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'method' => $this->method,
            'path' => $this->path,
            'pathParameters' => $this->buildPropertyMatcher($this->pathParameters),
            'queryStringParameters' => $this->buildPropertyMatcher($this->queryStringParameters),
            'headers' => $this->buildPropertyMatcher($this->headers),
            'cookies' => $this->buildPropertyMatcher($this->cookies),
            'body' => $this->body,
        ]);
    }

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
