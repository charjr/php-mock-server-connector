<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\MockInterface;
use Nivseb\PhpMockServerConnector\Exception\FailResetMockServerException;
use Nivseb\PhpMockServerConnector\Exception\UnsuccessfulVerificationException;
use Nivseb\PhpMockServerConnector\Exception\VerificationFailException;
use Nivseb\PhpMockServerConnector\Server\Connector;
use Nivseb\PhpMockServerConnector\Structs\Expectation;
use Nivseb\PhpMockServerConnector\Structs\RequestMatcher;
use Nivseb\PhpMockServerConnector\Structs\Action;
use Nivseb\PhpMockServerConnector\Structs\MockServerExpectation;

use Nivseb\PhpMockServerConnector\Structs\Times;

use function Pest\Faker\fake;

it(
    'resets mock server correctly',
    /**
     * @throws FailResetMockServerException
     */
    function (): void {
        /** @var Client&MockInterface $clientMock */
        $clientMock    = Mockery::mock(Client::class);
        $testConnector = new Connector($clientMock);

        $clientMock->allows('put')
            ->once()
            ->withArgs(['/mockserver/reset'])
            ->andReturn(new Response());

        $testConnector->reset();
    }
);

it(
    'throws exception for non 200 response on reset',
    /**
     * @throws FailResetMockServerException
     */
    function (int $statusCode): void {
        /** @var Client&MockInterface $clientMock */
        $clientMock    = Mockery::mock(Client::class);
        $testConnector = new Connector($clientMock);

        $response      = new Response($statusCode);

        $clientMock->allows('put')
            ->once()
            ->withArgs(['/mockserver/reset'])
            ->andReturn($response);

        expect(fn () => $testConnector->reset())
            ->toThrow(
                function (FailResetMockServerException $exception) use ($response): void {
                    expect($exception->getMessage())
                        ->toBe('Failing to reset mock server expectations!')
                        ->and($exception->response)->toBe($response)
                        ->and($exception->getPrevious())->toBeNull();
                }
            );
    }
)->with([
    'created'      => [201],
    'not modified' => [304],
    'not found'    => [404],
    'server fail'  => [500],
]);

it(
    'wraps guzzle client exception on failed reset',
    /**
     * @throws FailResetMockServerException
     */
    function (): void {
        /** @var Client&MockInterface $clientMock */
        $clientMock      = Mockery::mock(Client::class);
        $testConnector   = new Connector($clientMock);

        $guzzleException = new TransferException();

        $clientMock->allows('put')
            ->once()
            ->withArgs(['/mockserver/reset'])
            ->andThrow($guzzleException);


        expect(fn () => $testConnector->reset())
            ->toThrow(
                function (FailResetMockServerException $exception) use ($guzzleException): void {
                    expect($exception->getMessage())->toBe('Failing to reset mock server expectations!')
                        ->and($exception->response)->toBeNull()
                        ->and($exception->getPrevious())->toBe($guzzleException);
                }
            );
    }
);

it(
    'verifies remote expectations correctly',
    /**
     * @throws UnsuccessfulVerificationException
     * @throws VerificationFailException
     */
    function (): void {
        /** @var Client&MockInterface $clientMock */
        $clientMock        = Mockery::mock(Client::class);
        $testConnector     = new Connector($clientMock);

        $expectation       = new Expectation(
            new RequestMatcher\Properties('GET', '/path'),
            new Action\Response(),
            id: fake()->uuid(),
        );

        $clientMock->allows('put')
            ->once()
            ->withArgs([
                '/mockserver/verify',
                ['json' => [$expectation->verifyFormat()]],
            ])
            ->andReturn(new Response(202));

        $testConnector->verify($expectation);
    }
);

it(
    'verifies remote expectation received correct number of requests',
    /**
     * @throws UnsuccessfulVerificationException
     * @throws VerificationFailException
     */
    function (): void {
        /** @var Client&MockInterface $clientMock */
        $clientMock        = Mockery::mock(Client::class);
        $testConnector     = new Connector($clientMock);

        $atLeast           = fake()->numberBetween(1, 50);
        $atMost            = $atLeast + fake()->numberBetween(1, 50);

        $expectation       = new Expectation(
            new RequestMatcher\Properties('GET', '/path'),
            new Action\Response(),
            new Times($atLeast, $atMost),
            id: fake()->uuid(),
        );

        $clientMock->allows('put')
            ->once()
            ->withArgs([
                '/mockserver/verify',
                ['json' => [$expectation->verifyFormat()]],
            ])
            ->andReturn(new Response(202));

        $testConnector->verify($expectation);
    }
);

it(
    'throws exception for non-202 responses on verification',
    /**
     * @throws UnsuccessfulVerificationException
     * @throws VerificationFailException
     */
    function (): void {
        /** @var Client&MockInterface $clientMock */
        $clientMock    = Mockery::mock(Client::class);
        $testConnector = new Connector($clientMock);

        $expectation   = new Expectation(
            new RequestMatcher\Properties('GET', '/path'),
            id: fake()->uuid(),
        );

        $body     = 'Request not found exactly 1 times, expected:<{';
        $response = new Response(
            406,
            headers: ['Content-Length' => (string) strlen($body)],
            body: $body
        );

        $clientMock->allows('put')
            ->once()
            ->withArgs(
                [
                    '/mockserver/verify',
                    ['json' => [$expectation->verifyFormat()]],
                ]
            )
            ->andReturn($response);

        expect(fn () => $testConnector->verify($expectation))
            ->toThrow(
                function (UnsuccessfulVerificationException $exception) use ($expectation, $response): void {
                    expect($exception->getMessage())
                        ->toBe('Request not found exactly 1 times')
                        ->and($exception->expectation)->toBe($expectation)
                        ->and($exception->response)->toBe($response);
                }
            );
    }
);

it(
    'wraps guzzle exception on unsuccessful verification',
    /**
     * @throws UnsuccessfulVerificationException
     * @throws VerificationFailException
     */
    function (): void {
        /** @var Client&MockInterface $clientMock */
        $clientMock    = Mockery::mock(Client::class);
        $testConnector = new Connector($clientMock);

        $expectation   = new Expectation(
            new RequestMatcher\Properties('GET', '/path'),
            id: fake()->uuid(),
        );

        $body     = 'Request not found exactly 1 times, expected:<{';
        $response = new Response(
            406,
            headers: ['Content-Length' => (string) strlen($body)],
            body: $body
        );
        $exception     = new RequestException('Exception', new Request('GET', '/path'), $response);

        $clientMock->allows('put')
            ->once()
            ->withArgs(
                [
                    '/mockserver/verify',
                    ['json' => [$expectation->verifyFormat()]],
                ]
            )
            ->andThrow($exception);

        expect(fn () => $testConnector->verify($expectation))
            ->toThrow(
                function (UnsuccessfulVerificationException $exception) use ($expectation, $response): void {
                    expect($exception->getMessage())
                        ->toBe('Request not found exactly 1 times')
                        ->and($exception->expectation)->toBe($expectation)
                        ->and($exception->response)->toBe($response);
                }
            );
    }
);

it(
    'wraps guzzle exception on failed verification requests',
    /**
     * @throws UnsuccessfulVerificationException
     * @throws VerificationFailException
     */
    function (): void {
        /** @var Client&MockInterface $clientMock */
        $clientMock        = Mockery::mock(Client::class);
        $testConnector     = new Connector($clientMock);

        $expectation = new Expectation(
            new RequestMatcher\Properties('GET', '/path'),
            id: fake()->uuid(),
        );

        $expectedException = new TransferException();

        $clientMock->allows('put')
            ->once()
            ->withArgs(
                [
                    '/mockserver/verify',
                    ['json' => [$expectation->verifyFormat()]],
                ]
            )
            ->andThrow($expectedException);

        expect(fn () => $testConnector->verify($expectation))
            ->toThrow(
                function (VerificationFailException $exception) use ($expectation, $expectedException): void {
                    expect($exception->getMessage())
                        ->toBe('Fail to check verification for expectation!')
                        ->and($exception->expectation)->toBe($expectation)
                        ->and($exception->getPrevious())->toBe($expectedException);
                }
            );
    }
);
