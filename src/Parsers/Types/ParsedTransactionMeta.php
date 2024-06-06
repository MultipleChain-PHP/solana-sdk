<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Parsers\Types;

class ParsedTransactionMeta
{
    /**
     * @var int
     */
    private int $fee;

    /**
     * @var array<ParsedInnerInstruction>|null
     */
    private array|null $innerInstructions;

    /**
     * @var array<int>
     */
    private array $preBalances;

    /**
     * @var array<int>
     */
    private array $postBalances;

    /**
     * @var array<string>|null
     */
    private ?array $logMessages;

    /**
     * @var array<TokenBalance>|null
     */
    private ?array $preTokenBalances;

    /**
     * @var array<TokenBalance>|null
     */
    private ?array $postTokenBalances;

    /**
     * @var array<mixed>|string|null
     */
    private array|string|null $err;

    /**
     * @var LoadedAddresses|null
     */
    private ?LoadedAddresses $loadedAddresses;

    /**
     * @var int|null
     */
    private ?int $computeUnitsConsumed;

    /**
     * Get the value of fee
     *
     * @return int
     */
    public function getFee(): int
    {
        return $this->fee;
    }

    /**
     * Set the value of fee
     *
     * @param int $fee
     *
     * @return self
     */
    public function setFee(int $fee): self
    {
        $this->fee = $fee;
        return $this;
    }

    /**
     * Get the value of innerInstructions
     *
     * @return array<ParsedInnerInstruction>|null
     */
    public function getInnerInstructions(): ?array
    {
        return $this->innerInstructions;
    }

    /**
     * Set the value of innerInstructions
     *
     * @param array<ParsedInnerInstruction>|null $innerInstructions
     *
     * @return self
     */
    public function setInnerInstructions(?array $innerInstructions): self
    {
        $this->innerInstructions = $innerInstructions;
        return $this;
    }

    /**
     * Get the value of preBalances
     *
     * @return array<int>
     */
    public function getPreBalances(): array
    {
        return $this->preBalances;
    }

    /**
     * Set the value of preBalances
     *
     * @param array<int> $preBalances
     *
     * @return self
     */
    public function setPreBalances(array $preBalances): self
    {
        $this->preBalances = $preBalances;
        return $this;
    }

    /**
     * Get the value of postBalances
     *
     * @return array<int>
     */
    public function getPostBalances(): array
    {
        return $this->postBalances;
    }

    /**
     * Set the value of postBalances
     *
     * @param array<int> $postBalances
     *
     * @return self
     */
    public function setPostBalances(array $postBalances): self
    {
        $this->postBalances = $postBalances;
        return $this;
    }

    /**
     * Get the value of logMessages
     *
     * @return array<string>|null
     */
    public function getLogMessages(): ?array
    {
        return $this->logMessages;
    }

    /**
     * Set the value of logMessages
     *
     * @param array<string>|null $logMessages
     *
     * @return self
     */
    public function setLogMessages(?array $logMessages): self
    {
        $this->logMessages = $logMessages;
        return $this;
    }

    /**
     * Get the value of preTokenBalances
     *
     * @return array<TokenBalance>|null
     */
    public function getPreTokenBalances(): ?array
    {
        return $this->preTokenBalances;
    }

    /**
     * Set the value of preTokenBalances
     *
     * @param array<TokenBalance>|null $preTokenBalances
     *
     * @return self
     */
    public function setPreTokenBalances(?array $preTokenBalances): self
    {
        $this->preTokenBalances = $preTokenBalances;
        return $this;
    }

    /**
     * Get the value of postTokenBalances
     *
     * @return array<TokenBalance>|null
     */
    public function getPostTokenBalances(): ?array
    {
        return $this->postTokenBalances;
    }

    /**
     * Set the value of postTokenBalances
     *
     * @param array<TokenBalance>|null $postTokenBalances
     *
     * @return self
     */
    public function setPostTokenBalances(?array $postTokenBalances): self
    {
        $this->postTokenBalances = $postTokenBalances;
        return $this;
    }

    /**
     * Get the value of err
     * @return array<mixed>|string|null
     */
    public function getErr(): array|string|null
    {
        return $this->err;
    }

    /**
     * Set the value of err
     * @param array<mixed>|string|null $err
     * @return self
     */
    public function setErr(array|string|null $err): self
    {
        $this->err = $err;
        return $this;
    }

    /**
     * Get the value of loadedAddresses
     * @return LoadedAddresses|null
     */
    public function getLoadedAddresses(): ?LoadedAddresses
    {
        return $this->loadedAddresses;
    }

    /**
     * Set the value of loadedAddresses
     * @param LoadedAddresses|null $loadedAddresses
     * @return self
     */
    public function setLoadedAddresses(?LoadedAddresses $loadedAddresses): self
    {
        $this->loadedAddresses = $loadedAddresses;
        return $this;
    }

    /**
     * Get the value of computeUnitsConsumed
     * @return int|null
     */
    public function getComputeUnitsConsumed(): ?int
    {
        return $this->computeUnitsConsumed;
    }

    /**
     * Set the value of computeUnitsConsumed
     * @param int|null $computeUnitsConsumed
     * @return self
     */
    public function setComputeUnitsConsumed(?int $computeUnitsConsumed): self
    {
        $this->computeUnitsConsumed = $computeUnitsConsumed;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'fee' => $this->fee,
            'err' => $this->err,
            'logMessages' => $this->logMessages,
            'preBalances' => $this->preBalances,
            'postBalances' => $this->postBalances,
            'computeUnitsConsumed' => $this->computeUnitsConsumed,
            'preTokenBalances' => $this->preTokenBalances ? array_map(
                fn (TokenBalance $tokenBalance) => $tokenBalance->toArray(),
                $this->preTokenBalances
            ) : null,
            'postTokenBalances' => $this->postTokenBalances ? array_map(
                fn (TokenBalance $tokenBalance) => $tokenBalance->toArray(),
                $this->postTokenBalances
            ) : null,
            'loadedAddresses' => $this->loadedAddresses?->toArray(),
            'innerInstructions' => $this->innerInstructions ? array_map(
                fn (ParsedInnerInstruction $innerInstructions) => $innerInstructions->toArray(),
                $this->innerInstructions
            ) : null,
        ];
    }

    /**
     * @param array<mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return (new self())
            ->setFee($data['fee'])
            ->setErr($data['err'])
            ->setLogMessages($data['logMessages'])
            ->setPreBalances($data['preBalances'])
            ->setPostBalances($data['postBalances'])
            ->setComputeUnitsConsumed($data['computeUnitsConsumed'])
            ->setPreTokenBalances($data['preTokenBalances'] ? array_map(
                fn (array $tokenBalance) => TokenBalance::fromArray($tokenBalance),
                $data['preTokenBalances']
            ) : null)
            ->setPostTokenBalances($data['postTokenBalances'] ? array_map(
                fn (array $tokenBalance) => TokenBalance::fromArray($tokenBalance),
                $data['postTokenBalances']
            ) : null)
            ->setLoadedAddresses($data['loadedAddresses'] ? LoadedAddresses::fromArray($data['loadedAddresses']) : null)
            ->setInnerInstructions($data['innerInstructions'] ? array_map(
                fn (array $addressTableLookup) => ParsedInnerInstruction::fromArray($addressTableLookup),
                $data['innerInstructions']
            ) : null);
    }
}
