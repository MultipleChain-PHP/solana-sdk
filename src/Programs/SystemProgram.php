<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Programs;

use MultipleChain\SolanaSDK\Program;
use MultipleChain\SolanaSDK\PublicKey;
use MultipleChain\SolanaSDK\Util\AccountMeta;
use MultipleChain\SolanaSDK\TransactionInstruction;
use MultipleChain\SolanaSDK\Exceptions\AccountNotFoundException;

class SystemProgram extends Program
{
    public const PROGRAM_INDEX_CREATE_ACCOUNT = 0;
    public const PROGRAM_INDEX_TRANSFER = 2;

    /**
     * Public key that identifies the System program
     *
     * @return PublicKey
     */
    public static function programId(): PublicKey
    {
        return new PublicKey('11111111111111111111111111111111');
    }

    /**
     * @param string $pubKey
     * @return array<mixed>
     */
    public function getAccountInfo(string $pubKey): array
    {
        $accountResponse = $this->client->call('getAccountInfo', [$pubKey, ["encoding" => "jsonParsed"]])['value'];

        if (! $accountResponse) {
            throw new AccountNotFoundException("API Error: Account {$pubKey} not found.");
        }

        return $accountResponse;
    }

    /**
     * @param string $pubKey
     * @return float
     */
    public function getBalance(string $pubKey): float
    {
        return $this->client->call('getBalance', [$pubKey])['value'];
    }

    /**
     * @param string $transactionSignature
     * @return array<mixed>
     */
    public function getConfirmedTransaction(string $transactionSignature): array
    {
        return $this->client->call('getConfirmedTransaction', [$transactionSignature]);
    }

    /**
     * NEW: This method is only available in solana-core v1.7 or newer.
     * > Please use getConfirmedTransaction for solana-core v1.6
     *
     * @param string $transactionSignature
     * @return array<mixed>
     */
    public function getTransaction(string $transactionSignature): array
    {
        return $this->client->call('getTransaction', [$transactionSignature]);
    }

    /**
     * Generate a transaction instruction that transfers lamports from one account to another
     *
     * @param PublicKey $fromPubkey
     * @param PublicKey $toPublicKey
     * @param int $lamports
     * @return TransactionInstruction
     */
    public static function transfer(
        PublicKey $fromPubkey,
        PublicKey $toPublicKey,
        int $lamports
    ): TransactionInstruction {
        // 4 byte instruction index + 8 bytes lamports
        // look at https://www.php.net/manual/en/function.pack.php for formats.
        $data = [
            // uint32 @phpstan-ignore-next-line
            ...unpack("C*", pack("V", self::PROGRAM_INDEX_TRANSFER)),
            // int64 @phpstan-ignore-next-line
            ...unpack("C*", pack("P", $lamports)),
        ];
        $keys = [
            new AccountMeta($fromPubkey, true, true),
            new AccountMeta($toPublicKey, false, true),
        ];

        return new TransactionInstruction(
            static::programId(),
            $keys,
            $data
        );
    }

    /**
     * Generate a transaction instruction that creates a new account
     *
     * @param PublicKey $fromPubkey
     * @param PublicKey $newAccountPublicKey
     * @param int $lamports
     * @param int $space
     * @param PublicKey $programId
     * @return TransactionInstruction
     */
    public static function createAccount(
        PublicKey $fromPubkey,
        PublicKey $newAccountPublicKey,
        int $lamports,
        int $space,
        PublicKey $programId
    ): TransactionInstruction {
        // look at https://www.php.net/manual/en/function.pack.php for formats.
        $data = [
            // uint32 @phpstan-ignore-next-line
            ...unpack("C*", pack("V", self::PROGRAM_INDEX_CREATE_ACCOUNT)),
            // int64 @phpstan-ignore-next-line
            ...unpack("C*", pack("P", $lamports)),
            // int64 @phpstan-ignore-next-line
            ...unpack("C*", pack("P", $space)),
            ...$programId->toBytes(),
        ];
        $keys = [
            new AccountMeta($fromPubkey, true, true),
            new AccountMeta($newAccountPublicKey, true, true),
        ];

        return new TransactionInstruction(
            static::programId(),
            $keys,
            $data
        );
    }
}
