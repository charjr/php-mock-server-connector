<?php

namespace Nivseb\PhpMockServerConnector\Server;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Nivseb\PhpMockServerConnector\Exception\FailCreateExpectationException;
use Nivseb\PhpMockServerConnector\Exception\FailResetMockServerException;
use Nivseb\PhpMockServerConnector\Exception\UnsuccessfulVerificationException;
use Nivseb\PhpMockServerConnector\Exception\VerificationFailException;
use Nivseb\PhpMockServerConnector\Structs\Expectation;
use Psr\Http\Message\ResponseInterface;

class Connector
{
    public function __construct(
        protected Client $client,
    ) {
    }

    public static function fromUrl($mockServerUrl): self
    {
        return new self(new Client(['base_uri' => $mockServerUrl]));
    }

    /**
     * @throws FailResetMockServerException
     */
    public function reset(): void
    {
        try {
            $response = $this->client->put('/mockserver/reset');
            if ($response->getStatusCode() !== 200) {
                throw new FailResetMockServerException($response);
            }
        } catch (GuzzleException $exception) {
            throw new FailResetMockServerException(previous: $exception);
        }
    }

    /**
     * @throws FailCreateExpectationException
     */
    public function applyExpectation(Expectation $expectation): Expectation
    {
        try {
            $response = $this->client->put(
                '/mockserver/expectation',
                ['json' => $expectation->createFormat()]
            );
        } catch (GuzzleException $exception) {
            throw new FailCreateExpectationException($expectation, previous: $exception);
        }

        if ($response->getStatusCode() !== 201) {
            throw new FailCreateExpectationException($expectation, $response);
        }

        $expectation->id ??= json_decode($response->getBody()->getContents())[0]->id;

        return $expectation;
    }

    /**
     * @throws UnsuccessfulVerificationException
     * @throws VerificationFailException
     */
    public function verify(Expectation $expectation): void
    {
        try {
            $response = $this->client->put(
                '/mockserver/verify',
                ['json' => [$expectation->verifyFormat()]],
            );
        } catch (GuzzleException $exception) {
            if (!$exception instanceof RequestException) {
                throw new VerificationFailException($expectation, $exception);
            }

            $response = $exception->getResponse();
            if (!$response) {
                throw new VerificationFailException($expectation, $exception);
            }

            throw new UnsuccessfulVerificationException(
                $this->getMessageFromResponse($response),
                $expectation,
                $response,
                $exception
            );
        }

        if ($response->getStatusCode() !== 202) {
            throw new UnsuccessfulVerificationException(
                $this->getMessageFromResponse($response),
                $expectation,
                $response
            );
        }
    }

    protected function buildClient(string $mockServerUrl): Client
    {
        return new Client(['base_uri' => $mockServerUrl]);
    }

    protected function getMessageFromResponse(ResponseInterface $response): string
    {
        $result = $response->getBody()->read((int) $response->getHeaderLine('Content-Length'));

        $matches = [];
        if (preg_match('/^(.*), expected:<\{/', $result, $matches)) {
            return $matches[1];
        }

        return $result;
    }
}
