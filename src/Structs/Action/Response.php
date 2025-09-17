<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs\Action;

final class Response implements \Nivseb\PhpMockServerConnector\Structs\Action
{
    public function __construct(
        private int|string $statusCode = 200,
        private string $reasonPhrase = '',
        private array $headers = [],
        private array $cookies = [],
        private array|string $body = '',
    ) {}

    public function withStatusCode(int|string $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function withReasonPhrase(string $phrase): self
    {
        $this->reasonPhrase = $phrase;
        return $this;
    }

    /** @param array<string, scalar> $headers */
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
