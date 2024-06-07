<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Types;

use MultipleChain\SolanaSDK\PublicKey;

class ParsedMessageAccount
{
    /**
     * @var PublicKey
     */
    private PublicKey $pubkey;

    /**
     * @var bool
     */
    private bool $signer;

    /**
     * @var bool
     */
    private bool $writable;

    /**
     * @var string|null
     */
    private ?string $source;

    /**
     * Get the value of pubkey
     *
     * @return PublicKey
     */
    public function getPubkey(): PublicKey
    {
        return $this->pubkey;
    }

    /**
     * Set the value of pubkey
     *
     * @param PublicKey $pubkey
     *
     * @return self
     */
    public function setPubkey(PublicKey $pubkey): self
    {
        $this->pubkey = $pubkey;
        return $this;
    }

    /**
     * Get the value of signer
     *
     * @return bool
     */
    public function getSigner(): bool
    {
        return $this->signer;
    }

    /**
     * Set the value of signer
     *
     * @param bool $signer
     *
     * @return self
     */
    public function setSigner(bool $signer): self
    {
        $this->signer = $signer;
        return $this;
    }

    /**
     * Get the value of writable
     *
     * @return bool
     */
    public function getWritable(): bool
    {
        return $this->writable;
    }

    /**
     * Set the value of writable
     *
     * @param bool $writable
     *
     * @return self
     */
    public function setWritable(bool $writable): self
    {
        $this->writable = $writable;
        return $this;
    }

    /**
     * Get the value of source
     *
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * Set the value of source
     *
     * @param string|null $source
     *
     * @return self
     */
    public function setSource(?string $source): self
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'source' => $this->source,
            'signer' => $this->signer,
            'writable' => $this->writable,
            'pubkey' => $this->pubkey->toString()
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
            ->setSigner($data['signer'])
            ->setWritable($data['writable'])
            ->setSource($data['source'] ?? null)
            ->setPubkey(
                $data['pubkey'] instanceof PublicKey
                    ? $data['pubkey']
                    : new PublicKey($data['pubkey'])
            );
    }
}
