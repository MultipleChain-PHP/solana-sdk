<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Programs;

use MultipleChain\SolanaSDK\Program;

class SplTokenProgram extends Program
{
    public const SOLANA_TOKEN_PROGRAM_ID = 'TokenkegQfeZyiNwAJbNbGKPFXCWuBvf9Ss623VQ5DA';

    /**
     * @param string $pubKey
     * @return mixed
     */
    public function getTokenAccountsByOwner(string $pubKey): mixed
    {
        return $this->client->call('getTokenAccountsByOwner', [
            $pubKey,
            [
                'programId' => self::SOLANA_TOKEN_PROGRAM_ID,
            ],
            [
                'encoding' => 'jsonParsed',
            ],
        ]);
    }
}
