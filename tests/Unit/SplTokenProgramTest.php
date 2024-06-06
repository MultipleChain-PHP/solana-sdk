<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MultipleChain\SolanaSDK\Keypair;
use MultipleChain\SolanaSDK\PublicKey;
use MultipleChain\SolanaSDK\Connection;
use MultipleChain\SolanaSDK\SolanaRpcClient;
use MultipleChain\SolanaSDK\Util\AccountMeta;
use MultipleChain\SolanaSDK\Programs\SplTokenProgram;

class SplTokenProgramTest extends TestCase
{
    private string $splTokenAddress = '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx';

    private string $walletAddress = 'gEbjuPsW9xwKpUdQ69khDP3kNEw17HTSmLCMu1S9Msm';

    private string $receiver = 'B8kLt8MZk6cPqdrZKbRwdnh2y1mz5nk6jHFMsRDni6Ei';

    private string $tokenAccount = 'F723Hbpe6vNYiBY5rwXpq7e1P2hcH9en1tET6QHji2TZ';

    private string $nftId = 'FxN19KB5UeZJFwxLFgT57WvYYXYhBFKxVumfq37xU4Ck';

    private Keypair $keypair;

    private Connection $connection;

    private PublicKey $senderPublicKey;

    private PublicKey $receiverPublicKey;

    private PublicKey $mintPublicKey;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->senderPublicKey = new PublicKey($this->walletAddress);
        $this->receiverPublicKey = new PublicKey($this->receiver);
        $this->mintPublicKey = new PublicKey($this->splTokenAddress);

        $this->keypair = Keypair::fromPrivateKey(
            "44gurmbgSzfMZfqhmaUj1nuvbLMCbMyo3shHvJSesAAfLLTzU9p2aB6Jue7XF2ViBirSzbeUMTYVjRmEj5jW4puu"
        );
        $this->connection = new Connection(SolanaRpcClient::from('devnet'));
    }

    /**
     * @return void
     */
    public function testTokenAccount(): void
    {
        $tokenAccount = SplTokenProgram::getAssociatedTokenAddress(
            new PublicKey($this->splTokenAddress),
            new PublicKey($this->walletAddress)
        );

        $this->assertEquals($this->tokenAccount, $tokenAccount->toString());
    }

    /**
     * @return void
     */
    public function testTokenAccountInstruction(): void
    {
        $receiverTokenAccount = SplTokenProgram::getAssociatedTokenAddress(
            $this->mintPublicKey,
            $this->receiverPublicKey
        );

        $this->assertEquals('C1JA7q94cBAK97UzSqYVxXebnqPCkY4ZXeEFDTXTuEb7', $receiverTokenAccount->toString());

        $instruction = SplTokenProgram::createAssociatedTokenAccountInstruction(
            $this->senderPublicKey,
            $receiverTokenAccount,
            $this->receiverPublicKey,
            $this->mintPublicKey
        );

        $this->assertEquals([
            'keys' => array_map(function (AccountMeta $key) {
                return [
                    'isSigner' => $key->isSigner,
                    'isWritable' => $key->isWritable,
                    'pubkey' => $key->publicKey->toString()
                ];
            }, $instruction->keys),
            'programId' => $instruction->programId->toString(),
            'data' => $instruction->data->toString()
        ], [
            'keys' => [
                [
                    'pubkey' => 'gEbjuPsW9xwKpUdQ69khDP3kNEw17HTSmLCMu1S9Msm',
                    'isSigner' => true,
                    'isWritable' => true
                ],
                [
                    'pubkey' => 'C1JA7q94cBAK97UzSqYVxXebnqPCkY4ZXeEFDTXTuEb7',
                    'isSigner' => false,
                    'isWritable' => true
                ],
                [
                    'pubkey' => 'B8kLt8MZk6cPqdrZKbRwdnh2y1mz5nk6jHFMsRDni6Ei',
                    'isSigner' => false,
                    'isWritable' => false
                ],
                [
                    'pubkey' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                    'isSigner' => false,
                    'isWritable' => false
                ],
                [
                    'pubkey' => '11111111111111111111111111111111',
                    'isSigner' => false,
                    'isWritable' => false
                ],
                [
                    'pubkey' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                    'isSigner' => false,
                    'isWritable' => false
                ],
            ],
            'programId' => 'ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL',
            'data' => ''
        ]);
    }

    /**
     * @return void
     */
    public function testTransferInstruction(): void
    {
        $senderTokenAccount = SplTokenProgram::getAssociatedTokenAddress(
            $this->mintPublicKey,
            $this->senderPublicKey
        );

        $receiverTokenAccount = SplTokenProgram::getAssociatedTokenAddress(
            $this->mintPublicKey,
            $this->receiverPublicKey
        );

        $instruction = SplTokenProgram::createTransferInstruction(
            $senderTokenAccount,
            $receiverTokenAccount,
            $this->senderPublicKey,
            100000000000
        );

        $this->assertEquals('AwDodkgXAAAA', $instruction->data->toBase64String());
    }

    /**
     * @return void
     */
    public function testApproveInstruction(): void
    {
        $senderTokenAccount = SplTokenProgram::getAssociatedTokenAddress(
            $this->mintPublicKey,
            $this->senderPublicKey
        );

        $instruction = SplTokenProgram::createApproveInstruction(
            $senderTokenAccount,
            $this->receiverPublicKey,
            $this->senderPublicKey,
            1000000
        );

        $this->assertEquals('BEBCDwAAAAAA', $instruction->data->toBase64String());
    }
}
