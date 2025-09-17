<?php

namespace Tests\Unit\Structs\RequestMatcher;

use Nivseb\PhpMockServerConnector\Structs\RequestMatcher;

it(
    'is json serializable',
    function (array $input, array $expectation): void {
        expect((new RequestMatcher\OpenApi(...$input))->jsonSerialize())
            ->toBe($expectation);
    }
)
    ->with('serialise: open api request matcher');

dataset('serialise: open api request matcher', [
    ...array_map(fn ($d) => array_fill(0, 2, $d), [
        'specUrlOrPayload: file' => [
            'specUrlOrPayload' => 'file:/api',
            'operationId' => 'listPets',
        ],
        'specUrlOrPayload: url'  => [
            'specUrlOrPayload' => 'http://petstore.swagger.io/v1/api',
            'operationId' => 'showPetById'
        ]
    ]),
]);
