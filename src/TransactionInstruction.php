<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK;

use MultipleChain\SolanaSDK\Util\AccountMeta;
use MultipleChain\SolanaSDK\Util\Buffer;

class TransactionInstruction
{
    /**
     * @var array<AccountMeta>
     */
    public array $keys;

    /**
     * @var PublicKey
     */
    public PublicKey $programId;

    /**
     * @var Buffer
     */
    public Buffer $data;

    /**
     * @param PublicKey $programId
     * @param array<AccountMeta> $keys
     * @param mixed $data
     */
    public function __construct(PublicKey $programId, array $keys, mixed $data = null)
    {
        $this->programId = $programId;
        $this->keys = $keys;
        $this->data = Buffer::from($data);
    }
}
