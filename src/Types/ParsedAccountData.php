<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Types;

class ParsedAccountData
{
    /**
     * @var string
     */
    private string $program;

    /**
     * @var mixed
     */
    private mixed $parsed;

    /**
     * @var int
     */
    private int $space;

    /**
     * @param string $program
     * @return self
     */
    public function setProgram(string $program): self
    {
        $this->program = $program;
        return $this;
    }

    /**
     * @return string
     */
    public function getProgram(): string
    {
        return $this->program;
    }

    /**
     * @param mixed $parsed
     * @return self
     */
    public function setParsed(mixed $parsed): self
    {
        $this->parsed = $parsed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParsed(): mixed
    {
        return $this->parsed;
    }

    /**
     * @param int $space
     * @return self
     */
    public function setSpace(int $space): self
    {
        $this->space = $space;
        return $this;
    }

    /**
     * @return int
     */
    public function getSpace(): int
    {
        return $this->space;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'program' => $this->program,
            'parsed' => $this->parsed,
            'space' => $this->space,
        ];
    }

    /**
     * @param array<mixed>|ParsedAccountData $data
     * @return ParsedAccountData
     */
    public static function from(array|ParsedAccountData $data): ParsedAccountData
    {
        if ($data instanceof ParsedAccountData) {
            return $data;
        }

        return (new ParsedAccountData())
            ->setProgram($data['program'])
            ->setParsed($data['parsed'])
            ->setSpace($data['space']);
    }
}
