<?php

namespace Nivseb\PhpMockServerConnector\Exception;

use Nivseb\PhpMockServerConnector\Structs\Expectation;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class UnsuccessfulVerificationException extends AbstractMockServerException
{
    public function __construct(
        string $message,
        public Expectation $expectation,
        public ResponseInterface $response,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, previous: $previous);
    }
}
