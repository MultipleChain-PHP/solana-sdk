<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK;

use ParagonIE_Sodium_Compat;
use RangeException;
use StephenHill\Base58;
use MultipleChain\SolanaSDK\Exceptions\BaseSolanaPhpSdkException;
use MultipleChain\SolanaSDK\Exceptions\InputValidationException;
use MultipleChain\SolanaSDK\Util\Buffer;
use MultipleChain\SolanaSDK\Util\HasPublicKey;

class PublicKey implements HasPublicKey
{
    /**
     * The length of public keys
     * @var int
     */
    public const LENGTH = 32;

    /**
     * The maximum seed length
     * @var int
     */
    public const MAX_SEED_LENGTH = 32;

    /**
     * @var Buffer
     */
    protected Buffer $buffer;

    /**
     * @param array<mixed>|string|int $bn
     */
    public function __construct(array|string|int $bn)
    {
        if (is_integer($bn)) {
            $this->buffer = Buffer::from()->pad(self::LENGTH, $bn);
        } elseif (is_string($bn)) {
            // https://stackoverflow.com/questions/25343508/detect-if-string-is-binary
            $isBinaryString = preg_match('~[^\x20-\x7E\t\r\n]~', $bn) > 0;

            // if not binary string already, assumed to be a base58 string.
            if ($isBinaryString) {
                $this->buffer = Buffer::from($bn);
            } else {
                $this->buffer = Buffer::fromBase58($bn);
            }
        } else {
            $this->buffer = Buffer::from($bn);
        }

        if (self::LENGTH !== sizeof($this->buffer)) {
            $len = sizeof($this->buffer);
            throw new InputValidationException("Invalid public key input. Expected length 32. Found: {$len}");
        }
    }

    /**
     * @return PublicKey
     */
    public static function default(): PublicKey
    {
        return new PublicKey('11111111111111111111111111111111');
    }

    /**
     * Check if two publicKeys are equal
     * @param PublicKey $publicKey
     * @return bool
     */
    public function equals(PublicKey $publicKey): bool
    {
        return $publicKey->buffer === $this->buffer;
    }

    /**
     * Return the base-58 representation of the public key
     * @return string
     */
    public function toBase58(): string
    {
        return $this->base58()->encode($this->buffer->toString());
    }

    /**
     * Return the byte array representation of the public key
     * @return array<int>
     */
    public function toBytes(): array
    {
        return $this->buffer->toArray();
    }

    /**
     * Return the Buffer representation of the public key
     * @return Buffer
     */
    public function toBuffer(): Buffer
    {
        return $this->buffer;
    }

    /**
     * @return string
     */
    public function toBinaryString(): string
    {
        return $this->buffer->toString();
    }

    /**
     * Return the base-58 representation of the public key
     * @return string
     */
    public function toString(): string
    {
        return $this->toBase58();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Derive a public key from another key, a seed, and a program ID.
     * The program ID will also serve as the owner of the public key, giving
     * it permission to write data to the account.
     *
     * @param PublicKey $fromPublicKey
     * @param string $seed
     * @param PublicKey $programId
     * @return PublicKey
     */
    public static function createWithSeed(PublicKey $fromPublicKey, string $seed, PublicKey $programId): PublicKey
    {
        $buffer = new Buffer();

        $buffer->push($fromPublicKey)
            ->push($seed)
            ->push($programId)
        ;

        $hash = hash('sha256', $buffer->toString());
        $binaryString = sodium_hex2bin($hash);
        return new PublicKey($binaryString);
    }

    /**
     * Derive a program address from seeds and a program ID.
     *
     * @param array<mixed> $seeds
     * @param PublicKey $programId
     * @return PublicKey
     */
    public static function createProgramAddress(array $seeds, PublicKey $programId): PublicKey
    {
        $buffer = new Buffer();
        foreach ($seeds as $seed) {
            $seed = Buffer::from($seed);
            if (sizeof($seed) > self::MAX_SEED_LENGTH) {
                throw new InputValidationException("Max seed length exceeded.");
            }
            $buffer->push($seed);
        }

        $buffer->push($programId)->push('ProgramDerivedAddress');

        $hash = hash('sha256', $buffer->toString());
        $binaryString = sodium_hex2bin($hash);

        if (static::isOnCurve($binaryString)) {
            throw new InputValidationException('Invalid seeds, address must fall off the curve.');
        }

        return new PublicKey($binaryString);
    }

    /**
     * @param array<mixed> $seeds
     * @param PublicKey $programId
     * @return array<mixed> 2 elements, [0] = PublicKey, [1] = integer
     */
    public static function findProgramAddress(array $seeds, PublicKey $programId): array
    {
        $nonce = 255;

        while (0 != $nonce) {
            try {
                $copyOfSeedsWithNonce = $seeds;
                array_push($copyOfSeedsWithNonce, [$nonce]);
                $address = static::createProgramAddress($copyOfSeedsWithNonce, $programId);
            } catch (\Exception $exception) {
                $nonce--;
                continue;
            }
            return [$address, $nonce];
        }

        throw new BaseSolanaPhpSdkException('Unable to find a viable program address nonce.');
    }

    /**
     * Check that a pubkey is on the ed25519 curve.
     * @param mixed $publicKey
     * @return bool
     */
    public static function isOnCurve(mixed $publicKey): bool
    {
        try {
            $binaryString = $publicKey instanceof PublicKey
                ? $publicKey->toBinaryString()
                : $publicKey;

            /**
             * Sodium extension method sometimes returns "conversion failed" exception.
             * sodium_crypto_sign_ed25519_pk_to_curve25519($binaryString);
             */
            ParagonIE_Sodium_Compat::crypto_sign_ed25519_pk_to_curve25519($binaryString);

            return true;
        } catch (RangeException $exception) {
            return false;
        }
    }

    /**
     * Convenience.
     *
     * @return Base58
     */
    public static function base58(): Base58
    {
        return new Base58();
    }

    /**
     * @return PublicKey
     */
    public function getPublicKey(): PublicKey
    {
        return $this;
    }
}
