# eComCharge bePaid integration library

[![Build Status Master](https://travis-ci.org/ecomcharge/bepaid-api-php.svg?branch=master)](https://travis-ci.org/ecomcharge/bepaid-api-php)

## Requirements

PHP 5.3+

## Getting started

Simple usage looks like:

```php
require_once __DIR__ . 'PATH_TO_INSTALLED_LIBRARY/lib/ecomcharge.php';
\eComCharge\Settings::setShopId('your_shop_id');
\eComCharge\Settings::setShopKey('your_shop_key');

\eComCharge\Logger::getInstance()->setLogLevel(\eComCharge\Logger::INFO);

$transaction = new \eComCharge\Payment;

$transaction->money->setAmount(1.00);
$transaction->money->setCurrency('EUR');
$transaction->setDescription('test order');
$transaction->setTrackingId('my_custom_variable');

$transaction->card->setCardNumber('4200000000000000');
$transaction->card->setCardHolder('John Doe');
$transaction->card->setCardExpMonth(1);
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

if ($response->isSuccess()) {
  print("Status: " . $response->getStatus() . PHP_EOL);
  print("Transaction UID: " . $response->getUid() . PHP_EOL);
} elseif ($response->isFailed()) {
  print("Status: " . $response->getStatus() . PHP_EOL);
  print("Transaction UID: " . $response->getUid() . PHP_EOL);
  print("Reason: " . $response->getMessage() . PHP_EOL);
} else {
  print("Status: error" . PHP_EOL);
  print("Reason: " . $response->getMessage() . PHP_EOL);
}
```

## Examples

See the [examples](examples) directory for integration examples of different
transactions.

## Documentation

Visit https://doc.ecomcharge.com for up-to-date documentation.

## Tests

To run tests

```bash
php -f ./test/ecomcharge.php
```

