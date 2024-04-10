<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use MYETH\MyEth;

class MyEthTest extends TestCase
{
    public function testSignTransaction() {
        $transactionJson = '{"nonce":0,"gasPrice":1000000000,"gas":21000,"to":"0x4bbeEB066eD09B7AEd07bF39EEe0460DFa261520","value":1000000000000000000,"data":""}';
        $privateKey = '';
        
        $privateKey = preg_replace('/^0x/', '', $privateKey);

        $signedTransaction = MyEth::signTransaction($transactionJson, $privateKey);
        $signedTransactionArray = $signedTransaction;

        $this->assertArrayHasKey('nonce', $signedTransactionArray);
        $this->assertArrayHasKey('gasPrice', $signedTransactionArray);
        $this->assertArrayHasKey('gas', $signedTransactionArray);
        $this->assertArrayHasKey('to', $signedTransactionArray);
        $this->assertArrayHasKey('value', $signedTransactionArray);
        $this->assertArrayHasKey('data', $signedTransactionArray);
        $this->assertArrayHasKey('v', $signedTransactionArray);
        $this->assertArrayHasKey('r', $signedTransactionArray);
        $this->assertArrayHasKey('s', $signedTransactionArray);
        $this->assertArrayHasKey('signature', $signedTransactionArray);

        echo $signedTransactionArray;
    }

    public function testSignMessage() {
        $message = "Hello, world!";
        $privateKey = '';
        
        $privateKey = preg_replace('/^0x/', '', $privateKey);

        $signedMessage = MyEth::signMessage($message, $privateKey);
        $signedMessageArray = $signedMessage;

        $this->assertArrayHasKey('message', $signedMessageArray);
        $this->assertArrayHasKey('v', $signedMessageArray);
        $this->assertArrayHasKey('r', $signedMessageArray);
        $this->assertArrayHasKey('s', $signedMessageArray);
        $this->assertArrayHasKey('signature', $signedMessageArray);

        echo $signedMessageArray;
    }
}