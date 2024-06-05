<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MultipleChain\SolanaSDK\SolanaRpcClient;
use MultipleChain\SolanaSDK\Programs\SystemProgram;
use MultipleChain\SolanaSDK\Exceptions\GenericException;

class SolanaTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_will_throw_exception_when_rpc_account_response_is_null(): void
    {
        $client = new SolanaRpcClient(SolanaRpcClient::DEVNET_ENDPOINT);
        $solana = new SystemProgram($client);
        $this->expectException(GenericException::class);
        $solana->getAccountInfo('abc123');
    }
}
