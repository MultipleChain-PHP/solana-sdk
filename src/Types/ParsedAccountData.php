<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Types;

class ParsedAccountData
{
    /**
     * @var string|null
     */
    private ?string $program;

    /**
     * @var int|null
     */
    private ?int $space;

    /**
     * @var mixed
     */
    private mixed $data;

    /**
     * @param string|null $program
     * @return self
     */
    public function setProgram(?string $program): self
    {
        $this->program = $program;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProgram(): ?string
    {
        return $this->program;
    }

    /**
     * @return mixed
     */
    public function getParsed(): mixed
    {
        return is_array($this->data) ? ($this->data['parsed'] ?? null) : null;
    }

    /**
     * @param int|null $space
     * @return self
     */
    public function setSpace(?int $space): self
    {
        $this->space = $space;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSpace(): ?int
    {
        return $this->space;
    }

    /**
     * @param mixed $data
     * @return self
     */
    public function setData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'program' => $this->program,
            'space' => $this->space,
            'data' => $this->data,
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
            ->setProgram($data['program'] ?? null)
            ->setSpace($data['space'] ?? null)
            ->setData($data);
    }
}
