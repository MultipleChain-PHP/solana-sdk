<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MultipleChain\SolanaSDK\Keypair;
use MultipleChain\SolanaSDK\PublicKey;
use MultipleChain\SolanaSDK\Connection;
use MultipleChain\SolanaSDK\Transaction;
use MultipleChain\SolanaSDK\SolanaRpcClient;
use MultipleChain\SolanaSDK\Util\Commitment;
use MultipleChain\SolanaSDK\Programs\SystemProgram;

class SignTest extends TestCase
{
    /**
     * @var Keypair
     */
    private Keypair $keypair;

    /**
     * @var PublicKey
     */
    private PublicKey $fromPublicKey;

    /**
     * @var PublicKey
     */
    private PublicKey $toPublicKey;

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->keypair = Keypair::fromPrivateKey(
            "44gurmbgSzfMZfqhmaUj1nuvbLMCbMyo3shHvJSesAAfLLTzU9p2aB6Jue7XF2ViBirSzbeUMTYVjRmEj5jW4puu"
        );
        $this->fromPublicKey = $this->keypair->getPublicKey();
        $this->toPublicKey = new PublicKey('B8kLt8MZk6cPqdrZKbRwdnh2y1mz5nk6jHFMsRDni6Ei');
        $this->connection = new Connection(SolanaRpcClient::from('devnet'));
    }

    /**
     * @param float $amount
     * @return int
     */
    private function toLamports(float $amount): int
    {
        return (int) ($amount * 10 ** 9);
    }

    /**
     * @param int $amount
     * @return float
     */
    private function fromLamports(int $amount): float
    {
        return (float) ($amount / 10 ** 9);
    }

    /**
     * @return void
     */
    public function testSignTransaction(): void
    {
        $tx = (new Transaction())
            ->add(SystemProgram::transfer(
                $this->fromPublicKey,
                $this->toPublicKey,
                $this->toLamports(0.00001)
            ))
            ->setFeePayer($this->fromPublicKey)
            ->setRecentBlockhash(
                $this->connection->getLatestBlockhash(
                    Commitment::finalized()
                )['blockhash']
            );

        $tx->sign($this->keypair);

        $serialized = $tx->serialize(false, true);

        $this->assertIsString($serialized);

        $txId = $this->connection->sendRawTransaction($serialized);

        $this->assertIsString($txId);
    }
}
