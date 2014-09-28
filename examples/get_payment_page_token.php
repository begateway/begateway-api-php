<?php
namespace eComCharge;

require_once __DIR__ . '/../lib/ecomcharge.php';
require_once __DIR__ . '/test_shop_data.php';

Logger::getInstance()->setLogLevel(Logger::DEBUG);

$transaction = new GetPaymentPageToken(SHOP_ID, SHOP_SECRET_KEY);

$amount = rand(100, 10000);

$transaction->money->setAmount($amount);
$transaction->money->setCurrency('EUR');
$transaction->setDescription('test');
$transaction->setTrackingId('my_custom_variable');
$transaction->setLanguage('en');
$transaction->setNotificationUrl('http://www.example.com/notify');
$transaction->setSuccessUrl('http://www.example.com/success');
$transaction->setDeclineUrl('http://www.example.com/decline');
$transaction->setFailUrl('http://www.example.com/fail');
$transaction->setCancelUrl('http://www.example.com/cancel');

$transaction->customer->setFirstName('John');
$transaction->customer->setLastName('Doe');
$transaction->customer->setCountry('LV');
$transaction->customer->setAddress('Demo str 12');
$transaction->customer->setCity('Riga');
$transaction->customer->setZip('LV-1082');
$transaction->customer->setIp('127.0.0.1');
$transaction->customer->setEmail('john@example.com');
$transaction->setAddressHidden();


$response = $transaction->submit();

print("Transaction message: " . $response->getMessage() . PHP_EOL);

if ($response->isSuccess() ) {
  print("Token: " . $response->getToken() . PHP_EOL);
}
?>
