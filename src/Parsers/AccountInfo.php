<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Parsers;

use MultipleChain\SolanaSDK\Parsers\Types\ParsedAccountInfo;

trait AccountInfo
{
    /**
     * @param array<mixed> $data
     * @return ParsedAccountInfo
     */
    public function parseAccountInfo(array $data): ParsedAccountInfo
    {
        return ParsedAccountInfo::from($data);
    }
}
