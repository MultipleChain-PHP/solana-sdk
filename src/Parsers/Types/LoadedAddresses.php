<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Parsers\Types;

class LoadedAddresses
{
    /**
     * @var array<string>
     */
    private array $writable;

    /**
     * @var array<string>
     */
    private array $readonly;

    /**
     * Get the value of writable
     *
     * @return array<string>
     */
    public function getWritable(): array
    {
        return $this->writable;
    }

    /**
     * Set the value of writable
     *
     * @param array<string> $writable
     *
     * @return self
     */
    public function setWritable(array $writable): self
    {
        $this->writable = $writable;
        return $this;
    }

    /**
     * Get the value of readonly
     *
     * @return array<string>
     */
    public function getReadonly(): array
    {
        return $this->readonly;
    }

    /**
     * Set the value of readonly
     *
     * @param array<string> $readonly
     *
     * @return self
     */
    public function setReadonly(array $readonly): self
    {
        $this->readonly = $readonly;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'writable' => $this->writable,
            'readonly' => $this->readonly,
        ];
    }

    /**
     * @param array<mixed>|self $data
     * @return self
     */
    public static function from(array|self $data): self
    {
        if ($data instanceof self) {
            return $data;
        }
        return (new self())
            ->setWritable($data['writable'])
            ->setReadonly($data['readonly']);
    }
}
