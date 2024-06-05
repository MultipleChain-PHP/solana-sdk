<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Util;

class ShortVec
{
    /**
     * @param mixed $buffer
     * @return array<int> list($length, $size)
     */
    public static function decodeLength(mixed $buffer): array
    {
        $buffer = Buffer::from($buffer)->toArray();

        $len = 0;
        $size = 0;
        while ($size < sizeof($buffer)) {
            $elem = $buffer[$size];
            $len |= ($elem & 0x7F) << ($size * 7);
            $size++;
            if (0 == ($elem & 0x80)) {
                break;
            }
        }
        return [$len, $size];
    }

    /**
     * @param int $length
     * @return array<int>
     */
    public static function encodeLength(int $length): array
    {
        $elemList = [];
        $remLen = $length;

        for (;;) {
            $elem = $remLen & 0x7f;
            $remLen >>= 7;
            if (! $remLen) {
                array_push($elemList, $elem);
                break;
            }
            $elem |= 0x80;
            array_push($elemList, $elem);
        }

        return $elemList;
    }
}
