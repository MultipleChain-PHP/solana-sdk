<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Parsers\Types;

use MultipleChain\SolanaSDK\PublicKey;

class ParsedInstruction
{
    /**
     * @var string
     */
    private string $program;

    /**
     * @var array<string>
     */
    private array $accounts;

    /**
     * @var string|null
     */
    private ?string $data;

    /**
     * @var int|null
     */
    private ?int $stackHeight;

    /**
     * @var PublicKey
     */
    private PublicKey $programId;

    /**
     * @var mixed
     */
    private mixed $parsed;

    /**
     * Get the value of program
     *
     * @return string
     */
    public function getProgram(): string
    {
        return $this->program;
    }

    /**
     * Set the value of program
     *
     * @param string $program
     *
     * @return self
     */
    public function setProgram(string $program): self
    {
        $this->program = $program;
        return $this;
    }

    /**
     * Get the value of accounts
     *
     * @return array<string>
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

    /**
     * Set the value of accounts
     *
     * @param array<string> $accounts
     *
     * @return self
     */
    public function setAccounts(array $accounts): self
    {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * Get the value of data
     *
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @param string|null $data
     *
     * @return self
     */
    public function setData(?string $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get the value of stackHeight
     *
     * @return int|null
     */
    public function getStackHeight(): ?int
    {
        return $this->stackHeight;
    }

    /**
     * Set the value of stackHeight
     *
     * @param int|null $stackHeight
     *
     * @return self
     */
    public function setStackHeight(?int $stackHeight): self
    {
        $this->stackHeight = $stackHeight;
        return $this;
    }

    /**
     * Get the value of programId
     *
     * @return PublicKey
     */
    public function getProgramId(): PublicKey
    {
        return $this->programId;
    }

    /**
     * Set the value of programId
     *
     * @param PublicKey $programId
     *
     * @return self
     */
    public function setProgramId(PublicKey $programId): self
    {
        $this->programId = $programId;
        return $this;
    }

    /**
     * Get the value of parsed
     *
     * @return mixed
     */
    public function getParsed(): mixed
    {
        return $this->parsed;
    }

    /**
     * Set the value of parsed
     *
     * @param mixed $parsed
     *
     * @return self
     */
    public function setParsed(mixed $parsed): self
    {
        $this->parsed = $parsed;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'parsed' => $this->parsed,
            'program' => $this->program,
            'accounts' => $this->accounts,
            'stackHeight' => $this->stackHeight,
            'programId' => $this->programId->toString(),
        ];
    }

    /**
     * @param array<mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return (new self())
            ->setData($data['data'])
            ->setParsed($data['parsed'])
            ->setProgram($data['program'])
            ->setAccounts($data['accounts'])
            ->setStackHeight($data['stackHeight'])
            ->setProgramId(new PublicKey($data['programId']));
    }
}
