<?php

namespace Nivseb\PhpMockServerConnector\Exception;

use Nivseb\PhpMockServerConnector\Structs\Expectation;
use Throwable;

class VerificationFailException extends AbstractMockServerException
{
    public function __construct(
        public Expectation $expectation,
        ?Throwable $previous = null
    ) {
        parent::__construct('Fail to check verification for expectation!', previous: $previous);
    }
}
