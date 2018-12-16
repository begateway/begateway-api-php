<?php
require_once __DIR__ . '/../lib/BeGateway.php';
require_once __DIR__ . '/test_shop_data.php';

\BeGateway\Logger::getInstance()->setLogLevel(\BeGateway\Logger::DEBUG);

$transaction = new \BeGateway\Product;

$amount = rand(1, 100);

$transaction->money->setAmount($amount);
$transaction->money->setCurrency('EUR');
$transaction->setName('name');
$transaction->setDescription('test');
$transaction->setTestMode(true);

$response = $transaction->submit();

if ($response->isSuccess()) {
  print("Product Id: " . $response->getId() . PHP_EOL);
  print("Link to pay: " . $response->getPayLink() . PHP_EOL);
  print("URL for website: " . $response->getPayUrl() . PHP_EOL);
}
