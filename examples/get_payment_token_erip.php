<?php
require_once __DIR__ . '/../lib/beGateway.php';
require_once __DIR__ . '/test_shop_data.php';

\beGateway\Logger::getInstance()->setLogLevel(\beGateway\Logger::DEBUG);

$transaction = new \beGateway\GetPaymentToken;

$cc = new \beGateway\PaymentMethod\CreditCard;
$erip = new \beGateway\PaymentMethod\Erip(array(
  'order_id' => 1234,
  'account_number' => '99999999',
  'service_no' => '99999999'
));

$transaction->addPaymentMethod($cc);
$transaction->addPaymentMethod($erip);

$amount = rand(100, 10000);

$transaction->money->setAmount($amount);
$transaction->money->setCurrency('BYN');
$transaction->setDescription('test');
$transaction->setTrackingId('my_custom_variable');
$transaction->setLanguage('ru');
$transaction->setNotificationUrl('http://www.example.com/notify');
$transaction->setSuccessUrl('http://www.example.com/success');
$transaction->setDeclineUrl('http://www.example.com/decline');
$transaction->setFailUrl('http://www.example.com/fail');
$transaction->setCancelUrl('http://www.example.com/cancel');

$transaction->customer->setEmail('john@example.com');
$transaction->setAddressHidden();

$response = $transaction->submit();

print("Transaction message: " . $response->getMessage() . PHP_EOL);

if ($response->isSuccess() ) {
  print("Token: " . $response->getToken() . PHP_EOL);
}
?>
