# BeGateway payment system API integration library

[![Build Status Master](https://travis-ci.org/begateway/begateway-api-php.svg?branch=master)](https://travis-ci.org/begateway/begateway-api-php)

## Requirements

PHP 5.3+

## Test data

### Shop without 3-D Secure

  * Shop Id __361__
  * Shop secret key __b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d__

### Shop with 3-D Secure

  * Shop Id __362__
  * Shop secret key __9ad8ad735945919845b9a1996af72d886ab43d3375502256dbf8dd16bca59a4e__

### Test data set

  * Card name __John Doe__
  * Card expiry month __01__ to get a success payment
  * Card expiry month __10__ to get a failed payment
  * CVC __123__

### Test card numbers

Refer to the documentation https://doc.begateway.com/test-integration#test-card-number for valid test card numbers.

## Getting started

### Setup

Before to use the library classes you must configure it.
You have to setup values of variables as follows:

  * `shopId`
  * `shopKey`
  * `gatewayBase`
  * `checkoutBase`

This data you will receive from your payment processor.

```php
\BeGateway\Settings::$shopId  = 361;
\BeGateway\Settings::$shopKey = 'b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d';
\BeGateway\Settings::$gatewayBase = 'https://demo-gateway.begateway.com';
\BeGateway\Settings::$checkoutBase = 'https://checkout.begateway.com';
```

### Hosted payment page

Simple usage looks like:

```php
require_once __DIR__ . 'PATH_TO_INSTALLED_LIBRARY/lib/BeGateway.php';
\BeGateway\Settings::$shopId  = 361;
\BeGateway\Settings::$shopKey = 'b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d';

\BeGateway\Logger::getInstance()->setLogLevel(\BeGateway\Logger::INFO);

$transaction = new \BeGateway\GetPaymentToken;

$transaction->money->setAmount(1.00);
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

$response = $transaction->submit();

if ($response->isSuccess() ) {
  header("Location: " . $response->getRedirectUrl() );
}
```

### Payment request via direct API

Simple usage looks like:

```php
require_once __DIR__ . 'PATH_TO_INSTALLED_LIBRARY/lib/BeGateway.php';
\BeGateway\Settings::$shopId  = 361;
\BeGateway\Settings::$shopKey = 'b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d';

\BeGateway\Logger::getInstance()->setLogLevel(\BeGateway\Logger::INFO);

$transaction = new \BeGateway\Payment;

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

Visit https://doc.begateway.com for up-to-date documentation.

## Tests

To run tests

```bash
php -f ./test/BeGateway.php
```
