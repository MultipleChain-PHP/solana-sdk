<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Tests\Feature;

use PHPUnit\Framework\TestCase;
use MultipleChain\SolanaSDK\SolanaRpcClient;

class SolanaRpcClientTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_generates_random_key(): void
    {
        $client = new SolanaRpcClient('abc.com');
        $rpc1 = $client->buildRpc('doStuff', []);
        $rpc2 = $client->buildRpc('doStuff', []);

        $client = new SolanaRpcClient('abc.com');
        $rpc3 = $client->buildRpc('doStuff', []);
        $rpc4 = $client->buildRpc('doStuff', []);

        $this->assertEquals($rpc1['id'], $rpc2['id']);
        $this->assertEquals($rpc3['id'], $rpc4['id']);
        $this->assertNotEquals($rpc1['id'], $rpc4['id']);
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_validates_response_id(): void
    {
        // If we get back a response that doesn't have id set to this->randomKey, throw exception
        $this->markTestIncomplete();
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_throws_exception_for_invalid_methods(): void
    {
        // If we get an error: invalid method response back, throw the correct exception
        $this->markTestIncomplete();
    }
}
