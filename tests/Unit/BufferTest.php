<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MultipleChain\SolanaSDK\Keypair;
use MultipleChain\SolanaSDK\Util\Buffer;
use MultipleChain\SolanaSDK\Programs\SystemProgram;

class BufferTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_buffer_push_fixed_length(): void
    {
        $lamports = 4;
        $space = 6;
        $programId = Keypair::generate()->getPublicKey();

        $rawCreateAccountBinary = [
            // uint32
            ...unpack("C*", pack("V", SystemProgram::PROGRAM_INDEX_CREATE_ACCOUNT)),
            // int64
            ...unpack("C*", pack("P", $lamports)),
            // int64
            ...unpack("C*", pack("P", $space)),
            ...$programId->toBytes(),
        ];

        $bufferable = Buffer::from()
            ->push(
                Buffer::from(SystemProgram::PROGRAM_INDEX_CREATE_ACCOUNT, Buffer::TYPE_INT, false)
            )
            ->push(
                Buffer::from($lamports, Buffer::TYPE_LONG, false)
            )
            ->push(
                Buffer::from($space, Buffer::TYPE_LONG, false)
            )
            ->push($programId)
        ;

        $this->assertEquals($rawCreateAccountBinary, $bufferable->toArray());
    }
}
