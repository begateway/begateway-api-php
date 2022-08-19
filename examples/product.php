<?php

require_once __DIR__ . '/../BeGateway.php';
require_once __DIR__ . '/test_shop_data.php';

BeGateway\Logger::getInstance()->setLogLevel(BeGateway\Logger::DEBUG);

$transaction = new BeGateway\Product;

$amount = rand(1, 100);

$transaction->money->setAmount($amount);
$transaction->money->setCurrency('EUR');
$transaction->setName('name');
$transaction->setDescription('test');
$transaction->setTestMode(true);

$response = $transaction->submit();

if ($response->isSuccess()) {
    echo 'Product Id: ' . $response->getId() . PHP_EOL;
    echo 'Link to pay: ' . $response->getPayLink() . PHP_EOL;
    echo 'URL for website: ' . $response->getPayUrl() . PHP_EOL;
}
