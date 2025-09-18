<?php

namespace Nivseb\PhpMockServerConnector\Exception;

use Nivseb\PhpMockServerConnector\Structs\Expectation;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class FailCreateExpectationException extends AbstractMockServerException
{
    public function __construct(
        public Expectation $expectation,
        public ?ResponseInterface $response = null,
        ?Throwable $previous = null
    ) {
        parent::__construct('Can´t create the expectation at mock server!', previous: $previous);
    }
}
