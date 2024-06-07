# Solana PHP SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/multiplechain/solana-sdk.svg?style=square)](https://packagist.org/packages/multiplechain/solana-sdk)
[![GitHub Tests Action Status](https://github.com/MultipleChain-PHP/solana-sdk/actions/workflows/test-and-code-check.yaml/badge.svg?branch=alpha)](https://github.com/MultipleChain-PHP/solana-sdk/actions/workflows/test-and-code-check.yaml?query=workflow%3Atest-and-code-check)

---

NOTE: This package has been forked to be developed at MultipleChain to provide infrastructure for Solana. We are not adding it internally to MultipleChain because MultipleChain is an interface that covers all Blockchain networks. But for those who just want to do something for Solana, we aim to make something that covers everything in Solana/Web3.js.

Forked from: [Sellix/solana-php-sdk](https://github.com/Sellix/solana-php-sdk)

---

Simple PHP SDK for Solana.

## Installation

You can install the package via composer:

```bash
composer require multiplechain/solana-sdk
```

## Usage

### Using the Solana simple client

You can use the `Connection` class for convenient access to API methods. Some are defined in the code:

```php
use MultipleChain\SolanaSDK\Connection;
use MultipleChain\SolanaSDK\SolanaRpcClient;

// Using a defined method
$sdk = new Connection(new SolanaRpcClient(SolanaRpcClient::MAINNET_ENDPOINT));
$accountInfo = $sdk->getAccountInfo('4fYNw3dojWmQ4dXtSGE9epjRGy9pFSx62YypT7avPYvA');
var_dump($accountInfo);
```

For all the possible methods, see the [API documentation](https://docs.solana.com/developing/clients/jsonrpc-api).

### Directly using the RPC client

The `Connection` class is just a light convenience layer on top of the RPC client. You can, if you want, use the client directly, which allows you to work with the full `Response` object:

```php
use MultipleChain\SolanaSDK\SolanaRpcClient;

$client = new SolanaRpcClient(SolanaRpcClient::MAINNET_ENDPOINT);
$accountInfoResponse = $client->call('getAccountInfo', ['4fYNw3dojWmQ4dXtSGE9epjRGy9pFSx62YypT7avPYvA']);
$accountInfoBody = $accountInfoResponse->json();
$accountInfoStatusCode = $accountInfoResponse->getStatusCode();
``````

### Transactions

Here is working example of sending a transfer instruction to the Solana blockchain:

```php
$client = new SolanaRpcClient(SolanaRpcClient::DEVNET_ENDPOINT);
$connection = new Connection($client);
$fromPublicKey = KeyPair::fromSecretKey([...]);
$toPublicKey = new PublicKey('J3dxNj7nDRRqRRXuEMynDG57DkZK4jYRuv3Garmb1i99');
$instruction = SystemProgram::transfer(
    $fromPublicKey->getPublicKey(),
    $toPublicKey,
    6
);

$transaction = new Transaction(null, null, $fromPublicKey->getPublicKey());
$transaction->add($instruction);

$txHash = $connection->sendTransaction($transaction, $fromPublicKey);
```

Note: This project is in alpha, the code to generate instructions is still being worked on `$instruction = SystemProgram::abc()`

## Roadmap

1. Borsh serialize and deserialize.
2. Improved documentation.
3. Build out more of the Connection, SystemProgram, TokenProgram, MetaplexProgram classes.
4. Improve abstractions around working with binary data.
5. Optimizations:
   1. Leverage PHP more.
   2. Better cache `$recentBlockhash` when sending transactions.
6. Suggestions? Open an issue or PR :D

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please create an issue in this repository.

## Credits

- [Matt Stauffer](https://github.com/mattstauffer) (Original creator)
- [Zach Vander Velden](https://github.com/exzachlyvv) (Metadata wizard)
- [Halil Beycan](https://github.com/beycandeveloper) (Maintainer)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
