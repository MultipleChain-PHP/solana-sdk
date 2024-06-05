<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Util;

use MultipleChain\SolanaSDK\TransactionInstruction;

class NonceInformation
{
    /**
     * @var string
     */
    public string $nonce;

    /**
     * @var TransactionInstruction
     */
    public TransactionInstruction $nonceInstruction;

    /**
     * @param string $nonce
     * @param TransactionInstruction $nonceInstruction
     */
    public function __construct(string $nonce, TransactionInstruction $nonceInstruction)
    {
        $this->nonce = $nonce;
        $this->nonceInstruction = $nonceInstruction;
    }
}
