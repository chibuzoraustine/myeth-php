<h1>MyEth PHP</h1>

interact with EVM (ethereum virtual machine) compatible chains with ease on php, sign messages, sign transactions, sign and send transactions.

<h2>Table of content</h2>

- [Requirements](#requirements)
- [Installation](#installation)
- [Sign message](#sign-message)
- [Sign transaction](#sign-transaction)
- [Sign and Send Transaction](#sign-and-send-transaction)
- [Using different chains](#using-different-chains)
- [Error handling](#error-handling)
- [Testing](#testing)
- [License](#license)

## Requirements

1. PHP 8 or higher.

## Installation

```bash
composer require arcgen/myeth-php
```

## Sign message

```php

use MYETH\MyEth;

$message = "Hello, world!";
$privateKey = ''; // without 0x (remove 0x before passing private key)

$signedMessage = MyEth::signMessage($message, $privateKey);

echo json_encode($signedMessage);

```

## Sign transaction

```php
use MYETH\MyEth;

$transactionEncodedJson = '{"nonce":0,"gasPrice":1000000000,"gas":21000,"to":"0x4bbeEB066eD09B7AEd07bF39EEe0460DFa261520","value":1000000000000000000,"data":""}';
$privateKey = ''; // without 0x (remove 0x before passing private key)

$signedTransaction = MyEth::signTransaction($transactionEncodedJson, $privateKey);

echo $signedTransaction;
```

## Sign and Send Transaction

```php
use MYETH\MyEth;

$transactionEncodedJson = '{"nonce":0,"gasPrice":1000000000,"gas":21000,"to":"0x4bbeEB066eD09B7AEd07bF39EEe0460DFa261520","value":1000000000000000000,"data":""}';
$privateKey = ''; // without 0x (remove 0x before passing private key)
$web3Provider = 'https://mainnet.infura.io/v3/YOUR_INFURA_PROJECT_ID';

// Invoke the signAndSendTransaction method
$response = MyEth::signAndSendTransaction($transactionEncodedJson, $privateKey, $web3Provider);

echo $response;
```

## Using different chains

when using other evm chains or testnets it is advisable to pass chain ID as the last parameter.

```php

use MYETH\MyEth;

$message = "Hello, world!";
$privateKey = ''; // without 0x (remove 0x before passing private key)
$chainID = '80001' // Polygon matic

$signedMessage = MyEth::signMessage($message, $privateKey, $chainID);

echo json_encode($signedMessage);

```

## Error handling

You can catch request errors by wrapping the method in a try / catch block.

```php
use MYETH\MyEth;

$transactionEncodedJson = '{"nonce":0,"gasPrice":1000000000,"gas":21000,"to":"0x4bbeEB066eD09B7AEd07bF39EEe0460DFa261520","value":1000000000000000000,"data":""}';
$privateKey = ''; // without 0x (remove 0x before passing private key)
$web3Provider = 'https://mainnet.infura.io/v3/YOUR_INFURA_PROJECT_ID';

try {
    $response = MyEth::signAndSendTransaction($transactionEncodedJson, $privateKey, $web3Provider);
    echo $response;
} catch (\Exception $e) {
            $this->fail("Exception thrown: " . $e->getMessage());
}
```

Response :
```console
Exception thrown: Error message
```

## Testing

<!-- Prior to running tests, ensure you have renamed the `.env.example` file to `.env` and populated it with a test key (testSecretKey). Then, execute the following command: -->

```bash
./vendor/bin/phpunit
```


## License

[MIT](https://choosealicense.com/licenses/mit/)
