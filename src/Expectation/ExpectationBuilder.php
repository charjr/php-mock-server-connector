<?php

namespace Nivseb\PhpMockServerConnector\Expectation;

use Nivseb\PhpMockServerConnector\Structs\MockServerExpectation;

class ExpectationBuilder
{
    public static function buildMockServerExpectation(MockServerExpectation $expectation): array
    {
        return [
            'times' => [
                'remainingTimes' => $expectation->atMost,
            ],
            'httpRequest'  => $expectation->requestMatcher->jsonSerialize(),
            'httpResponse' => $expectation->action->jsonSerialize(),
        ];
    }
}
