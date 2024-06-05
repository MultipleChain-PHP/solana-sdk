<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK;

class Program
{
    /**
     * @var SolanaRpcClient
     */
    protected SolanaRpcClient $client;

    /**
     * @param SolanaRpcClient $client
     */
    public function __construct(SolanaRpcClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $method
     * @param array<mixed> $params
     * @return mixed
     */
    public function __call(string $method, array $params = []): mixed
    {
        return $this->client->call($method, ...$params);
    }
}
