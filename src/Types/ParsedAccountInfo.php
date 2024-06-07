<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Types;

use MultipleChain\SolanaSDK\PublicKey;

class ParsedAccountInfo
{
    /**
     * @var bool
     */
    private bool $executable;

    /**
     * @var PublicKey
     */
    private PublicKey $owner;

    /**
     * @var int
     */
    private int $lamports;

    /**
     * @var ParsedAccountData
     */
    public ParsedAccountData $data;

    /**
     * @var float|null
     */
    private ?float $rentEpoch;

    /**
     * @var int|null
     */
    private ?int $space;

    /**
     * Get the value of executable
     *
     * @return bool
     */
    public function getExecutable(): bool
    {
        return $this->executable;
    }

    /**
     * Set the value of executable
     *
     * @param bool $executable
     *
     * @return self
     */
    public function setExecutable(bool $executable): self
    {
        $this->executable = $executable;
        return $this;
    }

    /**
     * Get the value of owner
     *
     * @return PublicKey
     */
    public function getOwner(): PublicKey
    {
        return $this->owner;
    }

    /**
     * Set the value of owner
     *
     * @param PublicKey $owner
     *
     * @return self
     */
    public function setOwner(PublicKey $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get the value of lamports
     *
     * @return int
     */
    public function getLamports(): int
    {
        return $this->lamports;
    }

    /**
     * Set the value of lamports
     *
     * @param int $lamports
     *
     * @return self
     */
    public function setLamports(int $lamports): self
    {
        $this->lamports = $lamports;
        return $this;
    }

    /**
     * Get the value of data
     *
     * @return ParsedAccountData
     */
    public function getData(): ParsedAccountData
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @param ParsedAccountData $data
     *
     * @return self
     */
    public function setData(ParsedAccountData $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get the value of rentEpoch
     *
     * @return float|null
     */
    public function getRentEpoch(): ?float
    {
        return $this->rentEpoch;
    }

    /**
     * Set the value of rentEpoch
     *
     * @param float|null $rentEpoch
     *
     * @return self
     */
    public function setRentEpoch(?float $rentEpoch): self
    {
        $this->rentEpoch = $rentEpoch;
        return $this;
    }

    /**
     * Get the value of space
     *
     * @return int|null
     */
    public function getSpace(): ?int
    {
        return $this->space;
    }

    /**
     * Set the value of space
     *
     * @param int|null $space
     *
     * @return self
     */
    public function setSpace(?int $space): self
    {
        $this->space = $space;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'space' => $this->space,
            'lamports' => $this->lamports,
            'rentEpoch' => $this->rentEpoch,
            'executable' => $this->executable,
            'owner' => $this->owner->toString(),
            'data' => $this->data->toArray()
        ];
    }

    /**
     * @param array<mixed>|ParsedAccountInfo $data
     * @return ParsedAccountInfo
     */
    public static function from(array|ParsedAccountInfo $data): ParsedAccountInfo
    {
        if ($data instanceof ParsedAccountInfo) {
            return $data;
        }

        return (new ParsedAccountInfo())
            ->setLamports($data['lamports'])
            ->setSpace($data['space'] ?? null)
            ->setExecutable($data['executable'])
            ->setRentEpoch($data['rentEpoch'] ?? null)
            ->setData(ParsedAccountData::from($data['data'] ?? []))
            ->setOwner(
                $data['owner'] instanceof PublicKey ? $data['owner'] : new PublicKey($data['owner'])
            );
    }
}
