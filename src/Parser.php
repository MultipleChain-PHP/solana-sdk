<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK;

use MultipleChain\Utils;
use MultipleChain\Utils\Number;

class Parser
{
    /**
     * @var array<string>
     */
    private const PROGRAMS = [
            '11111111111111111111111111111111' => 'system',
            'MemoSq4gqABAXKb96qnH8TysNcWxMyWCqXgDLGmfcHr' => 'memo',
            'Stake11111111111111111111111111111111111111' => 'stake',
            'Vote111111111111111111111111111111111111111' => 'vote',
            'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA' => 'spl-token',
            'So11111111111111111111111111111111111111112' => 'native-mint',
            'TokenzQdBNbLqP5VEhdkAS6EPFLC1PHnBqCXEpPxuEb' => 'spl-token-2022',
            '9pan9bMn5HatX4EJdBwg9VgCa7Uz5HL8N1m5D3NdXejP' => 'native-mint-2022',
            'ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL' => 'spl-associated-token-account',
    ];

    /**
     * @param int $lamports
     * @return Number
     */
    // @phpstan-ignore-next-line
    private function fromLamports(int $lamports): Number
    {
        return new Number(Utils::toHex($lamports / 10 ** 9), 9);
    }

    /**
     * @param int $amount
     * @param int $decimals
     * @return Number
     */
    // @phpstan-ignore-next-line
    private function fromTokenFormat(int $amount, int $decimals): Number
    {
        return new Number(Utils::toHex((int) ($amount / 10 ** $decimals)), $decimals);
    }

    /**
     * @param array<mixed> $transaction
     * @return array<mixed>
     */
    private function findSigners(array $transaction): array
    {
        $message = $transaction['message'];
        $accountKeys = $message['accountKeys'];
        $signatures = $transaction['signatures'];

        $signers = array_fill(0, count($accountKeys), false);

        for ($i = 0; $i < count($signatures); $i++) {
            $signers[$i] = true;
        }

        return $signers;
    }

    /**
     * @param array<mixed> $transaction
     * @return array<mixed>
     */
    private function findWritableAccounts(array $transaction): array
    {
        $message = $transaction['message'];
        $accountKeys = $message['accountKeys'];

        $writableAccounts = array_fill(0, count($accountKeys), false);

        $numRequiredSignatures = $message['header']['numRequiredSignatures'];
        $numReadonlySignedAccounts = $message['header']['numReadonlySignedAccounts'];
        $numReadonlyUnsignedAccounts = $message['header']['numReadonlyUnsignedAccounts'];

        for ($i = 0; $i < $numRequiredSignatures; $i++) {
            if ($i >= $numReadonlySignedAccounts) {
                $writableAccounts[$i] = true;
            }
        }

        for ($i = $numRequiredSignatures; $i < count($accountKeys); $i++) {
            if ($i < count($accountKeys) - $numReadonlyUnsignedAccounts) {
                $writableAccounts[$i] = true;
            }
        }

        return $writableAccounts;
    }

    /**
     * @param mixed $key
     * @param array<mixed>|null $loadedAddresses
     * @return string
     */
    private function findSource(mixed $key, ?array $loadedAddresses): string
    {
        if (
            is_array($loadedAddresses)
            && (in_array($key, $loadedAddresses['readonly']) || in_array($key, $loadedAddresses['writable']))
        ) {
            return 'lookupTable';
        }
        return 'transaction';
    }

    /**
     * @param array<mixed> $accountKeys
     * @param array<mixed> $signers
     * @param array<mixed> $writableAccounts
     * @param array<mixed>|null $loadedAddresses
     * @return array<mixed>
     */
    private function parseAccountKeys(
        array $accountKeys,
        array $signers,
        array $writableAccounts,
        ?array $loadedAddresses
    ): array {
        return array_map(
            function ($key, $index) use ($signers, $writableAccounts, $loadedAddresses) {
                return [
                    'pubkey' => new PublicKey($key),
                    'signer' => $signers[$index] ?? false,
                    'writable' => $writableAccounts[$index] ?? false,
                    'source' => $this->findSource($key, $loadedAddresses)
                ];
            },
            $accountKeys,
            array_keys($accountKeys)
        );
    }

    /**
     * @param array<mixed> $tx
     * @param array<mixed>|null $loadedAddresses
     * @return array<mixed>
     */
    private function parseTx(array $tx, ?array $loadedAddresses): array
    {
        $signers = $this->findSigners($tx);
        $writableAccounts = $this->findWritableAccounts($tx);
        return [
            'signatures' => $tx['signatures'] ?? [],
            'message' => [
                'accountKeys' => $this->parseAccountKeys(
                    $tx['message']['accountKeys'] ?? [],
                    $signers,
                    $writableAccounts,
                    $loadedAddresses
                ),
                'instructions' => $this->parseInstructions(
                    $tx['message']['instructions'] ?? [],
                    $tx['message']['accountKeys'] ?? []
                ),
                'recentBlockhash' => $tx['message']['recentBlockhash'] ?? null,
                'addressTableLookups' => $tx['message']['addressTableLookups'] ?? null
            ]
        ];
    }

    /**
     * @param array<mixed> $balances
     * @return array<mixed>
     */
    private function parseTokenBalances(array $balances): array
    {
        return array_map(function ($balance) {
            return [
                'accountIndex' => $balance['accountIndex'] ?? 0,
                'mint' => $balance['mint'] ?? null,
                'owner' => $balance['owner'] ?? null,
                'programId' => $balance['programId'] ?? null,
                'uiTokenAmount' => [
                    'amount' => $balance['uiTokenAmount']['amount'] ?? null,
                    'decimals' => $balance['uiTokenAmount']['decimals'] ?? 0,
                    'uiAmount' => $balance['uiTokenAmount']['uiAmount'] ?? null,
                    'uiAmountString' => $balance['uiTokenAmount']['uiAmountString'] ?? null
                ]
            ];
        }, $balances);
    }

    /**
     * @param array<mixed> $instructions
     * @param array<mixed> $accountKeys
     * @return array<mixed>
     */
    private function parseInstructions(array $instructions, array $accountKeys): array
    {
        return array_map(function ($instruction) use ($accountKeys) {
            $pubKey = $accountKeys[$instruction['programIdIndex'] ?? null];
            return [
                'programId' => $pubKey ? new PublicKey($pubKey) : null,
                'accounts' => array_map(function ($index) use ($accountKeys) {
                    return $accountKeys[$index] ?? null;
                }, $instruction['accounts'] ?? []),
                'data' => $instruction['data'] ?? null,
                'stackHeight' => $instruction['stackHeight'] ?? null,
                'program' => self::PROGRAMS[$pubKey] ?? null
            ];
        }, $instructions);
    }

    /**
     * @param array<mixed> $instructions
     * @param array<mixed> $accountKeys
     * @return array<mixed>
     */
    private function parseInnerInstructions(array $instructions, array $accountKeys): array
    {
        return array_map(function ($innerInstruction) use ($accountKeys) {
            return [
                'index' => $innerInstruction['index'] ?? 0,
                'instructions' => $this->parseInstructions(
                    $innerInstruction['instructions'] ?? [],
                    $accountKeys
                ),
            ];
        }, $instructions);
    }

    /**
     * @param array<mixed> $transaction
     * @return array<mixed>
     */
    private function parseMeta(array $transaction): array
    {
        return [
            'fee' => $transaction['meta']['fee'] ?? 0,
            'err' => $transaction['meta']['err'] ?? null,
            'rewards' => $transaction['meta']['rewards'] ?? [],
            'status' => $transaction['meta']['status'] ?? null,
            'preBalances' => $transaction['meta']['preBalances'] ?? [],
            'postBalances' => $transaction['meta']['postBalances'] ?? [],
            'logMessages' => $transaction['meta']['logMessages'] ?? null,
            'loadedAddresses' => $transaction['meta']['loadedAddresses'] ?? null,
            'computeUnitsConsumed' => $transaction['meta']['computeUnitsConsumed'] ?? null,
            'preTokenBalances' => $this->parseTokenBalances($transaction['meta']['preTokenBalances'] ?? []),
            'postTokenBalances' => $this->parseTokenBalances($transaction['meta']['postTokenBalances'] ?? []),
            'innerInstructions' => $this->parseInnerInstructions(
                $transaction['meta']['innerInstructions'] ?? [],
                $transaction['transaction']['message']['accountKeys']
            ),
        ];
    }

    /**
     * @param array<mixed> $transaction
     * @return array<mixed>
     */
    public function parseTransaction(array $transaction): array
    {
        $tx = $transaction['transaction'];
        $loadedAddresses = $transaction['meta']['loadedAddresses'] ?? null;

        return [
            'slot' => $transaction['slot'] ?? null,
            'meta' => $this->parseMeta($transaction),
            'version' => $transaction['version'] ?? 0,
            'blockTime' => $transaction['blockTime'] ?? null,
            'transaction' => $this->parseTx($tx, $loadedAddresses),
        ];
    }
}
