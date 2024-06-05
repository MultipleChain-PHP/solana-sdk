<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\Factory as HttpFactory;
use MultipleChain\SolanaSDK\Exceptions\GenericException;
use MultipleChain\SolanaSDK\Exceptions\MethodNotFoundException;
use MultipleChain\SolanaSDK\Exceptions\InvalidIdResponseException;

/**
 * @see https://docs.solana.com/developing/clients/jsonrpc-api
 */
class SolanaRpcClient
{
    public const LOCAL_ENDPOINT = 'http://localhost:8899';
    public const DEVNET_ENDPOINT = 'https://api.devnet.solana.com';
    public const TESTNET_ENDPOINT = 'https://api.testnet.solana.com';
    public const MAINNET_ENDPOINT = 'https://api.mainnet-beta.solana.com';

    /**
     * Per: https://www.jsonrpc.org/specification
     */
    // Invalid JSON was received by the server.
    // An error occurred on the server while parsing the JSON text.
    public const ERROR_CODE_PARSE_ERROR = -32700;
    // The JSON sent is not a valid Request object.
    public const ERROR_CODE_INVALID_REQUEST = -32600;
    // The method does not exist / is not available.
    public const ERROR_CODE_METHOD_NOT_FOUND = -32601;
    // Invalid method parameter(s).
    public const ERROR_CODE_INVALID_PARAMETERS = -32602;
    // Internal JSON-RPC error.
    public const ERROR_CODE_INTERNAL_ERROR = -32603;
    // Reserved for implementation-defined server-errors.
    // -32000 to -32099 is server error - no const.

    /**
     * @var string
     */
    protected string $endpoint;

    /**
     * @var int
     */
    protected int $randomKey;

    /**
     * @param string $endpoint
     * @throws \Exception
     */
    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->randomKey = random_int(0, 99999999);
    }

    /**
     * @param string $network
     * @return SolanaRpcClient
     * @throws \Exception
     */
    public static function from(string $network): SolanaRpcClient
    {
        switch ($network) {
            case 'local':
                return new self(self::LOCAL_ENDPOINT);
            case 'devnet':
                return new self(self::DEVNET_ENDPOINT);
            case 'testnet':
                return new self(self::TESTNET_ENDPOINT);
            case 'mainnet':
                return new self(self::MAINNET_ENDPOINT);
            default:
                throw new \Exception('Invalid network');
        }
    }

    /**
     * @param string $method
     * @param array<mixed> $params
     * @param array<mixed> $headers
     * @return mixed
     * @throws GenericException
     * @throws InvalidIdResponseException
     * @throws MethodNotFoundException
     */
    public function call(string $method, array $params = [], array $headers = []): mixed
    {
        $response = (new HttpFactory())->acceptJson()->withHeaders($headers)->post(
            $this->endpoint,
            $this->buildRpc($method, $params)
        )->throw();

        $this->validateResponse($response, $method, $params);

        return $response->json('result');
    }

    /**
     * @param string $method
     * @param array<mixed> $params
     * @return array<mixed>
     */
    public function buildRpc(string $method, array $params): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $this->randomKey,
            'method' => $method,
            'params' => $params,
        ];
    }

    /**
     * @param Response $response
     * @param string $method
     * @param array<mixed> $params
     * @return void
     * @throws GenericException
     * @throws InvalidIdResponseException
     * @throws MethodNotFoundException
     */
    protected function validateResponse(Response $response, string $method, array $params): void
    {
        if ($response['id'] !== $this->randomKey) {
            throw new InvalidIdResponseException();
        }

        if (isset($response['error'])) {
            if (self::ERROR_CODE_METHOD_NOT_FOUND === $response['error']['code']) {
                throw new MethodNotFoundException("API Error: Method {$method} not found.");
            } else {
                throw new GenericException($response['error']['message']);
            }
        }
    }

    /**
     * @return int
     */
    public function getRandomKey(): int
    {
        return $this->randomKey;
    }
}
