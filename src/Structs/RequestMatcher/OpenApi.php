<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs\RequestMatcher;

use Nivseb\PhpMockServerConnector\Structs\RequestMatcher;

final readonly class OpenApi implements RequestMatcher
{
    public function __construct(
        public string $specUrlOrPayload,
        public string $operationId,
    ) {}


    /**
     * @return array{
     *     specUrlOrPayload: string,
     *     operationId?: string,
     * }
     */
    public function jsonSerialize(): mixed
    {
        return [
                'specUrlOrPayload' => $this->specUrlOrPayload,
                'operationId' => $this->operationId,
        ];
    }
}
