<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Util;

use MultipleChain\SolanaSDK\Exceptions\InputValidationException;

class Commitment
{
    private const FINALIZED = 'finalized';
    private const CONFIRMED = 'confirmed';
    private const PROCESSED = 'processed';

    protected string $commitmentLevel;

    /**
     * @param string $commitmentLevel
     */
    public function __construct(string $commitmentLevel)
    {
        if (
            ! in_array($commitmentLevel, [
            self::FINALIZED,
            self::CONFIRMED,
            self::PROCESSED,
            ])
        ) {
            throw new InputValidationException('Invalid commitment level.');
        }

        $this->commitmentLevel = $commitmentLevel;
    }

    /**
     * @return Commitment
     */
    public static function finalized(): Commitment
    {
        return new Commitment(self::FINALIZED);
    }

    /**
     * @return Commitment
     */
    public static function confirmed(): Commitment
    {
        return new Commitment(self::CONFIRMED);
    }

    /**
     * @return Commitment
     */
    public static function processed(): Commitment
    {
        return new Commitment(self::PROCESSED);
    }

    /**
     * @param string $commitmentLevel
     * @return Commitment
     */
    public static function fromString(string $commitmentLevel): Commitment
    {
        return new Commitment($commitmentLevel);
    }

    /**
     * @param Commitment $commitment1
     * @param Commitment $commitment2
     * @return bool
     */
    public static function equals(Commitment $commitment1, Commitment $commitment2): bool
    {
        return $commitment1->__toString() === $commitment2->__toString();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->commitmentLevel;
    }
}
