<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK;

use MultipleChain\SolanaSDK\Util\Buffer;
use MultipleChain\SolanaSDK\Util\HasPublicKey;
use MultipleChain\SolanaSDK\Util\HasSecretKey;

class Account implements HasPublicKey, HasSecretKey
{
    protected Keypair $keypair;

    /**
     * @param mixed $secretKey
     */
    public function __construct(mixed $secretKey = null)
    {
        if ($secretKey) {
            $secretKeyString = Buffer::from($secretKey)->toString();

            $this->keypair = Keypair::fromSecretKey($secretKeyString);
        } else {
            $this->keypair = Keypair::generate();
        }
    }

    /**
     * @return PublicKey
     */
    public function getPublicKey(): PublicKey
    {
        return $this->keypair->getPublicKey();
    }

    /**
     * @return Buffer
     */
    public function getSecretKey(): Buffer
    {
        return $this->keypair->getSecretKey();
    }
}
