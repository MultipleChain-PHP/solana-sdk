<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Accounts;

use MultipleChain\SolanaSDK\Borsh;

class MetadataData
{
    use Borsh\BorshDeserializable;

    public const SCHEMA = [
        Creator::class => Creator::SCHEMA[Creator::class],
        self::class => [
            'kind' => 'struct',
            'fields' => [
                ['name', 'string'],
                ['symbol', 'string'],
                ['uri', 'string'],
                ['sellerFeeBasisPoints', 'u16'],
                ['creators', [
                    'kind' => 'option',
                    'type' => [Creator::class]
                ]]
            ],
        ],
    ];

    /**
     * @param mixed $name
     * @param mixed $value
     * @return void
     */
    public function __set(mixed $name, mixed $value): void
    {
        $this->{$name} = is_string($value) ? preg_replace('/[[:cntrl:]]/', '', $value) : $value;
    }
}
