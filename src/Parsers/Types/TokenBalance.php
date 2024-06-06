<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Parsers\Types;

class TokenBalance
{
    /**
     * @var int
     */
    private int $accountIndex;

    /**
     * @var string
     */
    private string $mint;

    /**
     * @var string|null
     */
    private ?string $owner;

    /**
     * @var TokenAmount
     */
    private TokenAmount $uiTokenAmount;

    /**
     * Get the value of accountIndex
     *
     * @return int
     */
    public function getAccountIndex(): int
    {
        return $this->accountIndex;
    }

    /**
     * Set the value of accountIndex
     *
     * @param int $accountIndex
     *
     * @return self
     */
    public function setAccountIndex(int $accountIndex): self
    {
        $this->accountIndex = $accountIndex;
        return $this;
    }

    /**
     * Get the value of mint
     *
     * @return string
     */
    public function getMint(): string
    {
        return $this->mint;
    }

    /**
     * Set the value of mint
     *
     * @param string $mint
     *
     * @return self
     */
    public function setMint(string $mint): self
    {
        $this->mint = $mint;
        return $this;
    }

    /**
     * Get the value of owner
     *
     * @return string|null
     */
    public function getOwner(): ?string
    {
        return $this->owner;
    }

    /**
     * Set the value of owner
     *
     * @param string|null $owner
     *
     * @return self
     */
    public function setOwner(?string $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * Get the value of uiTokenAmount
     *
     * @return TokenAmount
     */
    public function getUiTokenAmount(): TokenAmount
    {
        return $this->uiTokenAmount;
    }

    /**
     * Set the value of uiTokenAmount
     *
     * @param TokenAmount $uiTokenAmount
     *
     * @return self
     */
    public function setUiTokenAmount(TokenAmount $uiTokenAmount): self
    {
        $this->uiTokenAmount = $uiTokenAmount;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'mint' => $this->mint,
            'owner' => $this->owner,
            'accountIndex' => $this->accountIndex,
            'uiTokenAmount' => $this->uiTokenAmount->toArray(),
        ];
    }

    /**
     * @param array<mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return (new self())
            ->setMint($data['mint'])
            ->setOwner($data['owner'])
            ->setAccountIndex($data['accountIndex'])
            ->setUiTokenAmount(TokenAmount::fromArray($data['uiTokenAmount']));
    }
}
