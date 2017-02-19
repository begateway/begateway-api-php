<?php
require_once __DIR__ . '/../lib/beGateway.php';
require_once __DIR__ . '/test_shop_data.php';

\beGateway\Logger::getInstance()->setLogLevel(\beGateway\Logger::DEBUG);

$transaction = new \beGateway\GetPaymentToken;

$voucher = new \beGateway\PaymentMethod\Emexvoucher;

$transaction->addPaymentMethod($voucher);

$amount = rand(100, 1000);

$transaction->money->setAmount($amount);
$transaction->money->setCurrency('EUR');
$transaction->setDescription('Test payment');
$transaction->setTrackingId('my_custom_variable');
$transaction->setLanguage('ru');
$transaction->setNotificationUrl('http://www.example.com/notify');
$transaction->setSuccessUrl('http://www.example.com/success');
$transaction->setDeclineUrl('http://www.example.com/decline');
$transaction->setFailUrl('http://www.example.com/fail');
$transaction->setCancelUrl('http://www.example.com/cancel');

# No available to make payment for the order in 2 days
$transaction->setExpiryDate(date("Y-m-d", 3*24*3600 + time()) . "T00:00:00+03:00");

$transaction->customer->setEmail('john@example.com');
$transaction->setAddressHidden();

$response = $transaction->submit();

print("Transaction message: " . $response->getMessage() . PHP_EOL);

if ($response->isSuccess() ) {
  print("Token: " . $response->getToken() . PHP_EOL);
}
?>
