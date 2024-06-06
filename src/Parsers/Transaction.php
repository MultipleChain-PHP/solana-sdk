<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Parsers;

use MultipleChain\SolanaSDK\Parser;
use MultipleChain\SolanaSDK\PublicKey;
use MultipleChain\SolanaSDK\Parsers\Types\TokenAmount;
use MultipleChain\SolanaSDK\Parsers\Types\TokenBalance;
use MultipleChain\SolanaSDK\Parsers\Types\ParsedMessage;
use MultipleChain\SolanaSDK\Parsers\Types\LoadedAddresses;
use MultipleChain\SolanaSDK\Parsers\Types\ParsedInstruction;
use MultipleChain\SolanaSDK\Parsers\Types\ParsedTransaction;
use MultipleChain\SolanaSDK\Parsers\Types\ParsedMessageAccount;
use MultipleChain\SolanaSDK\Parsers\Types\ParsedTransactionMeta;
use MultipleChain\SolanaSDK\Parsers\Types\ParsedInnerInstruction;
use MultipleChain\SolanaSDK\Parsers\Types\ParsedAddressTableLookup;
use MultipleChain\SolanaSDK\Parsers\Types\ParsedTransactionWithMeta;

trait Transaction
{
    /**
     * @var array<mixed>
     */
    private array $meta = [];

    /**
     * @var array<string>
     */
    private array $accountKeys = [];

    /**
     * @var array<string>
     */
    private array $instructions = [];

    /**
     * @var array<mixed>
     */
    private array $transaction = [];

    /**
     * @var array<mixed>|null
     */
    private ?array $loadedAddresses = null;

    /**
     * @return array<mixed>
     */
    private function findSigners(): array
    {
        $signatures = $this->transaction['signatures'];

        $signers = array_fill(0, count($this->accountKeys), false);

        for ($i = 0; $i < count($signatures); $i++) {
            $signers[$i] = true;
        }

        return $signers;
    }

    /**
     * @return array<mixed>
     */
    private function findWritableAccounts(): array
    {
        $message = $this->transaction['message'];

        $writableAccounts = array_fill(0, count($this->accountKeys), false);

        $numRequiredSignatures = $message['header']['numRequiredSignatures'];
        $numReadonlySignedAccounts = $message['header']['numReadonlySignedAccounts'];
        $numReadonlyUnsignedAccounts = $message['header']['numReadonlyUnsignedAccounts'];

        for ($i = 0; $i < $numRequiredSignatures; $i++) {
            if ($i >= $numReadonlySignedAccounts) {
                $writableAccounts[$i] = true;
            }
        }

        for ($i = $numRequiredSignatures; $i < count($this->accountKeys); $i++) {
            if ($i < count($this->accountKeys) - $numReadonlyUnsignedAccounts) {
                $writableAccounts[$i] = true;
            }
        }

        return $writableAccounts;
    }

    /**
     * @param mixed $key
     * @return string
     */
    private function findSource(mixed $key): string
    {
        if (
            is_array($this->loadedAddresses)
            && (
                in_array($key, $this->loadedAddresses['readonly'])
                || in_array($key, $this->loadedAddresses['writable'])
            )
        ) {
            return 'lookupTable';
        }
        return 'transaction';
    }

    /**
     * @return array<ParsedMessageAccount>
     */
    private function parseAccountKeys(): array
    {
        $signers = $this->findSigners();
        $writableAccounts = $this->findWritableAccounts();
        return array_map(
            function ($key, $index) use ($signers, $writableAccounts) {
                return ParsedMessageAccount::from([
                    'pubkey' => new PublicKey($key),
                    'source' => $this->findSource($key),
                    'signer' => $signers[$index] ?? false,
                    'writable' => $writableAccounts[$index] ?? false
                ]);
            },
            $this->accountKeys,
            array_keys($this->accountKeys)
        );
    }

    /**
     * @param array<mixed> $balances
     * @return array<TokenBalance>
     */
    private function parseTokenBalances(array $balances): array
    {
        return array_map(function ($balance) {
            return TokenBalance::from([
                'mint' => $balance['mint'] ?? null,
                'owner' => $balance['owner'] ?? null,
                'programId' => $balance['programId'] ?? null,
                'accountIndex' => $balance['accountIndex'] ?? 0,
                'uiTokenAmount' => TokenAmount::from([
                    'amount' => $balance['uiTokenAmount']['amount'] ?? null,
                    'decimals' => $balance['uiTokenAmount']['decimals'] ?? 0,
                    'uiAmount' => $balance['uiTokenAmount']['uiAmount'] ?? null,
                    'uiAmountString' => $balance['uiTokenAmount']['uiAmountString'] ?? null
                ])
            ]);
        }, $balances);
    }

    /**
     * @param array<mixed> $instructions
     * @return array<ParsedInstruction>
     */
    private function parseInstructions(array $instructions): array
    {
        return array_map(function ($instruction) {
            $pubKey = $this->accountKeys[$instruction['programIdIndex'] ?? null];
            return ParsedInstruction::from([
                'programId' => $pubKey ? new PublicKey($pubKey) : null,
                'accounts' => array_map(function ($index) {
                    return $this->accountKeys[$index] ?? null;
                }, $instruction['accounts'] ?? []),
                'data' => $instruction['data'] ?? null,
                'stackHeight' => $instruction['stackHeight'] ?? null,
                'program' => Parser::PROGRAMS[$pubKey] ?? null
            ]);
        }, $instructions);
    }

    /**
     * @param array<mixed> $instructions
     * @return array<ParsedInnerInstruction>
     */
    private function parseInnerInstructions(array $instructions): array
    {
        return array_map(function ($innerInstruction) {
            return ParsedInnerInstruction::from([
                'index' => $innerInstruction['index'] ?? 0,
                'instructions' => $this->parseInstructions(
                    $innerInstruction['instructions'] ?? [],
                ),
            ]);
        }, $instructions);
    }

    /**
     * @return ParsedTransactionMeta
     */
    private function parseMeta(): ParsedTransactionMeta
    {
        return ParsedTransactionMeta::from([
            'fee' => $this->meta['fee'] ?? 0,
            'err' => $this->meta['err'] ?? null,
            'rewards' => $this->meta['rewards'] ?? [],
            'status' => $this->meta['status'] ?? null,
            'preBalances' => $this->meta['preBalances'] ?? [],
            'postBalances' => $this->meta['postBalances'] ?? [],
            'logMessages' => $this->meta['logMessages'] ?? null,
            'computeUnitsConsumed' => $this->meta['computeUnitsConsumed'] ?? null,
            'preTokenBalances' => $this->parseTokenBalances($this->meta['preTokenBalances'] ?? []),
            'postTokenBalances' => $this->parseTokenBalances($this->meta['postTokenBalances'] ?? []),
            'innerInstructions' => $this->parseInnerInstructions($this->meta['innerInstructions'] ?? []),
            'loadedAddresses' => $this->loadedAddresses ? LoadedAddresses::from($this->loadedAddresses) : null,
        ]);
    }


    /**
     * @return ParsedTransaction
     */
    private function parseTransactionInternal(): ParsedTransaction
    {
        $lookups = $this->transaction['message']['addressTableLookups'] ?? null;
        return ParsedTransaction::from([
            'signatures' => $this->transaction['signatures'] ?? [],
            'message' => ParsedMessage::from([
                'accountKeys' => $this->parseAccountKeys(),
                'instructions' => $this->parseInstructions($this->instructions),
                'recentBlockhash' => $this->transaction['message']['recentBlockhash'] ?? null,
                'addressTableLookups' => $lookups ?  ParsedAddressTableLookup::from($lookups) : null,
            ])
        ]);
    }

    /**
     * @param array<mixed> $transaction
     * @return ParsedTransactionWithMeta
     */
    public function parseTransaction(array $transaction): ParsedTransactionWithMeta
    {
        $this->meta = $transaction['meta'];
        $this->transaction = $transaction['transaction'];
        $this->loadedAddresses = $this->meta['loadedAddresses'] ?? null;
        $this->accountKeys = $this->transaction['message']['accountKeys'] ?? [];
        $this->instructions = $this->transaction['message']['instructions'] ?? [];

        return ParsedTransactionWithMeta::from([
            'meta' => $this->parseMeta(),
            'slot' => $transaction['slot'] ?? null,
            'version' => $transaction['version'] ?? 0,
            'blockTime' => $transaction['blockTime'] ?? null,
            'transaction' => $this->parseTransactionInternal(),
        ]);
    }
}
