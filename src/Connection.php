<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK;

use Illuminate\Http\Client\Response;
use MultipleChain\SolanaSDK\Util\Signer;
use MultipleChain\SolanaSDK\Util\Commitment;
use MultipleChain\SolanaSDK\Types\ParsedAccountInfo;
use MultipleChain\SolanaSDK\Types\ParsedTokenAccount;
use MultipleChain\SolanaSDK\Types\ParsedTransactionWithMeta;

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
        return (array) $this->client->call('getLatestBlockhash', [[
            "commitment" => $this->getCommitmentString($commitment),
        ]])['value'];
    }

    /**
     * @param string $blockhash
     * @param Commitment|null $commitment
     * @return bool
     */
    public function isBlockhashValid(string $blockhash, ?Commitment $commitment = null): bool
    {
        return (bool) $this->client->call('isBlockhashValid', [$blockhash, [
            "commitment" => $this->getCommitmentString($commitment),
        ]])['value'];
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
     * @param Commitment|null $commitment
     * @return array<mixed>|null
     */
    public function getAccountInfo(string $pubKey, ?Commitment $commitment = null): ?array
    {
        $accountResponse = $this->client->call('getAccountInfo', [$pubKey, [
            "commitment" => $this->getCommitmentString($commitment),
        ]])['value'];

        if (!$accountResponse) {
            return null;
        }

        return $accountResponse;
    }

    /**
     * @param string $pubKey
     * @return array<mixed>
     */
    public function getTokenLargestAccounts(string $pubKey): array
    {
        return $this->client->call('getTokenLargestAccounts', [$pubKey])['value'];
    }

    /**
     * @param string $pubKey
     * @return array<mixed>
     */
    public function getTokenSupply(string $pubKey): array
    {
        return $this->client->call('getTokenSupply', [$pubKey])['value'];
    }

    /**
     * @param string $pubKey
     * @return int
     */
    public function getBalance(string $pubKey): int
    {
        return $this->client->call('getBalance', [$pubKey])['value'];
    }

    /**
     * @return int
     */
    public function getSlot(): int
    {
        return $this->client->call('getSlot');
    }

    /**
     * @param string $method
     * @param array<mixed> $params
     * @return array<mixed>
     */
    public function call(string $method, array $params = []): mixed
    {
        return $this->client->call($method, $params);
    }

    /**
     * Confirm a transaction by signature.
     *
     * @param string $signature
     * @param Commitment|null $commitment
     * @return bool
     */
    public function confirmTransaction(string $signature, ?Commitment $commitment = null): bool
    {
        $result = $this->getSignatureStatus($signature, $commitment);
        while ('finalized' !== $result['confirmationStatus']) {
            $result = $this->getSignatureStatus($signature, $commitment);
        }
        return true;
    }

    /**
     * @param Commitment|null $commitment
     * @return int
     */
    public function getBlockHeight(?Commitment $commitment = null): int
    {
        return $this->client->call('getBlockHeight', [[
            "commitment" => $this->getCommitmentString($commitment),
        ]]);
    }

    /**
     * @param string $signature
     * @param Commitment|null $commitment
     * @return array<mixed>
     */
    public function getSignatureStatus(string $signature, ?Commitment $commitment = null): array
    {
        return $this->client->call('getSignatureStatuses', [[$signature], [
            "searchTransactionHistory" => true
        ]])['value'][0];
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
        return $this->client->call('getTransaction', [$transactionSignature, [
            "maxSupportedTransactionVersion" => 0,
            "commitment" => $this->getCommitmentString($commitment),
        ]]);
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
     * @param Commitment|null $commitment
     * @return ParsedAccountInfo
     */
    public function getParsedAccountInfo(string $pubKey, ?Commitment $commitment = null): ?ParsedAccountInfo
    {
        $accountResponse = $this->client->call('getAccountInfo', [$pubKey, [
            "encoding" => "jsonParsed",
            "commitment" => $this->getCommitmentString($commitment),
        ]])['value'];

        if (!$accountResponse) {
            return null;
        }

        return ParsedAccountInfo::from($accountResponse);
    }

    /**
     * @param string $pubKey
     * @param array<mixed> $params
     * @param Commitment|null $commitment
     * @return array<ParsedTokenAccount>
     */
    public function getParsedTokenAccountsByOwner(
        string $pubKey,
        array $params = [],
        ?Commitment $commitment = null
    ): array {
        $result = $this->client->call('getTokenAccountsByOwner', [$pubKey, $params, [
            "encoding" => "jsonParsed",
            "commitment" => $this->getCommitmentString($commitment),
        ]]);

        $accounts = [];
        foreach ($result['value'] as $account) {
            $accounts[] = ParsedTokenAccount::from($account);
        }

        return $accounts;
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
