<?php

namespace Nivseb\PhpMockServerConnector\Structs;

final class MockServerExpectation extends Expectation
{

    /** @var RequestMatcher\Properties */
    public RequestMatcher $requestMatcher;
    /** @var Action\Response */
    public Action $action;

    /**
     * @param array<string, array|scalar> $pathParameters
     * @param array<string, array|scalar> $queryParameters
     * @param array<string, array|scalar> $requestHeaders
     */
    public function __construct(
        string $method,
        string $url,
        int $responseStatusCode = 200,
        null|array|string $responseBody = null,
        ?array $responseHeaders = null,
        int $atLeast = 1,
        int $atMost = 1,
        ?array $pathParameters = null,
        ?array $queryParameters = null,
        ?array $requestHeaders = null,
        null|array|string $requestBody = null,
    ) {
        $requestMatcher = new RequestMatcher\Properties(
            $method,
            $url,
            $pathParameters ?? [],
            $queryParameters ?? [],
            $requestHeaders ?? [],
            [], //@TODO support cookies
            $requestBody ?? '',
        );

        $action = new Action\Response(
            $responseStatusCode,
            '', //@TODO support reasonPhrase
            $responseHeaders ?? [],
            [], //@TODO support cookies
            $responseBody ?? '',
        );

        parent::__construct(
            $requestMatcher,
            $action,
            $atLeast,
            $atMost,
        );
    }
}
