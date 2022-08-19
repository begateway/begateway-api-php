<?php

require_once __DIR__ . '/../BeGateway.php';
require_once __DIR__ . '/test_shop_data.php';

BeGateway\Logger::getInstance()->setLogLevel(BeGateway\Logger::DEBUG);

$transaction = new BeGateway\GetPaymentToken;

$cc = new \BeGateway\PaymentMethod\CreditCard;

$halva = new \BeGateway\PaymentMethod\CreditCardHalva;

$transaction->addPaymentMethod($cc);
$transaction->addPaymentMethod($halva);

$amount = rand(100, 1000);

$transaction->money->setAmount($amount);
$transaction->money->setCurrency('BYN');
$transaction->setDescription('Тестовая оплата');
$transaction->setTrackingId('my_custom_variable');
$transaction->setLanguage('ru');

$transaction->setTestMode(true);

$transaction->setNotificationUrl('http://www.example.com/notify');
$transaction->setSuccessUrl('http://www.example.com/success');
$transaction->setDeclineUrl('http://www.example.com/decline');
$transaction->setFailUrl('http://www.example.com/fail');
$transaction->setCancelUrl('http://www.example.com/cancel');

// No available to make payment for the order in 2 days
$transaction->setExpiryDate(date('Y-m-d', 3 * 24 * 3600 + time()) . 'T00:00:00+03:00');

$transaction->customer->setEmail('john@example.com');

$response = $transaction->submit();

echo 'Transaction message: ' . $response->getMessage() . PHP_EOL;

if ($response->isSuccess()) {
    echo 'Token: ' . $response->getToken() . PHP_EOL;
}
