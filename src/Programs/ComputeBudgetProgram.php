<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Programs;

use MultipleChain\SolanaSDK\Program;
use MultipleChain\SolanaSDK\TransactionInstruction;
use MultipleChain\SolanaSDK\PublicKey;

class ComputeBudgetProgram extends Program
{
    public const PROGRAM_ID = 'ComputeBudget111111111111111111111111111111';

    /**
     * @return PublicKey
     */
    public static function programId(): PublicKey
    {
        return new PublicKey(static::PROGRAM_ID);
    }

    /**
     * @param array<int> $units
     * @return TransactionInstruction
     */
    public static function setComputeUnitLimit(array $units): TransactionInstruction
    {
        $data = [
            // uint8 @phpstan-ignore-next-line
            ...unpack("C*", pack("C", 2)),
            // uint32 @phpstan-ignore-next-line
            ...unpack("C*", pack("V", $units)),
        ];
        return new TransactionInstruction(
            static::programId(),
            [],
            $data,
        );
    }

    /**
     * @param array<int> $microLamports
     * @return TransactionInstruction
     */
    public static function setComputeUnitPrice(array $microLamports): TransactionInstruction
    {
        $data = [
            // uint8 @phpstan-ignore-next-line
            ...unpack("C*", pack("C", 3)),
            // uint64 @phpstan-ignore-next-line
            ...unpack("C*", pack("P", $microLamports)),
        ];
        return new TransactionInstruction(
            static::programId(),
            [],
            $data,
        );
    }
}
