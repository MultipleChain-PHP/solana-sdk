<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Util;

use MultipleChain\SolanaSDK\PublicKey;

class Signer implements HasPublicKey, HasSecretKey
{
    /**
     * @var PublicKey
     */
    protected PublicKey $publicKey;

    /**
     * @var Buffer
     */
    protected Buffer $secretKey;

    /**
     * @param PublicKey $publicKey
     * @param Buffer $secretKey
     */
    public function __construct(PublicKey $publicKey, Buffer $secretKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @return PublicKey
     */
    public function getPublicKey(): PublicKey
    {
        return $this->publicKey;
    }

    /**
     * @return Buffer
     */
    public function getSecretKey(): Buffer
    {
        return $this->secretKey;
    }
}
