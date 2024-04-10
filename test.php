<?php declare(strict_types=1);

require_once('./src/MyEth.php');

use MYETH\MyEth;

function testSignMessage() {
    $message = "Hello, world!";
    $privateKey = '2bb758569718e4ac67a8164d98cadd08068e5839a6cb7c8bd4e769893818ddbf';
    
    $privateKey = preg_replace('/^0x/', '', $privateKey);

    $signedMessage = MyEth::signMessage($message, $privateKey);
    // $signedMessageArray = json_decode($signedMessage, true);

    echo json_encode($signedMessage);
}

testSignMessage();