<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs\RequestMatcher;

final class Properties implements \Nivseb\PhpMockServerConnector\Structs\RequestMatcher
{
    public function __construct(
        private string $method = '',
        private string $path = '',
        private array $pathParameters = [],
        private array $queryStringParameters = [],
        private array $headers = [],
        private array $cookies = [],
        private array|string $body = '',
    ) {}

    public function withMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function withPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /** @param array<string, array|scalar> $parameters */
    public function withPathParameters(array $parameters): self
    {
        $this->pathParameters = $parameters;
        return $this;
    }

    /** @param array<string, array|scalar> $parameters */
    public function withQueryStringParameters(array $parameters): self
    {
        $this->queryStringParameters = $parameters;
        return $this;
    }

    /** @param array<string, array|scalar> $headers */
    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /** @param array<string, scalar> $cookies */
    public function withCookies(array $cookies): self
    {
        $this->cookies = $cookies;
        return $this;
    }

    public function withBody(array|string $body): self
    {
        $this->body = $body;
        return $this;
    }

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
