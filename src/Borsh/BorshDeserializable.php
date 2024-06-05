<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Borsh;

trait BorshDeserializable
{
    /**
     * Create a new instance of this object.
     *
     * Note: must override when the default constructor required parameters!
     *
     * @return static
     */
    public static function borshConstructor(): static
    {
        // @phpstan-ignore-next-line
        return new static();
    }

    /**
     * @param mixed $name
     * @param mixed $value
     * @return void
     */
    public function __set(mixed $name, mixed $value): void
    {
        $this->{$name} = $value;
    }
}
