<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs;

final class Times
{
    public function __construct(
        public int $atLeast = 1,
        public int $atMost = 1,
    ) {}

    /** @return array{atLeast: int, atMost: int} */
    public function verifyFormat(): array
    {
        return [
            'atLeast' => $this->atLeast,
            'atMost' => $this->atMost,
        ];
    }

    /** @return array{remainingTimes: int} */
    public function createFormat(): mixed
    {
        return ['remainingTimes' => $this->atMost];
    }

}
