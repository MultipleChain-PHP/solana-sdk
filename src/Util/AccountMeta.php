<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Util;

use MultipleChain\SolanaSDK\PublicKey;

class AccountMeta implements HasPublicKey
{
    protected PublicKey $publicKey;
    public bool $isSigner;
    public bool $isWritable;

    /**
     * @param PublicKey $publicKey
     * @param bool $isSigner
     * @param bool $isWritable
     */
    public function __construct(PublicKey $publicKey, bool $isSigner, bool $isWritable)
    {
        $this->publicKey = $publicKey;
        $this->isSigner = $isSigner;
        $this->isWritable = $isWritable;
    }

    /**
     * @return PublicKey
     */
    public function getPublicKey(): PublicKey
    {
        return $this->publicKey;
    }
}
