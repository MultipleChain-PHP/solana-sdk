<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Util;

interface HasSecretKey
{
    /**
     * @return Buffer
     */
    public function getSecretKey(): Buffer;
}
