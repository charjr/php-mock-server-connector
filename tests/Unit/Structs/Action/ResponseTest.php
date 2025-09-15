<?php

namespace Tests\Unit\Structs\RequestMatcher;

use Nivseb\PhpMockServerConnector\Structs\Action;

it(
    'is json serializable',
    function (array $input, array $expectation): void {
        expect((new Action\Response(...$input))->jsonSerialize())
            ->toBe($expectation);
    }
)
    ->with('serialise: response action');

dataset('serialise: response action', [
    'no data' => [[], ['statusCode' => 200]],
    ...array_map(fn ($d) => array_fill(0, 2, $d), [
        'statusCode: default'     => ['statusCode' => 'default'],
        'response: OK'     => ['statusCode' => 200, 'reasonPhrase' => 'OK'],
        'response: I\'m a Teapot'     => ['statusCode' => 418, 'reasonPhrase' => 'I\'m a Teapot'],
    ]),

    'headers: string' => [
        ['headers' => ['myString' => 'Hello, World!']],
        [
            'statusCode' => 200,
            'headers' => [['name' => 'myString', 'values' => ['Hello, World!']]],
        ],
    ],
    'headers: number' => [
        ['headers' => ['myNumber' => 123]],
        [
            'statusCode' => 200,
            'headers' => [['name'   => 'myNumber', 'values' => [123]]]],
    ],
    'headers: string and number' => [
        ['headers' => ['myString' => 'Hello, World!', 'myNumber' => 123]],
        [
            'statusCode' => 200,
            'headers' => [
                ['name' => 'myString', 'values' => ['Hello, World!']],
                ['name'   => 'myNumber', 'values' => [123]],
            ]
        ],
    ],
]);
