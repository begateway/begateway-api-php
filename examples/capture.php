<?php

require_once __DIR__ . '/../BeGateway.php';
require_once __DIR__ . '/test_shop_data.php';

BeGateway\Logger::getInstance()->setLogLevel(BeGateway\Logger::DEBUG);

$transaction = new BeGateway\AuthorizationOperation;

$amount = rand(1, 100);

$transaction->money->setAmount($amount);
$transaction->money->setCurrency('EUR');
$transaction->setDescription('test');
$transaction->setTrackingId('my_custom_variable');

$transaction->setTestMode(true);

$transaction->card->setCardNumber('4200000000000000');
$transaction->card->setCardHolder('JOHN DOE');
$transaction->card->setCardExpMonth('01');
$transaction->card->setCardExpYear(2030);
$transaction->card->setCardCvc('123');

$transaction->customer->setFirstName('John');
$transaction->customer->setLastName('Doe');
$transaction->customer->setCountry('LV');
$transaction->customer->setAddress('Demo str 12');
$transaction->customer->setCity('Riga');
$transaction->customer->setZip('LV-1082');
$transaction->customer->setIp('127.0.0.1');
$transaction->customer->setEmail('john@example.com');

$response = $transaction->submit();

echo 'Transaction message: ' . $response->getMessage() . PHP_EOL;
echo 'Transaction status: ' . $response->getStatus() . PHP_EOL;

if ($response->isSuccess()) {
    echo 'Transaction UID: ' . $response->getUid() . PHP_EOL;
    echo 'Trying to Capture transaction ' . $response->getUid() . PHP_EOL;

    $capture = new BeGateway\CaptureOperation;
    $capture->setParentUid($response->getUid());
    $capture->money->setAmount($transaction->money->getAmount());

    $capture_response = $capture->submit();

    if ($capture_response->isSuccess()) {
        echo 'Captured successfuly. Captured transaction UID ' . $capture_response->getUid() . PHP_EOL;
    } else {
        echo 'Problem to capture' . PHP_EOL;
        echo 'Capture message: ' . $capture_response->getMessage() . PHP_EOL;
    }
}
