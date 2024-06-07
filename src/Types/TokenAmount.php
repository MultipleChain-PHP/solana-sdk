<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Types;

class TokenAmount
{
    /**
     * @var string
     */
    private string $amount;

    /**
     * @var int
     */
    private int $decimals;

    /**
     * @var float|null
     */
    private ?float $uiAmount;

    /**
     * @var string|null
     */
    private ?string $uiAmountString;

    /**
     * Get the value of amount
     *
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * Set the value of amount
     *
     * @param string $amount
     *
     * @return self
     */
    public function setAmount(string $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Get the value of decimals
     *
     * @return int
     */
    public function getDecimals(): int
    {
        return $this->decimals;
    }

    /**
     * Set the value of decimals
     *
     * @param int $decimals
     *
     * @return self
     */
    public function setDecimals(int $decimals): self
    {
        $this->decimals = $decimals;
        return $this;
    }

    /**
     * Get the value of uiAmount
     *
     * @return float|null
     */
    public function getUiAmount(): ?float
    {
        return $this->uiAmount;
    }

    /**
     * Set the value of uiAmount
     *
     * @param float|null $uiAmount
     *
     * @return self
     */
    public function setUiAmount(?float $uiAmount): self
    {
        $this->uiAmount = $uiAmount;
        return $this;
    }

    /**
     * Get the value of uiAmountString
     *
     * @return string|null
     */
    public function getUiAmountString(): ?string
    {
        return $this->uiAmountString;
    }

    /**
     * Set the value of uiAmountString
     *
     * @param string|null $uiAmountString
     *
     * @return self
     */
    public function setUiAmountString(?string $uiAmountString): self
    {
        $this->uiAmountString = $uiAmountString;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'decimals' => $this->decimals,
            'uiAmount' => $this->uiAmount,
            'uiAmountString' => $this->uiAmountString,
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
            ->setAmount($data['amount'])
            ->setDecimals($data['decimals'])
            ->setUiAmount($data['uiAmount'] ?? null)
            ->setUiAmountString($data['uiAmountString'] ?? null);
    }
}
