<?php

namespace Nivseb\PhpMockServerConnector\Expectation;

use Nivseb\PhpMockServerConnector\Structs\Expectation;

class RemoteExpectation
{
    public function __construct(
        public string $uuid,
        public Expectation $expectation
    ) {}
}
