<?php

namespace Tests\Unit\Structs\RequestMatcher;

use Nivseb\PhpMockServerConnector\Structs\RequestMatcher;

it(
    'is json serializable',
    function (array $input, array $expectation): void {
        expect((new RequestMatcher\Properties(...$input))->jsonSerialize())
            ->toBe($expectation);
    }
)
    ->with('serialise: request properties matcher');

dataset('serialise: request properties matcher', [
    'no data' => [[], []],
    ...array_map(fn ($d) => array_fill(0, 2, $d), [
        'method: GET'     => ['method' => 'GET'],
        'method: DELETE'  => ['method' => 'DELETE'],
        'method: HEAD'    => ['method' => 'HEAD'],
        'method: PATCH'   => ['method' => 'PATCH'],
        'method: POST'    => ['method' => 'POST'],
        'method: PUT'     => ['method' => 'PUT'],
        'method: OPTIONS' => ['method' => 'OPTIONS'],
        'method: TRACE'   => ['method' => 'TRACE'],
    ]),

    ...array_map(fn ($d) => array_fill(0, 2, $d), [
        'path: root'                 => ['path' => '/'],
        'path: directory'            => ['path' => '/directory'],
        'path: subdirectory'         => ['path' => '/directory/subdirectory'],
        'path: file'                 => ['path' => '/test.txt'],
        'path: file in subdirectory' => ['path' => '/directory/subdirectory/test.txt'],
        'path: with path parameter'  => ['path' => '/directory/{placeHolder}/'],
    ]),

    'pathParameters: string' => [
        ['pathParameters' => ['myString' => 'Hello, World!']],
        ['pathParameters' => [
            ['name' => 'myString', 'values' => ['Hello, World!']],
        ]],
    ],
    'pathParameters: number' => [
        ['pathParameters' => ['myNumber' => 123]],
        ['pathParameters' => [
            ['name'   => 'myNumber', 'values' => [123]],
        ]],
    ],
    'pathParameters: string and number' => [
        ['pathParameters' => ['myString' => 'Hello, World!', 'myNumber' => 123]],
        ['pathParameters' => [
            ['name' => 'myString', 'values' => ['Hello, World!']],
            ['name'   => 'myNumber', 'values' => [123]],
        ]],
    ],

    'queryStringParameters: string' => [
        ['queryStringParameters' => ['myString' => 'Hello, World!']],
        ['queryStringParameters' => [
            ['name' => 'myString', 'values' => ['Hello, World!']],
        ]],
    ],
    'queryStringParameters: number' => [
        ['queryStringParameters' => ['myNumber' => 123]],
        ['queryStringParameters' => [
            ['name'   => 'myNumber', 'values' => [123]],
        ]],
    ],
    'queryStringParameters: string and number' => [
        ['queryStringParameters' => ['myString' => 'Hello, World!', 'myNumber' => 123]],
        ['queryStringParameters' => [
            ['name' => 'myString', 'values' => ['Hello, World!']],
            ['name'   => 'myNumber', 'values' => [123]],
        ]],
    ],

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
