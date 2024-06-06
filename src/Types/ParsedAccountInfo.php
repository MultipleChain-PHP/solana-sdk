<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Types;

use MultipleChain\SolanaSDK\PublicKey;
use MultipleChain\SolanaSDK\Util\Buffer;

class ParsedAccountInfo
{
    /**
     * @var bool
     */
    public bool $executable;

    /**
     * @var PublicKey
     */
    public PublicKey $owner;

    /**
     * @var int
     */
    public int $lamports;

    /**
     * @var ParsedAccountData|Buffer
     */
    public ParsedAccountData|Buffer $data;

    /**
     * @var float|null
     */
    public ?float $rentEpoch;


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
     * @return ParsedAccountData|Buffer
     */
    public function getData(): ParsedAccountData|Buffer
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @param ParsedAccountData|Buffer $data
     *
     * @return self
     */
    public function setData(ParsedAccountData|Buffer $data): self
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
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'lamports' => $this->lamports,
            'rentEpoch' => $this->rentEpoch,
            'executable' => $this->executable,
            'owner' => $this->owner->toString(),
            'data' => $this->data instanceof ParsedAccountData ? $this->data->toArray() : $this->data->toString(),
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
            ->setRentEpoch($data['rentEpoch'])
            ->setExecutable($data['executable'])
            ->setOwner(
                $data['owner'] instanceof PublicKey ? $data['owner'] : new PublicKey($data['owner'])
            )
            ->setData(
                is_array($data['data']) ? ParsedAccountData::from($data['data']) : Buffer::fromBase58($data['data'])
            );
    }
}
