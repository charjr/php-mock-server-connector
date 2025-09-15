<?php

namespace Nivseb\PhpMockServerConnector\Structs;

final class MockServerExpectation implements \JsonSerializable
{
    /** @var RequestMatcher\Properties */
    public RequestMatcher $requestMatcher;
    public Action\Response $action;

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

    /**
     * @return array{
     *     times: array{atLeast:int, atMost:int},
     *     httpRequest: array{string, array|scalar},
     *     httpResponse: array{string, array|scalar},
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'times' => [
                'atLeast' => $this->atLeast,
                'atMost'  => $this->atMost,
            ],
            'httpRequest'  => $this->requestMatcher->jsonSerialize(),
            'httpResponse' => $this->action->jsonSerialize(),
        ];
    }
}
