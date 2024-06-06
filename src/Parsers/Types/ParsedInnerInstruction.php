<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Parsers\Types;

class ParsedInnerInstruction
{
    /**
     * @var int
     */
    private int $index;

    /**
     * @var array<ParsedInstruction>
     */
    private array $instructions;

    /**
     * Get the value of index
     *
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * Set the value of index
     *
     * @param int $index
     *
     * @return self
     */
    public function setIndex(int $index): self
    {
        $this->index = $index;
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
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'index' => $this->index,
            'instructions' => array_map(
                fn (ParsedInstruction $instruction) => $instruction->toArray(),
                $this->instructions
            ),
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
            ->setIndex($data['index'])
            ->setInstructions(
                array_map(
                    fn (array|ParsedInstruction $instruction) => ParsedInstruction::from($instruction),
                    $data['instructions']
                )
            );
    }
}
