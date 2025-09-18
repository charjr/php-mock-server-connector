<?php

namespace Nivseb\PhpMockServerConnector\Exception;

use Nivseb\PhpMockServerConnector\Structs\Expectation;

class AlreadyExpectedExpectationException extends AbstractMockServerException
{
    public function __construct(
        public Expectation $expectation,
    ) {
        parent::__construct('Expectation is already applied to the mock server!');
    }
}
