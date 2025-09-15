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
    'no data' => [[], []],
    ...array_map(fn ($d) => array_fill(0, 2, $d), [
        'statusCode: default'     => ['statusCode' => 'default'],
        'statusCode: 200'     => ['statusCode' => 200],
        'statusCode: 418'     => ['statusCode' => 418],
    ]),

    ...array_map(fn ($d) => array_fill(0, 2, $d), [
        'reasonPhrase: OK'     => ['reasonPhrase' => 'OK'],
        'reasonPhrase: I\'m a Teapot'     => ['reasonPhrase' => 'I\'m a Teapot'],
    ]),

    'headers: string' => [
        ['headers' => ['myString' => 'Hello, World!']],
        ['headers' => [
            ['name' => 'myString', 'values' => ['Hello, World!']],
        ]],
    ],
    'headers: number' => [
        ['headers' => ['myNumber' => 123]],
        ['headers' => [
            ['name'   => 'myNumber', 'values' => [123]],
        ]],
    ],
    'headers: string and number' => [
        ['headers' => ['myString' => 'Hello, World!', 'myNumber' => 123]],
        ['headers' => [
            ['name' => 'myString', 'values' => ['Hello, World!']],
            ['name'   => 'myNumber', 'values' => [123]],
        ]],
    ],
]);
