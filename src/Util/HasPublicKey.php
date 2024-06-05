<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Util;

use MultipleChain\SolanaSDK\PublicKey;

interface HasPublicKey
{
    /**
     * @return PublicKey
     */
    public function getPublicKey(): PublicKey;
}
