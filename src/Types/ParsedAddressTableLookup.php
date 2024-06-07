<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Types;

class ParsedAddressTableLookup
{
    /**
     * @var string
     */
    private string $accountKey;

    /**
     * @var array<int>
     */
    private array $writableIndexes;

    /**
     * @var array<int>
     */
    private array $readonlyIndexes;

    /**
     * Get the value of accountKey
     *
     * @return string
     */
    public function getAccountKey(): string
    {
        return $this->accountKey;
    }

    /**
     * Set the value of accountKey
     *
     * @param string $accountKey
     *
     * @return self
     */
    public function setAccountKey(string $accountKey): self
    {
        $this->accountKey = $accountKey;
        return $this;
    }

    /**
     * Get the value of writableIndexes
     *
     * @return array<int>
     */
    public function getWritableIndexes(): array
    {
        return $this->writableIndexes;
    }

    /**
     * Set the value of writableIndexes
     *
     * @param array<int> $writableIndexes
     *
     * @return self
     */
    public function setWritableIndexes(array $writableIndexes): self
    {
        $this->writableIndexes = $writableIndexes;
        return $this;
    }

    /**
     * Get the value of readonlyIndexes
     *
     * @return array<int>
     */
    public function getReadonlyIndexes(): array
    {
        return $this->readonlyIndexes;
    }

    /**
     * Set the value of readonlyIndexes
     *
     * @param array<int> $readonlyIndexes
     *
     * @return self
     */
    public function setReadonlyIndexes(array $readonlyIndexes): self
    {
        $this->readonlyIndexes = $readonlyIndexes;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'accountKey' => $this->accountKey,
            'writableIndexes' => $this->writableIndexes,
            'readonlyIndexes' => $this->readonlyIndexes,
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
            ->setAccountKey($data['accountKey'])
            ->setWritableIndexes($data['writableIndexes'])
            ->setReadonlyIndexes($data['readonlyIndexes']);
    }
}
