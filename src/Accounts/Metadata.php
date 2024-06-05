<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Accounts;

use MultipleChain\SolanaSDK\Borsh\Borsh;
use MultipleChain\SolanaSDK\Borsh\BorshDeserializable;

class Metadata
{
    use BorshDeserializable;

    public const SCHEMA = [
        Creator::class => Creator::SCHEMA[Creator::class],
        MetadataData::class => MetadataData::SCHEMA[MetadataData::class],
        self::class => [
            'kind' => 'struct',
            'fields' => [
                ['key', 'u8'],
                ['updateAuthority', 'pubkeyAsString'],
                ['mint', 'pubkeyAsString'],
                ['data', MetadataData::class],
                ['primarySaleHappened', 'u8'], // bool
                ['isMutable', 'u8'], // bool
            ],
        ],
    ];

    /**
     * @param array<mixed> $buffer
     * @return mixed
     */
    public static function fromBuffer(array $buffer): mixed
    {
        return Borsh::deserialize(self::SCHEMA, self::class, $buffer);
    }
}
