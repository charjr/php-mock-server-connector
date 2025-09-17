<?php

namespace Nivseb\PhpMockServerConnector\Structs;

class MockServerExpectation
{
    public RequestMatcher\Properties $requestMatcher;
    public Action\Response $action;

    /**
     * @param array<string, array|bool|float|int|string> $pathParameters
     * @param array<string, array|bool|float|int|string> $queryParameters
     * @param array<string, array|bool|float|int|string> $requestHeaders
     */
    public function __construct(
        string $method,
        string $url,
        int $responseStatusCode = 200,
        null|array|string $responseBody = null,
        ?array $responseHeaders = null,
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

        $this->action = new Action\Response(
            $responseStatusCode,
            '', //@TODO support reasonPhrase
            $responseHeaders ?? [],
            [], //@TODO support cookies
            $responseBody ?? '',
        );
    }

    public function jsonSerialize(): mixed
    {
        return [
            'times' => [
                'remainingTimes' => $this->atMost,
            ],
            'httpRequest' => $this->requestMatcher->jsonSerialize(),
            'httpResponse' => $this->action->jsonSerialize(),
        ];
    }

}
