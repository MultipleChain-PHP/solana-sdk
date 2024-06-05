<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Accounts;

use MultipleChain\SolanaSDK\Borsh;

class Creator
{
    use Borsh\BorshDeserializable;

    public const SCHEMA = [
        self::class => [
            'kind' => 'struct',
            'fields' => [
                ['address', 'pubkeyAsString'],
                ['verified', 'u8'],
                ['share', 'u8'],
            ],
        ],
    ];
}
