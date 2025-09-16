<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs\RequestMatcher;

use Nivseb\PhpMockServerConnector\Structs\RequestMatcher;

final readonly class OpenApi implements RequestMatcher
{
    public function __construct(
        public readonly string $specUrlOrPayload,
        public readonly ?string $operationId = null,
    ) {}

    /**
     * @return array{
     *     specUrlOrPayload: string,
     *     operationId?: string,
     * }
     */
    public function jsonSerialize(): mixed
    {
        return array_filter(
            [
                'specUrlOrPayload' => $this->specUrlOrPayload,
                'operationId' => $this->operationId,
            ],
            fn ($v) => $v !== null,
        );
    }

}
