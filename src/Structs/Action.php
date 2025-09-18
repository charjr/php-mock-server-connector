<?php

declare(strict_types=1);

namespace Nivseb\PhpMockServerConnector\Structs;

interface Action extends \JsonSerializable
{
    public function jsonSerialize(): array;
}
