<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs;

class Expectation
{
    public function __construct(
        public RequestMatcher $requestMatcher,
        public Action $action,
        public int $atLeast = 1,
        public int $atMost = 1,
        public ?int $priority = null,
        public ?string $id = null,
    ) {}

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
