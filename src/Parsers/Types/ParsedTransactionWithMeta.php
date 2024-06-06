<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Parsers\Types;

class ParsedTransactionWithMeta
{
    /**
     * @var int
     */
    private int $slot;

    /**
     * @var ParsedTransaction
     */
    private ParsedTransaction $transaction;

    /**
     * @var ParsedTransactionMeta|null
     */
    private ?ParsedTransactionMeta $meta;

    /**
     * @var int|null
     */
    private ?int $blockTime;

    /**
     * @var string|int
     */
    private string|int $version;

    /**
     * Get the value of slot
     *
     * @return int
     */
    public function getSlot(): int
    {
        return $this->slot;
    }

    /**
     * Set the value of slot
     *
     * @param int $slot
     *
     * @return self
     */
    public function setSlot(int $slot): self
    {
        $this->slot = $slot;
        return $this;
    }

    /**
     * Get the value of transaction
     *
     * @return ParsedTransaction
     */
    public function getTransaction(): ParsedTransaction
    {
        return $this->transaction;
    }

    /**
     * Set the value of transaction
     *
     * @param ParsedTransaction $transaction
     *
     * @return self
     */
    public function setTransaction(ParsedTransaction $transaction): self
    {
        $this->transaction = $transaction;
        return $this;
    }

    /**
     * Get the value of meta
     *
     * @return ?ParsedTransactionMeta
     */
    public function getMeta(): ?ParsedTransactionMeta
    {
        return $this->meta;
    }

    /**
     * Set the value of meta
     *
     * @param ?ParsedTransactionMeta $meta
     *
     * @return self
     */
    public function setMeta(?ParsedTransactionMeta $meta): self
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * Get the value of blockTime
     *
     * @return ?int
     */
    public function getBlockTime(): ?int
    {
        return $this->blockTime;
    }

    /**
     * Set the value of blockTime
     *
     * @param ?int $blockTime
     *
     * @return self
     */
    public function setBlockTime(?int $blockTime): self
    {
        $this->blockTime = $blockTime;
        return $this;
    }

    /**
     * Get the value of version
     *
     * @return string|int
     */
    public function getVersion(): string|int
    {
        return $this->version;
    }

    /**
     * Set the value of version
     *
     * @param string|int $version
     *
     * @return self
     */
    public function setVersion(string|int $version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            "slot" => $this->slot,
            "version" => $this->version,
            "blockTime" => $this->blockTime,
            "transaction" => $this->transaction->toArray(),
            "meta" => $this->meta ? $this->meta->toArray() : null
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
            ->setSlot($data['slot'])
            ->setVersion($data['version'] ?? 0)
            ->setBlockTime($data['blockTime'] ?? null)
            ->setTransaction(ParsedTransaction::from($data['transaction']))
            ->setMeta($data['meta'] ? ParsedTransactionMeta::from($data['meta']) : null);
    }
}
