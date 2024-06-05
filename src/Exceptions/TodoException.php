<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Exceptions;

use Exception;
use Throwable;

class TodoException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            $message . " | Help is appreciated: https://github.com/multiplechain/solana-sdk",
            $code,
            $previous
        );
    }
}
