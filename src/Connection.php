<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK;

use Illuminate\Http\Client\Response;
use MultipleChain\SolanaSDK\Util\Signer;
use MultipleChain\SolanaSDK\Util\Commitment;
use MultipleChain\SolanaSDK\Types\ParsedAccountInfo;
use MultipleChain\SolanaSDK\Types\ParsedTransactionWithMeta;
use MultipleChain\SolanaSDK\Exceptions\AccountNotFoundException;

class Connection extends Program
{
    /**
     * @param SolanaRpcClient $client
     */
    public function __construct(SolanaRpcClient $client)
    {
        parent::__construct($client);
    }

    /**
     * @param Commitment|null $commitment
     * @return array<mixed>
     */
    public function getLatestBlockhash(?Commitment $commitment = null): array
    {
        $object = [
            "commitment" => $this->getCommitmentString($commitment),
        ];
        return (array) $this->client->call('getLatestBlockhash', [$object])['value'];
    }

    /**
     * @param string $blockhash
     * @param Commitment|null $commitment
     * @return bool
     */
    public function isBlockhashValid(string $blockhash, ?Commitment $commitment = null): bool
    {
        $object = [
            "commitment" => $this->getCommitmentString($commitment),
        ];
        return (bool) $this->client->call('isBlockhashValid', [$blockhash, $object])['value'];
    }

    /**
     * @param Commitment|null $commitment
     * @return string
     */
    public function getCommitmentString(?Commitment $commitment = null): string
    {
        if (null === $commitment) {
            return Commitment::finalized()->__toString();
        }
        return $commitment->__toString();
    }

    /**
     * @param string $pubKey
     * @return array<mixed>
     */
    public function getAccountInfo(string $pubKey): array
    {
        $accountResponse = $this->client->call('getAccountInfo', [$pubKey])['value'];

        if (!$accountResponse) {
            throw new AccountNotFoundException("API Error: Account {$pubKey} not found.");
        }

        return $accountResponse;
    }

    /**
     * @param string $pubKey
     * @return float
     */
    public function getBalance(string $pubKey): float
    {
        return $this->client->call('getBalance', [$pubKey])['value'];
    }

    /**
     * @param string $transactionSignature
     * @return array<mixed>|null
     */
    public function getConfirmedTransaction(string $transactionSignature): ?array
    {
        return $this->client->call('getConfirmedTransaction', [$transactionSignature]);
    }

    /**
     * NEW: This method is only available in solana-core v1.7 or newer.
     * > Please use getConfirmedTransaction for solana-core v1.6
     *
     * @param string $transactionSignature
     * @param Commitment|null $commitment
     * @return array<mixed>|null
     */
    public function getTransaction(string $transactionSignature, ?Commitment $commitment = null): ?array
    {
        $config = [
            "maxSupportedTransactionVersion" => 0,
            "commitment" => $this->getCommitmentString($commitment),
        ];

        return $this->client->call('getTransaction', [$transactionSignature, $config]);
    }

    /**
     * @deprecated
     * @param Commitment|null $commitment
     * @return array<mixed>
     * @throws Exceptions\GenericException|Exceptions\MethodNotFoundException|Exceptions\InvalidIdResponseException
     */
    public function getRecentBlockhash(?Commitment $commitment = null): array
    {
        return $this->client->call('getRecentBlockhash', array_filter([$commitment]))['value'];
    }

    /**
     * @param string $transactionSignature
     * @param Commitment|null $commitment
     * @return ParsedTransactionWithMeta|null
     */
    public function getParsedTransaction(
        string $transactionSignature,
        ?Commitment $commitment = null
    ): ?ParsedTransactionWithMeta {
        $result = $this->client->call('getTransaction', [$transactionSignature, [
            "encoding" => "jsonParsed",
            "maxSupportedTransactionVersion" => 0,
            "commitment" => $this->getCommitmentString($commitment),
        ]]);

        if (!$result) {
            return null;
        }

        return ParsedTransactionWithMeta::from($result);
    }

    /**
     * @param string $pubKey
     * @return ParsedAccountInfo
     */
    public function getParsedAccountInfo(string $pubKey): ?ParsedAccountInfo
    {
        $accountResponse = $this->client->call('getAccountInfo', [$pubKey, ["encoding" => "jsonParsed"]])['value'];

        if (!$accountResponse) {
            return null;
        }

        return ParsedAccountInfo::from($accountResponse);
    }

    /**
     * @param Transaction $transaction
     * @param array<Signer|Keypair> $signers
     * @param array<mixed> $params
     * @return array<mixed>|Response
     * @throws Exceptions\GenericException
     * @throws Exceptions\InvalidIdResponseException
     * @throws Exceptions\MethodNotFoundException
     */
    public function sendTransaction(Transaction $transaction, array $signers, array $params = []): array|Response
    {
        if (!$transaction->recentBlockhash) {
            $transaction->recentBlockhash = $this->getRecentBlockhash()['blockhash'];
        }

        $transaction->sign(...$signers);

        $rawBinaryString = $transaction->serialize(false);

        $hashString = sodium_bin2base64($rawBinaryString, SODIUM_BASE64_VARIANT_ORIGINAL);

        $sendParams = ['encoding' => 'base64', 'preflightCommitment' => 'confirmed'];

        if (!is_array($params)) {
            $params = [];
        }

        foreach ($params as $k => $v) {
            $sendParams[$k] = $v;
        }

        return $this->client->call('sendTransaction', [$hashString, $sendParams]);
    }

    /**
     * @param string $rawBinaryString
     * @param array<mixed> $params
     * @return string
     */
    public function sendRawTransaction(string $rawBinaryString, array $params = []): string
    {
        $hashString = sodium_bin2base64($rawBinaryString, SODIUM_BASE64_VARIANT_ORIGINAL);

        $sendParams = ['encoding' => 'base64', 'preflightCommitment' => 'confirmed'];

        if (!is_array($params)) {
            $params = [];
        }

        foreach ($params as $k => $v) {
            $sendParams[$k] = $v;
        }

        return $this->client->call('sendTransaction', [$hashString, $sendParams]);
    }

    /**
     * @param Transaction $transaction
     * @param array<Signer|Keypair> $signers
     * @param array<mixed> $params
     * @return array<mixed>|Response
     * @throws Exceptions\GenericException
     * @throws Exceptions\InvalidIdResponseException
     * @throws Exceptions\MethodNotFoundException
     */
    public function simulateTransaction(Transaction $transaction, array $signers, array $params = []): array|Response
    {
        $transaction->sign(...$signers);

        $rawBinaryString = $transaction->serialize(false);

        $hashString = sodium_bin2base64($rawBinaryString, SODIUM_BASE64_VARIANT_ORIGINAL);

        $sendParams = ['encoding' => 'base64', 'commitment' => 'confirmed', 'sigVerify' => true];
        if (!is_array($params)) {
            $params = [];
        }
        foreach ($params as $k => $v) {
            $sendParams[$k] = $v;
        }

        return $this->client->call('simulateTransaction', [$hashString, $sendParams]);
    }
}
