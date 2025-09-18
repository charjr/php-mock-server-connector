<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs;

class Expectation
{
    public function __construct(
        public RequestMatcher $requestMatcher,
        public Action $action = new Action\Response(),
        public Times $times = new Times(),
        public ?int $priority = null,
        public ?string $id = null,
    ) {}

    /**
     * @return array{
     *     httpRequest: array{string, array|scalar},
     *     httpResponse: array{string, array|scalar},
     *     times: array{remainingTimes:int},
     *     id?: string,
     * }
     */
    public function createFormat(): array
    {
        return array_filter([
            'httpRequest'  => $this->requestMatcher->jsonSerialize(),
            'httpResponse' => $this->action->jsonSerialize(),
            'times' =>  $this->times->createFormat(),
            'id' => $this->id,
        ]);
    }

    /**
     * @return array{
     *     httpRequest: array{string, array|scalar},
     *     times: array{atLeast:int, atMost: int},
     *     id?: string,
     * }
     */
    public function verifyFormat(): array
    {
        return array_filter([
            'httpRequest'  => $this->requestMatcher->jsonSerialize(),
            'times' =>  $this->times->verifyFormat(),
            'id' => $this->id,
        ]);
    }
}
