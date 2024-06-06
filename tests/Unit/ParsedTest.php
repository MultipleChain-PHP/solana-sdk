<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MultipleChain\SolanaSDK\PublicKey;
use MultipleChain\SolanaSDK\Connection;
use MultipleChain\SolanaSDK\Transaction;
use MultipleChain\SolanaSDK\SolanaRpcClient;
use MultipleChain\SolanaSDK\Util\Commitment;
use MultipleChain\SolanaSDK\Programs\SystemProgram;

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
                                // 'parsed' => [
                                //     'info' => [
                                //         'extensionTypes' => [
                                //             'immutableOwner'
                                //         ],
                                //         'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx'
                                //     ],
                                //     'type' => 'getAccountDataSize'
                                // ],
                                'program' => 'spl-token',
                                'programId' => new PublicKey('TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA'),
                                'stackHeight' => 2,
                                'data' => '84eT',
                                'accounts' => [
                                    '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx'
                                ],
                            ],
                            [
                                // 'parsed' => [
                                //     'info' => [
                                //         'lamports' => 2039280,
                                //         'newAccount' => 'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5',
                                //         'owner' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                                //         'source' => '37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ',
                                //         'space' => 165
                                //     ],
                                //     'type' => 'createAccount'
                                // ],
                                'program' => 'system',
                                'programId' => new PublicKey('11111111111111111111111111111111'),
                                'stackHeight' => 2,
                                'data' => '11119os1e9qSs2u7TsThXqkBSRVFxhmYaFKFZ1waB2X7armDmvK3p5GmLdUxYdg3h7QSrL',
                                'accounts' => [
                                    '37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ',
                                    'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5'
                                ],
                            ],
                            [
                                // 'parsed' => [
                                //     'info' => [
                                //         'account' => 'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5'
                                //     ],
                                //     'type' => 'initializeImmutableOwner'
                                // ],
                                'program' => 'spl-token',
                                'programId' => new PublicKey('TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA'),
                                'stackHeight' => 2,
                                'data' => 'P',
                                'accounts' => [
                                    'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5'
                                ],
                            ],
                            [
                                // 'parsed' => [
                                //     'info' => [
                                //         'account' => 'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5',
                                //         'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                                //         'owner' => '7bn9So6CKmdn2vHYZU5oG6gaVEewW3N7U8vJoNmSv2vB'
                                //     ],
                                //     'type' => 'initializeAccount3'
                                // ],
                                'program' => 'spl-token',
                                'programId' => new PublicKey('TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA'),
                                'stackHeight' => 2,
                                'accounts' => [
                                    'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5',
                                    '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx'
                                ],
                                'data' => '6TjUpNzczccucJQP4edH8k57xz8EkundpB46AHxTGDBM9',
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
                'loadedAddresses' => [
                    'readonly' => [],
                    'writable' => []
                ]
            ],
            'transaction' => [
                'message' => [
                    'accountKeys' => [
                        [
                            'pubkey' => new PublicKey('37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ'),
                            'signer' => true,
                            'writable' => true,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => new PublicKey('DFZs5Z27MRxEatmCX3XjgrtsvH3GJK9xyffwN8MWmpgC'),
                            'signer' => false,
                            'writable' => true,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => new PublicKey('GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5'),
                            'signer' => false,
                            'writable' => true,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => new PublicKey('11111111111111111111111111111111'),
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => new PublicKey('2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx'),
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => new PublicKey('7bn9So6CKmdn2vHYZU5oG6gaVEewW3N7U8vJoNmSv2vB'),
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => new PublicKey('ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL'),
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => new PublicKey('ComputeBudget111111111111111111111111111111'),
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ],
                        [
                            'pubkey' => new PublicKey('TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA'),
                            'signer' => false,
                            'writable' => false,
                            'source' => 'transaction'
                        ]
                    ],
                    'instructions' => [
                        [
                            'programId' => new PublicKey('ComputeBudget111111111111111111111111111111'),
                            'accounts' => [],
                            'data' => '3s2DQSEX3t4P',
                            'program' => null,
                            'stackHeight' => null
                        ],
                        [
                            'programId' => new PublicKey('ComputeBudget111111111111111111111111111111'),
                            'accounts' => [],
                            'data' => 'HMypLP',
                            'program' => null,
                            'stackHeight' => null
                        ],
                        [
                            // 'parsed' => [
                            //     'info' => [
                            //         'account' => 'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5',
                            //         'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                            //         'source' => '37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ',
                            //         'systemProgram' => '11111111111111111111111111111111',
                            //         'tokenProgram' => 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA',
                            //         'wallet' => '7bn9So6CKmdn2vHYZU5oG6gaVEewW3N7U8vJoNmSv2vB'
                            //     ],
                            //     'type' => 'create'
                            // ],
                            'programId' => new PublicKey('ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL'),
                            'accounts' => [
                                new PublicKey("37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ"),
                                new PublicKey("GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5"),
                                new PublicKey("7bn9So6CKmdn2vHYZU5oG6gaVEewW3N7U8vJoNmSv2vB"),
                                new PublicKey("2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx"),
                                new PublicKey("11111111111111111111111111111111"),
                                new PublicKey("TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA")
                            ],
                            'data' => null,
                            'program' => 'spl-associated-token-account',
                            'stackHeight' => null
                        ],
                        [
                            // 'parsed' => [
                            //     'info' => [
                            //         'authority' => '37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ',
                            //         'destination' => 'GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5',
                            //         'mint' => '2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx',
                            //         'source' => 'DFZs5Z27MRxEatmCX3XjgrtsvH3GJK9xyffwN8MWmpgC',
                            //         'tokenAmount' => [
                            //             'amount' => '5000000000000',
                            //             'decimals' => 8,
                            //             'uiAmount' => 50000,
                            //             'uiAmountString' => '50000'
                            //         ]
                            //     ],
                            //     'type' => 'transferChecked'
                            // ],
                            'programId' => new PublicKey('TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA'),
                            'accounts' => [
                                new PublicKey("DFZs5Z27MRxEatmCX3XjgrtsvH3GJK9xyffwN8MWmpgC"),
                                new PublicKey("2ZHwL3dXk3szRgiBLZi244NmKs2VmoBx764AYMY2tQfx"),
                                new PublicKey("GjaVSinePTeafvA5HG9qwF59jCHcK1t42E2dNvgggSr5"),
                                new PublicKey("37p742pby4ACHiGcT3d58gsjmC3Kd9bH2L89E3hY8FHZ")
                            ],
                            'data' => 'g7BNnsTEuYro1',
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
     * @return void
     */
    public function testSolTransferTx(): void
    {
        $result = $this->connection->getParsedTransaction($this->tokenTransferTx);

        $expectedResult = $this->getExpectedTokenTxResult();

        $this->assertEquals($expectedResult, $result);
    }
}