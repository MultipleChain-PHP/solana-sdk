<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Util;

use MultipleChain\SolanaSDK\PublicKey;

class SignaturePubkeyPair implements HasPublicKey
{
    /**
     * @var PublicKey
     */
    protected PublicKey $publicKey;

    /**
     * @var string|null
     */
    public ?string $signature;

    /**
     * @param PublicKey $publicKey
     * @param string|null $signature
     */
    public function __construct(PublicKey $publicKey, ?string $signature = null)
    {
        $this->publicKey = $publicKey;
        $this->signature = $signature;
    }

    /**
     * @return PublicKey
     */
    public function getPublicKey(): PublicKey
    {
        return $this->publicKey;
    }
}
