<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs;

final readonly class Expectation
{
    public function __construct(
        public RequestMatcher $requestMatcher,
        public Action $action,
        public int $atLeast = 1,
        public ?int $atMost = 1,
        public ?int $priority = null,
        public ?string $id = null,
    ) {}

    public function jsonSerialize(): mixed
    {
        return array_filter([
            'times' => array_filter([
                'atLeast' => $this->atLeast,
                'atMost'  => $this->atMost,
            ]),
            'httpRequest'  => $this->requestMatcher->jsonSerialize(),
            'httpResponse' => $this->action->jsonSerialize(),
        ]);
    }
}
