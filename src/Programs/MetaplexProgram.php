<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Programs;

use MultipleChain\SolanaSDK\Program;
use MultipleChain\SolanaSDK\PublicKey;
use MultipleChain\SolanaSDK\Util\Buffer;

class MetaplexProgram extends Program
{
    public const METAPLEX_PROGRAM_ID = 'metaqbxxUerdq28cj1RbAWkYQm3ybzjb6a8bt518x1s';

    /**
     * @param string $pubKey
     * @return array<mixed>|mixed
     */
    public function getProgramAccounts(string $pubKey): mixed
    {
        $magicOffsetNumber = 326; // 🤷‍♂️

        return $this->client->call('getProgramAccounts', [
            self::METAPLEX_PROGRAM_ID,
            [
                'encoding' => 'base64',
                'filters' => [
                    [
                        'memcmp' => [
                            'bytes' => $pubKey,
                            'offset' => $magicOffsetNumber,
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @param PublicKey $mint
     * @return PublicKey
     */
    public static function findMetadataAccount(PublicKey $mint): PublicKey
    {
        $metaplex = new PublicKey(self::METAPLEX_PROGRAM_ID);
        /**
         * @var PublicKey $metadataAccount
         */
        list($metadataAccount, ) = PublicKey::findProgramAddress(
            [
                Buffer::from('metadata'),
                $metaplex->toBuffer(),
                $mint->toBuffer()
            ],
            $metaplex
        );

        return $metadataAccount;
    }

    /**
     * @param string $base64Encoded
     * @return array<mixed>
     */
    public static function unpackMetadata(string $base64Encoded): array
    {
        $base58 = new \StephenHill\Base58();
        $data = base64_decode($base64Encoded);

        if (4 !== ord($data[0])) {
            throw new \Exception("Invalid data format");
        }

        $i = 1;

        $sourceAccount = $base58->encode(substr($data, $i, 32));
        $i += 32;

        $mintAccount = $base58->encode(substr($data, $i, 32));
        $i += 32;

        $nameLen = unpack('V', substr($data, $i, 4))[1] ?? 0;
        $i += 4;
        $name = substr($data, $i, $nameLen);
        $i += $nameLen;

        $symbolLen = unpack('V', substr($data, $i, 4))[1] ?? 0;
        $i += 4;
        $symbol = substr($data, $i, $symbolLen);
        $i += $symbolLen;

        $uriLen = unpack('V', substr($data, $i, 4))[1] ?? 0;
        $i += 4;
        $uri = substr($data, $i, $uriLen);
        $i += $uriLen;

        $fee = unpack('v', substr($data, $i, 2))[1] ?? 0;
        $i += 2;

        $hasCreator = ord($data[$i]);
        $i += 1;

        $creators = [];
        $verified = [];
        $share = [];
        if ($hasCreator) {
            $creatorLen = unpack('V', substr($data, $i, 4))[1] ?? 0;
            $i += 4;
            for ($j = 0; $j < $creatorLen; $j++) {
                $creator = $base58->encode(substr($data, $i, 32));
                $creators[] = $creator;
                $i += 32;
                $verified[] = ord($data[$i]);
                $i += 1;
                $share[] = ord($data[$i]);
                $i += 1;
            }
        }

        $primarySaleHappened = boolval(ord($data[$i]));
        $i += 1;
        $isMutable = boolval(ord($data[$i]));

        return [
            "updateAuthority" => $sourceAccount,
            "mint" => $mintAccount,
            "data" => [
                "share" => $share,
                "creators" => $creators,
                "verified" => $verified,
                "uri" => rtrim($uri, "\0"),
                "name" => rtrim($name, "\0"),
                "symbol" => rtrim($symbol, "\0"),
                "sellerFeeBasisPoints" => $fee,
            ],
            "primarySaleHappened" => $primarySaleHappened,
            "isMutable" => $isMutable,
        ];
    }
}
