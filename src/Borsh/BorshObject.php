<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Borsh;

trait BorshObject
{
    use BorshDeserializable;
    use BorshSerializable;
}
