<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Programs;

use MultipleChain\SolanaSDK\Program;
use MultipleChain\SolanaSDK\Keypair;
use MultipleChain\SolanaSDK\PublicKey;
use MultipleChain\SolanaSDK\Util\Signer;
use MultipleChain\SolanaSDK\Util\Buffer;
use MultipleChain\SolanaSDK\Util\AccountMeta;
use MultipleChain\SolanaSDK\TokenInstruction;
use MultipleChain\SolanaSDK\Programs\SystemProgram;
use MultipleChain\SolanaSDK\TransactionInstruction;

class SplTokenProgram extends Program
{
    /**
     * @var string
     */
    public const SOLANA_TOKEN_PROGRAM = 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA';

    /**
     * @var string
     */
    public const SOLANA_TOKEN_PROGRAM_2022 = 'TokenzQdBNbLqP5VEhdkAS6EPFLC1PHnBqCXEpPxuEb';

    /**
     * @var string
     */
    public const ASSOCIATED_TOKEN_PROGRAM_ID = 'ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL';

    /**
     * @var int
     */
    public const TRANSFER = 3;

    /**
     * @var int
     */
    public const APPROVE = 4;

    /**
     * @param PublicKey $mint
     * @param PublicKey $owner
     * @param bool $allowOwnerOffCurve
     * @param PublicKey|null $programId
     * @param PublicKey|null $associatedTokenProgramId
     * @return PublicKey
     */
    public static function getAssociatedTokenAddress(
        PublicKey $mint,
        PublicKey $owner,
        bool $allowOwnerOffCurve = false,
        ?PublicKey $programId = null,
        ?PublicKey $associatedTokenProgramId = null
    ): PublicKey {
        if (!$allowOwnerOffCurve && !PublicKey::isOnCurve($owner->toBuffer())) {
            throw new \RuntimeException('Token owner is off curve');
        }

        $programId = $programId ?? new PublicKey(self::SOLANA_TOKEN_PROGRAM);
        $associatedTokenProgramId = $associatedTokenProgramId ?? new PublicKey(self::ASSOCIATED_TOKEN_PROGRAM_ID);

        list($address, ) = PublicKey::findProgramAddress(
            [
                $owner->toBuffer(),
                $programId->toBuffer(),
                $mint->toBuffer(),
            ],
            $associatedTokenProgramId
        );

        return $address;
    }

    /**
     * @param PublicKey $payer
     * @param PublicKey $associatedToken
     * @param PublicKey $owner
     * @param PublicKey $mint
     * @param Buffer $instructionData
     * @param PublicKey|null $programId
     * @param PublicKey|null $associatedTokenProgramId
     * @return TransactionInstruction
     */
    public static function buildAssociatedTokenAccountInstruction(
        PublicKey $payer,
        PublicKey $associatedToken,
        PublicKey $owner,
        PublicKey $mint,
        Buffer $instructionData,
        ?PublicKey $programId = null,
        ?PublicKey $associatedTokenProgramId = null
    ): TransactionInstruction {
        $programId = $programId ?? new PublicKey(self::SOLANA_TOKEN_PROGRAM);
        $associatedTokenProgramId = $associatedTokenProgramId ?? new PublicKey(self::ASSOCIATED_TOKEN_PROGRAM_ID);

        $keys = [
            new AccountMeta($payer, true, true),
            new AccountMeta($associatedToken, false, true),
            new AccountMeta($owner, false, false),
            new AccountMeta($mint, false, false),
            new AccountMeta(SystemProgram::programId(), false, false),
            new AccountMeta($programId, false, false),
        ];

        return new TransactionInstruction(
            $associatedTokenProgramId,
            $keys,
            $instructionData
        );
    }

    /**
     * @param PublicKey $payer
     * @param PublicKey $associatedToken
     * @param PublicKey $owner
     * @param PublicKey $mint
     * @param PublicKey|null $programId
     * @param PublicKey|null $associatedTokenProgramId
     * @return TransactionInstruction
     */
    public static function createAssociatedTokenAccountInstruction(
        PublicKey $payer,
        PublicKey $associatedToken,
        PublicKey $owner,
        PublicKey $mint,
        ?PublicKey $programId = null,
        ?PublicKey $associatedTokenProgramId = null
    ): TransactionInstruction {
        return self::buildAssociatedTokenAccountInstruction(
            $payer,
            $associatedToken,
            $owner,
            $mint,
            Buffer::alloc(0),
            $programId,
            $associatedTokenProgramId
        );
    }

    /**
     * @param array<AccountMeta> $keys
     * @param PublicKey $ownerOrAuthority
     * @param array<PublicKey|Keypair|Signer> $multiSigners
     * @return array<AccountMeta>
     */
    public static function addSigners(
        array $keys,
        PublicKey $ownerOrAuthority,
        array $multiSigners,
    ): array {
        if (count($multiSigners) > 0) {
            $keys[] = new AccountMeta($ownerOrAuthority, false, false);
            foreach ($multiSigners as $signer) {
                $keys[] = new AccountMeta($signer->getPublicKey(), true, false);
            }
        } else {
            $keys[] = new AccountMeta($ownerOrAuthority, true, false);
        }

        return $keys;
    }

    /**
     * @param PublicKey $source
     * @param PublicKey $destination
     * @param PublicKey $owner
     * @param int $amount
     * @param array<PublicKey|Keypair|Signer> $multiSigners
     * @param PublicKey|null $programId
     * @return TransactionInstruction
     */
    public static function createTransferInstruction(
        PublicKey $source,
        PublicKey $destination,
        PublicKey $owner,
        int $amount,
        array $multiSigners = [],
        ?PublicKey $programId = null,
    ): TransactionInstruction {
        $programId = $programId ?? new PublicKey(self::SOLANA_TOKEN_PROGRAM);

        $keys = [
            new AccountMeta($source, false, true),
            new AccountMeta($destination, false, true),
        ];

        $keys = self::addSigners($keys, $owner, $multiSigners);

        $data = (new Buffer())
            ->push(pack('C', self::TRANSFER))
            ->push(pack('P', $amount));

        return new TransactionInstruction(
            $programId,
            $keys,
            $data->getData()
        );
    }

    /**
     * @param PublicKey $account
     * @param PublicKey $delegate
     * @param PublicKey $owner
     * @param int $amount
     * @param array<PublicKey|Keypair|Signer> $multiSigners
     * @param PublicKey|null $programId
     * @return TransactionInstruction
     */
    public static function createApproveInstruction(
        PublicKey $account,
        PublicKey $delegate,
        PublicKey $owner,
        int $amount,
        array $multiSigners = [],
        ?PublicKey $programId = null,
    ): TransactionInstruction {
        $programId = $programId ?? new PublicKey(self::SOLANA_TOKEN_PROGRAM);

        $keys = [
            new AccountMeta($account, false, true),
            new AccountMeta($delegate, false, false),
        ];

        $keys = self::addSigners($keys, $owner, $multiSigners);

        $data = (new Buffer())
            ->push(pack('C', self::APPROVE))
            ->push(pack('P', $amount));

        return new TransactionInstruction(
            $programId,
            $keys,
            $data->getData()
        );
    }
}
