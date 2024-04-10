<?php

namespace MYETH;

require_once 'vendor/autoload.php';

use InvalidArgumentException;
use kornrunner\Keccak;
use kornrunner\Ethereum\Transaction;
use kornrunner\Secp256k1;
use Web3\Web3;

class MyEth
{
    public static function signTransaction($transactionJson, $privateKey, $chainId = 1)
    {
        $transaction = json_decode($transactionJson, true);

        // Validate required fields
        if (
            !isset($transaction['nonce']) || !isset($transaction['gasPrice']) || !isset($transaction['gas']) ||
            !isset($transaction['to']) || !isset($transaction['value']) || !isset($transaction['data'])
        ) {
            throw new InvalidArgumentException("Transaction JSON is missing required fields.");
        }

        // Convert values to hexadecimal
        $nonce = '0x' . dechex($transaction['nonce']);
        $gasPrice = '0x' . dechex($transaction['gasPrice']);
        $gas = '0x' . dechex($transaction['gas']);
        $to = $transaction['to'];
        $value = '0x' . dechex($transaction['value']);
        $data = $transaction['data'];

        // Create transaction object
        $tx = new Transaction($nonce, $gasPrice, $gas, $to, $value, $data);

        // Calculate the hash of the transaction
        $hash = $tx->getRaw($privateKey);


        // Ensure the private key is in hexadecimal format
        $privateKey = preg_replace('/^0x/', '', $privateKey);

        // Sign the transaction
        // $privateKeyBinary = pack("H*", $privateKey);
        $ecdsa = new Secp256k1();
        $signature = $ecdsa->sign($hash, $privateKey);

        // Get the r, s, and v components from the signature
        $r = gmp_strval($signature->getR(), 16);
        $s = gmp_strval($signature->getS(), 16);
        $v = 27 + $signature->getRecoveryParam() + ($chainId * 2); // Adjust v based on chainId

        // Ensure r, s, and v are in the correct format
        $r = str_pad($r, 64, "0", STR_PAD_LEFT);
        $s = str_pad($s, 64, "0", STR_PAD_LEFT);
        $vHex = dechex($v);

        // Concatenate r, s, and v to form the real signature string
        $signatureString = '0x' . $r . $s . $vHex;

        // Construct the signed transaction
        $signedTransaction = [
            'nonce' => $nonce,
            'gasPrice' => $gasPrice,
            'gas' => $gas,
            'to' => $to,
            'value' => $value,
            'data' => $data,
            'v' => $v,
            'r' => '0x' . $r,
            's' => '0x' . $s,
            'signature' => $signatureString
        ];

        return ($signedTransaction);
    }

    public static function signMessage($message, $privateKey, $chainId = 1)
    {
        // Convert message to bytes
        $messageBytes = is_array($message) ? json_encode($message) : $message;

        // Calculate the hash of the message
        $hash = Keccak::hash("\x19Ethereum Signed Message:\n" . strlen($messageBytes) . $messageBytes, 256);

        $privateKey = preg_replace('/^0x/', '', $privateKey);

        // Sign the message hash
        // $privateKeyBinary = pack("H*", $privateKey); 
        $ecdsa = new Secp256k1();
        $signature = $ecdsa->sign($hash, $privateKey);

        // Get the r, s, and v components from the signature
        $r = gmp_strval($signature->getR(), 16);
        $s = gmp_strval($signature->getS(), 16);
        $v = 27 + $signature->getRecoveryParam() + ($chainId * 2); // Adjust v based on chainId

        // Ensure r, s, and v are in the correct format
        $r = str_pad($r, 64, "0", STR_PAD_LEFT);
        $s = str_pad($s, 64, "0", STR_PAD_LEFT);
        $vHex = dechex($v);

        // Concatenate r, s, and v to form the real signature string
        $signatureString = '0x' . $r . $s . $vHex;

        // Construct the signed message
        $signedMessage = [
            'message' => $messageBytes,
            'v' => $v,
            'r' => '0x' . $r,
            's' => '0x' . $s,
            'signature' => $signatureString
        ];

        return ($signedMessage);
    }

    public static function signAndSendTransaction($transactionJson, $privateKey, $web3Provider, $chainId = 1)
    {
        $signedTransaction = self::signTransaction($transactionJson, $privateKey);
        $web3 = new Web3($web3Provider);

        // Convert signed transaction from JSON to array
        $signedTransactionArray = $signedTransaction;

        // Add chainId for EIP-155
        $signedTransactionArray['chainId'] = $chainId;

        // Convert transaction array back to JSON
        $signedTransactionJson = json_encode($signedTransactionArray);

        // Send the signed transaction
        $response = $web3->eth->sendRawTransaction($signedTransactionJson);

        return $response;
    }
}
