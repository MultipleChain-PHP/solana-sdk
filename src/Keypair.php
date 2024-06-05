<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK;

use SodiumException;
use MultipleChain\Utils;
use MultipleChain\SolanaSDK\Util\Buffer;
use MultipleChain\SolanaSDK\Util\HasPublicKey;
use MultipleChain\SolanaSDK\Util\HasSecretKey;

/**
 * An account keypair used for signing transactions.
 */
class Keypair implements HasPublicKey, HasSecretKey
{
    /**
     * The public key for this keypair
     *
     * @var Buffer
     */
    public Buffer $publicKey;

    /**
     * The raw secret key for this keypair
     *
     * @var Buffer
     */
    public Buffer $secretKey;

    /**
     * @param array<mixed>|string $publicKey
     * @param array<mixed>|string $secretKey
     */
    public function __construct(array|string $publicKey = null, array|string $secretKey = null)
    {
        if (null == $publicKey && null == $secretKey) {
            $keypair = sodium_crypto_sign_keypair();

            $publicKey = sodium_crypto_sign_publickey($keypair);
            $secretKey = sodium_crypto_sign_secretkey($keypair);
        }

        $this->publicKey = Buffer::from($publicKey);
        $this->secretKey = Buffer::from($secretKey);
    }

    /**
     * @return Keypair
     * @throws SodiumException
     */
    public static function generate(): Keypair
    {
        $keypair = sodium_crypto_sign_keypair();

        return static::from($keypair);
    }

    /**
     * @param string $keypair
     * @return Keypair
     * @throws SodiumException
     */
    public static function from(string $keypair): Keypair
    {
        if (strlen($keypair) > 0) {
            return new Keypair(
                sodium_crypto_sign_publickey($keypair),
                sodium_crypto_sign_secretkey($keypair)
            );
        } else {
            throw new SodiumException('Invalid keypair');
        }
    }

    /**
     * Create a keypair from a raw secret key byte array.
     *
     * This method should only be used to recreate a keypair from a previously
     * generated secret key. Generating keypairs from a random seed should be done
     * with the {@link Keypair.fromSeed} method.
     *
     * @param mixed $secretKey
     * @return Keypair
     */
    public static function fromSecretKey(mixed $secretKey): Keypair
    {
        $secretKey = Buffer::from($secretKey)->toString();

        if (strlen($secretKey) > 0) {
            $publicKey = sodium_crypto_sign_publickey_from_secretkey($secretKey);

            return new Keypair(
                $publicKey,
                $secretKey
            );
        } else {
            throw new SodiumException('Invalid secret key');
        }
    }

    /**
     * Create a keypair from a raw private key byte array.
     *
     * @param string $privateKey
     * @return Keypair
     */
    public static function fromPrivateKey(string $privateKey): Keypair
    {
        return static::fromSecretKey(Utils::base58Decode($privateKey));
    }

    /**
     * Generate a keypair from a 32 byte seed.
     *
     * @param string|array<mixed> $seed
     * @return Keypair
     * @throws SodiumException
     */
    public static function fromSeed(string|array $seed): Keypair
    {
        $seed = Buffer::from($seed)->toString();

        if (strlen($seed) > 0) {
            $keypair = sodium_crypto_sign_seed_keypair($seed);

            return static::from($keypair);
        } else {
            throw new SodiumException('Invalid seed');
        }
    }

    /**
     * The public key for this keypair
     *
     * @return PublicKey
     */
    public function getPublicKey(): PublicKey
    {
        return new PublicKey($this->publicKey->toString());
    }

    /**
     * The raw secret key for this keypair
     *
     * @return Buffer
     */
    public function getSecretKey(): Buffer
    {
        return Buffer::from($this->secretKey);
    }
}
