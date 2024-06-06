<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Types;

use MultipleChain\SolanaSDK\PublicKey;

class ParsedTokenAccount
{
    /**
     * @var PublicKey
     */
    private PublicKey $pubkey;

    /**
     * @var ParsedAccountInfo
     */
    private ParsedAccountInfo $account;

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
     * Get the value of account
     *
     * @return ParsedAccountInfo
     */
    public function getAccount(): ParsedAccountInfo
    {
        return $this->account;
    }

    /**
     * Set the value of account
     *
     * @param ParsedAccountInfo $account
     *
     * @return self
     */
    public function setAccount(ParsedAccountInfo $account): self
    {
        $this->account = $account;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'pubkey' => $this->pubkey->toString(),
            'account' => $this->account->toArray(),
        ];
    }

    /**
     * @param array<mixed> $data
     * @return self
     */
    public static function from(array $data): self
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('Empty data');
        }

        return (new self())
            ->setPubkey(
                $data['pubkey'] instanceof PublicKey ? $data['pubkey'] : new PublicKey($data['pubkey'])
            )
            ->setAccount(ParsedAccountInfo::from($data['account']));
    }
}
