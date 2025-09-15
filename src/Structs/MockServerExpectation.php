<?php

namespace Nivseb\PhpMockServerConnector\Structs;

class MockServerExpectation
{
    public readonly RequestMatcher $requestMatcher;

    /**
     * @param array<string, array|bool|float|int|string> $pathParameters
     * @param array<string, array|bool|float|int|string> $queryParameters
     * @param array<string, array|bool|float|int|string> $requestHeaders
     */
    public function __construct(
        string $method,
        string $url,
        public int $responseStatusCode = 200,
        public null|array|string $responseBody = null,
        public ?array $responseHeaders = null,
        public int $atLeast = 1,
        public int $atMost = 1,
        ?array $pathParameters = null,
        ?array $queryParameters = null,
        ?array $requestHeaders = null,
        null|array|string $requestBody = null,
    ) {
        $this->requestMatcher = new RequestMatcher\Properties(
            $method,
            $url,
            $pathParameters ?? [],
            $queryParameters ?? [],
            $requestHeaders ?? [],
            [], //@TODO support cookies
            $requestBody ?? '',
        );
        );
    }
}
