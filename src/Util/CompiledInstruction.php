<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Util;

class CompiledInstruction
{
    public int $programIdIndex;
    /**
     * array of indexes.
     *
     * @var array<integer>
     */
    public array $accounts;

    /**
     * @var Buffer
     */
    public Buffer $data;

    /**
     * @param int $programIdIndex
     * @param array<int> $accounts
     * @param mixed $data
     */
    public function __construct(
        int $programIdIndex,
        array $accounts,
        mixed $data
    ) {
        $this->programIdIndex = $programIdIndex;
        $this->accounts = $accounts;
        $this->data = Buffer::from($data);
    }
}
