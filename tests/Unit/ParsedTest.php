<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MultipleChain\SolanaSDK\Connection;
use MultipleChain\SolanaSDK\SolanaRpcClient;

class ParsedTest extends TestCase
{
    /**
     * @var Connection
     */
    private Connection $connection;

    private string $solTransferTx
        = "2RDU1otuPR6UtevwYCQWnngvvjPiTFuHFdyCnzwQVR8wyZ7niqACt2QBmfuyD5aXJbEXSEUcqFquiCEtcQZzkWif";

    private string $tokenTransferTx
        = "4XLpHmpiKXXDM7pAg8CXeSLjw7SYKZaSzJjXHP2E1vL2ndvrJ6GnuHUvaQpY3LHQeJww8fzFLJ9MiLnvgsyyyt3i";

    private string $token2022Transfer
        = "3c2Myd3k4Pw1NbsCjuskkZCtbD9HRTjoyoxh2u7qsVLgFN1RbRYAXXRKzBRzwTAmv2pXDjArbotzVL6AVehBMeyg";

    private string $nftTransferTx
        = "3vrCoNVmeNgGG4LB1qvvdx21TYm6dnPBmhFqXChsusuLn5ZEjFZNFG3BwQQ8fodBYiPXG8QokdBLWjRtxgi7tnRD";

    private string $splTokenAddress = '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx';

    private string $walletAddress = 'gEbjuPsW9xwKpUdQ69khDP3kNEw17HTSmLCMu1S9Msm';

    private string $nftId = 'FxN19KB5UeZJFwxLFgT57WvYYXYhBFKxVumfq37xU4Ck';

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->connection = new Connection(SolanaRpcClient::from('devnet'));
    }

    /**
     * @return array<mixed>
     */
    private function getExpectedTokenTxResult(): array
    {
        return [
            'slot' => 299257412,
            'blockTime' => 1715865344,
            'meta' => [
                'err' => null,
                'fee' => 125000,
                'computeUnitsConsumed' => 27105,
                'innerInstructions' => [
                    [
                        'index' => 2,
                        'instructions' => [
                            [
                                'data' => null,
                                'program' => 'spl-token',
                                'programId' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                                'stackHeight' => 2,
                                'parsed' => [
                                    'info' => [
                                        'extensionTypes' => [
                                            'immutableOwner'
                                        ],
                                        'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx'
                                    ],
                                    'type' => 'getAccountDataSize'
                                ],
                                'accounts' => []
                            ],
                            [
                                'data' => null,
                                'program' => 'system',
                                'programId' => '11111111111111111111111111111111',
                                'stackHeight' => 2,
                                'parsed' => [
                                    'info' => [
                                        'lamports' => 2039280,
                                        'newAccount' => 'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5',
                                        'owner' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                                        'source' => '37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ',
                                        'space' => 165
                                    ],
                                    'type' => 'createAccount'
                                ],
                                'accounts' => []
                            ],
                            [
                                'data' => null,
                                'program' => 'spl-token',
                                'programId' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                                'stackHeight' => 2,
                                'parsed' => [
                                    'info' => [
                                        'account' => 'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5'
                                    ],
                                    'type' => 'initializeImmutableOwner'
                                ],
                                'accounts' => []
                            ],
                            [
                                'data' => null,
                                'program' => 'spl-token',
                                'programId' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                                'stackHeight' => 2,
                                'parsed' => [
                                    'info' => [
                                        'account' => 'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5',
                                        'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                                        'owner' => '7bn9So6CKmdn2vHYZU5oG6gaVEewW3N7U8vJoNmSv2vB'
                                    ],
                                    'type' => 'initializeAccount3'
                                ],
                                'accounts' => []
                            ]
                        ],
                    ]
                ],
                'logMessages' => [
                    'Program ComputeBudget111111111111111111111111111111 invoke [1]',
                    'Program ComputeBudget111111111111111111111111111111 success',
                    'Program ComputeBudget111111111111111111111111111111 invoke [1]',
                    'Program ComputeBudget111111111111111111111111111111 success',
                    'Program ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL invoke [1]',
                    'Program log: Create',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA invoke [2]',
                    'Program log: Instruction: GetAccountDataSize',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA consumed 1622 of 394255 compute units',
                    'Program return: TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA pQAAAAAAAAA=',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA success',
                    'Program 11111111111111111111111111111111 invoke [2]',
                    'Program 11111111111111111111111111111111 success',
                    'Program log: Initialize the associated token account',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA invoke [2]',
                    'Program log: Instruction: InitializeImmutableOwner',
                    'Program log: Please upgrade to SPL Token 2022 for immutable owner support',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA consumed 1405 of 387615 compute units',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA success',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA invoke [2]',
                    'Program log: Instruction: InitializeAccount3',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA consumed 4241 of 383731 compute units',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA success',
                    'Program ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL consumed 20514 of 399700 compute units',
                    'Program ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL success',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA invoke [1]',
                    'Program log: Instruction: TransferChecked',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA consumed 6291 of 379186 compute units',
                    'Program TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA success'
                ],
                'postBalances' => [
                    17119046640,
                    2039280,
                    2039280,
                    1,
                    1461600,
                    0,
                    731913600,
                    1,
                    934087680
                ],
                'preBalances' => [
                    17121210920,
                    2039280,
                    0,
                    1,
                    1461600,
                    0,
                    731913600,
                    1,
                    934087680
                ],
                'postTokenBalances' => [
                    [
                        'accountIndex' => 1,
                        'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                        'owner' => '37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ',
                        'programId' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                        'uiTokenAmount' => [
                            'amount' => '9994803000000000',
                            'decimals' => 8,
                            'uiAmount' => 99948030,
                            'uiAmountString' => '99948030'
                        ]
                    ],
                    [
                        'accountIndex' => 2,
                        'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                        'owner' => '7bn9So6CKmdn2vHYZU5oG6gaVEewW3N7U8vJoNmSv2vB',
                        'programId' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                        'uiTokenAmount' => [
                            'amount' => '5000000000000',
                            'decimals' => 8,
                            'uiAmount' => 50000,
                            'uiAmountString' => '50000'
                        ]
                    ]
                ],
                'preTokenBalances' => [
                    [
                        'accountIndex' => 1,
                        'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                        'owner' => '37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ',
                        'programId' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                        'uiTokenAmount' => [
                            'amount' => '9999803000000000',
                            'decimals' => 8,
                            'uiAmount' => 99998030,
                            'uiAmountString' => '99998030'
                        ]
                    ]
                ],
                'rewards' => [],
                'status' => [
                    'Ok' => null
                ],
                'loadedAddresses' => null
            ],
            'transaction' => [
                'message' => [
                    'accountKeys' => [
                        [
                            'pubkey' => '37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ',
                            'signer' => true,
                            'writable' => true,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => 'DFZs5Z27MRxEatmCX3XjgrtsvH3GJK9xyffwN8MWmpgC',
                            'signer' => false,
                            'writable' => true,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => 'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5',
                            'signer' => false,
                            'writable' => true,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => '11111111111111111111111111111111',
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => '7bn9So6CKmdn2vHYZU5oG6gaVEewW3N7U8vJoNmSv2vB',
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => 'ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL',
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => 'ComputeBudget111111111111111111111111111111',
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ]
                    ],
                    'instructions' => [
                        [
                            'programId' => 'ComputeBudget111111111111111111111111111111',
                            'accounts' => [],
                            'data' => '3s2DQSEX3t4P',
                            'parsed' => null,
                            'program' => null,
                            'stackHeight' => null
                        ],
                        [
                            'programId' => 'ComputeBudget111111111111111111111111111111',
                            'accounts' => [],
                            'data' => 'HMypLP',
                            'parsed' => null,
                            'program' => null,
                            'stackHeight' => null
                        ],
                        [
                            'data' => null,
                            'programId' => 'ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL',
                            'accounts' => [],
                            'parsed' => [
                                'info' => [
                                    'account' => 'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5',
                                    'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                                    'source' => '37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ',
                                    'systemProgram' => '11111111111111111111111111111111',
                                    'tokenProgram' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                                    'wallet' => '7bn9So6CKmdn2vHYZU5oG6gaVEewW3N7U8vJoNmSv2vB'
                                ],
                                'type' => 'create'
                            ],
                            'program' => 'spl-associated-token-account',
                            'stackHeight' => null
                        ],
                        [
                            'data' => null,
                            'programId' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                            'accounts' => [],
                            'parsed' => [
                                'info' => [
                                    'authority' => '37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ',
                                    'destination' => 'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5',
                                    'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                                    'source' => 'DFZs5Z27MRxEatmCX3XjgrtsvH3GJK9xyffwN8MWmpgC',
                                    'tokenAmount' => [
                                        'amount' => '5000000000000',
                                        'decimals' => 8,
                                        'uiAmount' => 50000,
                                        'uiAmountString' => '50000'
                                    ]
                                ],
                                'type' => 'transferChecked'
                            ],
                            'program' => 'spl-token',
                            'stackHeight' => null
                        ],
                    ],
                    'recentBlockhash' => 'Eua6hPeuJgsScbwTo3rmuGVJQS9xLZFu2eRZLCM3HcNk',
                    'addressTableLookups' => null
                ],
                'signatures' => [
                    '4XLpHmpiKXXDM7pAg8CXeSLjw7SYKZaSzJjXHP2E1vL2ndvrJ6GnuHUvaQpY3LHQeJww8fzFLJ9MiLnvgsyyyt3i'
                ],
            ],
            'version' => 'legacy'
        ];
    }

    /**
     * @param array<mixed> $input
     * @return array<mixed>
     */
    private function arrayFilterRecursive(array $input): array
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                if (empty($value)) {
                    $value = null;
                } else {
                    $value = $this->arrayFilterRecursive($value);
                }
            }
        }

        return array_filter($input, fn ($value) => null !== $value);
    }

    /**
     * @return void
     */
    public function testTokenTransferTx(): void
    {
        $result = $this->connection->getParsedTransaction($this->tokenTransferTx);

        if (!$result) {
            $this->fail('Failed to get parsed transaction');
        }

        $expectedResult = $this->getExpectedTokenTxResult();

        $this->assertEquals($result->toArray(), $expectedResult);
    }

    /**
     * @return void
     */
    public function testSplTokenAccountInfo(): void
    {
        $result = $this->connection->getParsedAccountInfo($this->splTokenAddress);

        $this->assertEquals($result->toArray()['data'], [
            'parsed' => [
                'info' => [
                    'decimals' => 8,
                    'freezeAuthority' => 'HH3K7b4RoemS7wFDZmqEBNeUkxrkZvS4n7waSuSqafzi',
                    'isInitialized' => true,
                    'mintAuthority' => 'HH3K7b4RoemS7wFDZmqEBNeUkxrkZvS4n7waSuSqafzi',
                    'supply' => '10000000000000000000'
                ],
                'type' => 'mint'
            ],
            'program' => 'spl-token',
            'space' => 82
        ]);
    }

    /**
     * @return void
     */
    public function testParsedTokenAccountsByOwner(): void
    {
        $result = $this->connection->getParsedTokenAccountsByOwner($this->walletAddress, [
            'mint' => $this->splTokenAddress
        ]);

        $info = $result[0]->toArray()['account']['data']['parsed']['info'];

        $this->assertEquals(
            [
                'mint' => $info['mint'],
                'owner' => $info['owner']
            ],
            [
                'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                'owner' => 'gEbjuPsW9xwKpUdQ69khDP3kNEw17HTSmLCMu1S9Msm',
            ]
        );
    }

    /**
     * @return void
     */
    public function testTokenLargestAccounts(): void
    {
        $result = $this->connection->getTokenLargestAccounts($this->nftId);

        $this->assertEquals($result[0]['address'], '3WdxNZnmmcFCNtW7VixRZJNtK1CKpN8GK1kpFReaEZMd');
    }

    /**
     * @return void
     */
    public function testTokenSupply(): void
    {
        $result = $this->connection->getTokenSupply($this->splTokenAddress);

        $this->assertEquals($result['uiAmount'], 100000000000);
    }

    /**
     * @return void
     */
    public function testSolTransferTx(): void
    {
        $result1 = $this->connection->call('getTransaction', [
            $this->solTransferTx,
            [
                "encoding" => "jsonParsed",
                "maxSupportedTransactionVersion" => 0,
                "commitment" => $this->connection->getCommitmentString(null),
            ]
        ]);

        $result2 = $this->connection->getParsedTransaction($this->solTransferTx);

        $this->assertEquals(
            $this->arrayFilterRecursive($result1),
            $this->arrayFilterRecursive($result2->toArray())
        );
    }

    /**
     * @return void
     */
    public function testNftTransferTx(): void
    {
        $result1 = $this->connection->call('getTransaction', [
            $this->nftTransferTx,
            [
                "encoding" => "jsonParsed",
                "maxSupportedTransactionVersion" => 0,
                "commitment" => $this->connection->getCommitmentString(null),
            ]
        ]);

        $result2 = $this->connection->getParsedTransaction($this->nftTransferTx);

        $this->assertEquals(
            $this->arrayFilterRecursive($result1),
            $this->arrayFilterRecursive($result2->toArray())
        );
    }

    /**
     * @return void
     */
    public function testToken2022TransferTx(): void
    {
        $result1 = $this->connection->call('getTransaction', [
            $this->token2022Transfer,
            [
                "encoding" => "jsonParsed",
                "maxSupportedTransactionVersion" => 0,
                "commitment" => $this->connection->getCommitmentString(null),
            ]
        ]);

        $result2 = $this->connection->getParsedTransaction($this->token2022Transfer);

        $this->assertEquals(
            $this->arrayFilterRecursive($result1),
            $this->arrayFilterRecursive($result2->toArray())
        );
    }
}
