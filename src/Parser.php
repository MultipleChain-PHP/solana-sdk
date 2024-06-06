<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK;

use MultipleChain\Utils;
use MultipleChain\Utils\Number;
use MultipleChain\SolanaSDK\Parsers\Transaction;

class Parser
{
    use Transaction;

    /**
     * @var array<string>
     */
    public const PROGRAMS = [
        '11111111111111111111111111111111' => 'system',
        'MemoSq4gqABAXKb96qnH8TysNcWxMyWCqXgDLGmfcHr' => 'memo',
        'Stake11111111111111111111111111111111111111' => 'stake',
        'Vote111111111111111111111111111111111111111' => 'vote',
        'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA' => 'spl-token',
        'So11111111111111111111111111111111111111112' => 'native-mint',
        'TokenzQdBNbLqP5VEhdkAS6EPFLC1PHnBqCXEpPxuEb' => 'spl-token-2022',
        '9pan9bMn5HatX4EJdBwg9VgCa7Uz5HL8N1m5D3NdXejP' => 'native-mint-2022',
        'ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL' => 'spl-associated-token-account',
    ];

    /**
     * @param int $lamports
     * @return Number
     */
    public static function fromLamports(int $lamports): Number
    {
        return new Number(Utils::toHex($lamports / 10 ** 9), 9);
    }

    /**
     * @param int $amount
     * @param int $decimals
     * @return Number
     */
    public static function fromTokenFormat(int $amount, int $decimals): Number
    {
        return new Number(Utils::toHex((int) ($amount / 10 ** $decimals)), $decimals);
    }
}
