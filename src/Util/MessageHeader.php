<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Util;

class MessageHeader
{
    /**
     * @var int
     */
    public int $numRequiredSignature;

    /**
     * @var int
     */
    public int $numReadonlySignedAccounts;

    /**
     * @var int
     */
    public int $numReadonlyUnsignedAccounts;

    /**
     * @param int $numRequiredSignature
     * @param int $numReadonlySignedAccounts
     * @param int $numReadonlyUnsignedAccounts
     */
    public function __construct(
        int $numRequiredSignature,
        int $numReadonlySignedAccounts,
        int $numReadonlyUnsignedAccounts
    ) {
        $this->numRequiredSignature = $numRequiredSignature;
        $this->numReadonlySignedAccounts = $numReadonlySignedAccounts;
        $this->numReadonlyUnsignedAccounts = $numReadonlyUnsignedAccounts;
    }
}
