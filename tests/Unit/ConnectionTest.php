<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MultipleChain\SolanaSDK\Connection;
use MultipleChain\SolanaSDK\SolanaRpcClient;

class ConnectionTest extends TestCase
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = new Connection(new SolanaRpcClient(SolanaRpcClient::DEVNET_ENDPOINT));
    }
    /**
     * @return void
     */
    public function testConfirmTransaction(): void
    {
        $this->assertTrue($this->connection->confirmTransaction(
            '4XLpHmpiKXXDM7pAg8CXeSLjw7SYKZaSzJjXHP2E1vL2ndvrJ6GnuHUvaQpY3LHQeJww8fzFLJ9MiLnvgsyyyt3i'
        ));
    }
}
