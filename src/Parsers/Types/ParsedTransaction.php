<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Parsers\Types;

class ParsedTransaction
{
    /**
     * @var array<string>
     */
    private array $signatures;

    /**
     * @var ParsedMessage
     */
    private ParsedMessage $message;

    /**
     * Get the value of signatures
     *
     * @return array<string>
     */
    public function getSignatures(): array
    {
        return $this->signatures;
    }

    /**
     * Set the value of signatures
     *
     * @param array<string> $signatures
     *
     * @return self
     */
    public function setSignatures(array $signatures): self
    {
        $this->signatures = $signatures;
        return $this;
    }

    /**
     * Get the value of message
     *
     * @return ParsedMessage
     */
    public function getMessage(): ParsedMessage
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @param ParsedMessage $message
     *
     * @return self
     */
    public function setMessage(ParsedMessage $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'signatures' => $this->signatures,
            'message' => $this->message->toArray(),
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
            ->setSignatures($data['signatures'])
            ->setMessage(ParsedMessage::from($data['message']));
    }
}
