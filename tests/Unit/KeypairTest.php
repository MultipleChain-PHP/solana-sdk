<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MultipleChain\SolanaSDK\Keypair;
use MultipleChain\SolanaSDK\Util\Buffer;

class KeypairTest extends TestCase
{
    /**
     * Seeded from
     * https://github.com/solana-labs/solana-web3.js/blob/master/test/keypair.test.ts
     * on Oct 2, 2021
     */

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_public_key_from_keypair(): void
    {
        $keypair = Keypair::fromSecretKey(json_decode(file_get_contents(__DIR__ . '/keypair.json'), true));
        $this->assertEquals('6P2UFjKLnf2LHsPAvwLty5XFc2642S2EByW5PgKb7UFD', $keypair->getPublicKey()->toString());
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_public_key_from_private_key(): void
    {
        $keypair = Keypair::fromPrivateKey(
            "44gurmbgSzfMZfqhmaUj1nuvbLMCbMyo3shHvJSesAAfLLTzU9p2aB6Jue7XF2ViBirSzbeUMTYVjRmEj5jW4puu"
        );
        $this->assertEquals('gEbjuPsW9xwKpUdQ69khDP3kNEw17HTSmLCMu1S9Msm', $keypair->getPublicKey()->toString());
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_new_keypair(): void
    {
        $keypair = new Keypair();

        $this->assertEquals(64, sizeof($keypair->getSecretKey()));
        $this->assertEquals(32, sizeof($keypair->getPublicKey()->toBytes()));
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_generate_new_keypair(): void
    {
        $keypair = Keypair::generate();

        $this->assertEquals(64, sizeof($keypair->getSecretKey()));
        $this->assertEquals(32, sizeof($keypair->getPublicKey()->toBytes()));
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_keypair_from_secret_key(): void
    {
        $secretKey = sodium_base642bin(
            'mdqVWeFekT7pqy5T49+tV12jO0m+ESW7ki4zSU9JiCgbL0kJbj5dvQ/PqcDAzZLZqzshVEs01d1KZdmLh4uZIg==',
            SODIUM_BASE64_VARIANT_ORIGINAL
        );

        $keypair = Keypair::fromSecretKey($secretKey);

        $this->assertEquals('2q7pyhPwAwZ3QMfZrnAbDhnh9mDUqycszcpf86VgQxhF', $keypair->getPublicKey()->toBase58());
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_generate_keypair_from_seed(): void
    {
        $byteArray = array_fill(0, 32, 8);

        $seedString = pack('C*', ...$byteArray);

        $keypair = Keypair::fromSeed($seedString);

        $this->assertEquals('2KW2XRd9kwqet15Aha2oK3tYvd3nWbTFH1MBiRAv1BE1', $keypair->getPublicKey()->toBase58());
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_bin2array_and_array2bin_are_equivalent(): void
    {
        $keypair = sodium_crypto_sign_keypair();
        $publicKey = sodium_crypto_sign_publickey($keypair);

        $valueAsArray = Buffer::from($publicKey)->toArray();
        $valueAsString = Buffer::from($valueAsArray)->toString();

        $this->assertEquals($publicKey, $valueAsString);
    }
}
