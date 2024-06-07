<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Types;

class ParsedMessage
{
    /**
     * @var array<ParsedMessageAccount>
     */
    private array $accountKeys;

    /**
     * @var array<ParsedInstruction>
     */
    private array $instructions;

    /**
     * @var string
     */
    private string $recentBlockhash;

    /**
     * @var array<ParsedAddressTableLookup>|null
     */
    private ?array $addressTableLookups;

    /**
     * Get the value of accountKeys
     *
     * @return array<ParsedMessageAccount>
     */
    public function getAccountKeys(): array
    {
        return $this->accountKeys;
    }

    /**
     * Set the value of accountKeys
     *
     * @param array<ParsedMessageAccount> $accountKeys
     *
     * @return self
     */
    public function setAccountKeys(array $accountKeys): self
    {
        $this->accountKeys = $accountKeys;
        return $this;
    }

    /**
     * Get the value of instructions
     *
     * @return array<ParsedInstruction>
     */
    public function getInstructions(): array
    {
        return $this->instructions;
    }

    /**
     * Set the value of instructions
     *
     * @param array<ParsedInstruction> $instructions
     *
     * @return self
     */
    public function setInstructions(array $instructions): self
    {
        $this->instructions = $instructions;
        return $this;
    }

    /**
     * Get the value of recentBlockhash
     *
     * @return string
     */
    public function getRecentBlockhash(): string
    {
        return $this->recentBlockhash;
    }

    /**
     * Set the value of recentBlockhash
     *
     * @param string $recentBlockhash
     *
     * @return self
     */
    public function setRecentBlockhash(string $recentBlockhash): self
    {
        $this->recentBlockhash = $recentBlockhash;
        return $this;
    }

    /**
     * Get the value of addressTableLookups
     *
     * @return array<ParsedAddressTableLookup>|null
     */
    public function getAddressTableLookups(): ?array
    {
        return $this->addressTableLookups;
    }

    /**
     * Set the value of addressTableLookups
     * @param array<ParsedAddressTableLookup>|null $addressTableLookups
     * @return ParsedMessage
     */
    public function setAddressTableLookups(?array $addressTableLookups): ParsedMessage
    {
        $this->addressTableLookups = $addressTableLookups;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'accountKeys' => array_map(
                fn (ParsedMessageAccount $account) => $account->toArray(),
                $this->accountKeys
            ),
            'instructions' => array_map(
                fn (ParsedInstruction $instruction) => $instruction->toArray(),
                $this->instructions
            ),
            'recentBlockhash' => $this->recentBlockhash,
            'addressTableLookups' => $this->addressTableLookups ? array_map(
                fn (ParsedAddressTableLookup $addressTableLookup) => $addressTableLookup->toArray(),
                $this->addressTableLookups
            ) : null,
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
            ->setAccountKeys(array_map(
                fn (array|ParsedMessageAccount $account) => ParsedMessageAccount::from($account),
                $data['accountKeys']
            ))
            ->setInstructions(array_map(
                fn (array|ParsedInstruction $instruction) => ParsedInstruction::from($instruction),
                $data['instructions']
            ))
            ->setRecentBlockhash($data['recentBlockhash'])
            ->setAddressTableLookups(($data['addressTableLookups'] ?? false) ? array_map(
                fn (array|ParsedAddressTableLookup $addressTableLookup)
                => ParsedAddressTableLookup::from($addressTableLookup),
                $data['addressTableLookups']
            ) : null);
    }
}
