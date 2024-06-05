<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Borsh;

trait BorshSerializable
{
    /**
     * @param mixed $name
     * @return mixed
     */
    public function __get(mixed $name): mixed
    {
        return $this->{$name};
    }
}
