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
    private array $readable;

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
     * Get the value of readable
     *
     * @return array<string>
     */
    public function getReadable(): array
    {
        return $this->readable;
    }

    /**
     * Set the value of readable
     *
     * @param array<string> $readable
     *
     * @return self
     */
    public function setReadable(array $readable): self
    {
        $this->readable = $readable;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'writable' => $this->writable,
            'readable' => $this->readable,
        ];
    }

    /**
     * @param array<mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return (new self())
            ->setWritable($data['writable'])
            ->setReadable($data['readable']);
    }
}
