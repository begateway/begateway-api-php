<?php
require_once __DIR__ . '/../lib/BeGateway.php';
require_once __DIR__ . '/test_shop_data.php';

\BeGateway\Logger::getInstance()->setLogLevel(\BeGateway\Logger::DEBUG);

$token = new \BeGateway\CardToken;
$token->card->setCardNumber('4200000000000000');
$token->card->setCardHolder('John Doe');
$token->card->setCardExpMonth(1);
$token->card->setCardExpYear(2029);

$response = $token->submit();

if ($response->isSuccess()) {
  print("Card token: " . $response->card->getCardToken() . PHP_EOL);
  print("Trying to make a payment by the token and with CVC 123" . PHP_EOL);

  $transaction = new \BeGateway\PaymentOperation;

  $amount = rand(1, 100);

  $transaction->money->setAmount($amount);
  $transaction->money->setCurrency('EUR');
  $transaction->setDescription('test');
  $transaction->setTrackingId('my_custom_variable');

  $transaction->card->setCardCvc('123');
  $transaction->card->setCardToken($response->card->getCardToken());

  $transaction->setTestMode(true);

  $transaction->customer->setFirstName('John');
  $transaction->customer->setLastName('Doe');
  $transaction->customer->setCountry('LV');
  $transaction->customer->setAddress('Demo str 12');
  $transaction->customer->setCity('Riga');
  $transaction->customer->setZip('LV-1082');
  $transaction->customer->setIp('127.0.0.1');
  $transaction->customer->setEmail('john@example.com');


  $response = $transaction->submit();

  print("Transaction message: " . $response->getMessage() . PHP_EOL);
  print("Transaction status: " . $response->getStatus(). PHP_EOL);

  if ($response->isSuccess() ) {
    print("Transaction UID: " . $response->getUid() . PHP_EOL);
  }
}
?>
