<?php

// Tested on PHP 5.3

// This snippet (and some of the curl code) due to the Facebook SDK.
if (!function_exists('curl_init')) {
  throw new Exception('beGateway needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('beGateway needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
  throw new Exception('beGateway needs the Multibyte String PHP extension.');
}

if (!class_exists('\beGateway\Settings')) {
  require_once (__DIR__ . '/beGateway/Settings.php');
  require_once (__DIR__ . '/beGateway/Logger.php');
  require_once (__DIR__ . '/beGateway/Language.php');
  require_once (__DIR__ . '/beGateway/Customer.php');
  require_once (__DIR__ . '/beGateway/Card.php');
  require_once (__DIR__ . '/beGateway/Money.php');
  require_once (__DIR__ . '/beGateway/ResponseBase.php');
  require_once (__DIR__ . '/beGateway/Response.php');
  require_once (__DIR__ . '/beGateway/ResponseCheckout.php');
  require_once (__DIR__ . '/beGateway/ResponseCardToken.php');
  require_once (__DIR__ . '/beGateway/ApiAbstract.php');
  require_once (__DIR__ . '/beGateway/ChildTransaction.php');
  require_once (__DIR__ . '/beGateway/GatewayTransport.php');
  require_once (__DIR__ . '/beGateway/Authorization.php');
  require_once (__DIR__ . '/beGateway/Payment.php');
  require_once (__DIR__ . '/beGateway/Capture.php');
  require_once (__DIR__ . '/beGateway/Void.php');
  require_once (__DIR__ . '/beGateway/Refund.php');
  require_once (__DIR__ . '/beGateway/Credit.php');
  require_once (__DIR__ . '/beGateway/QueryByUid.php');
  require_once (__DIR__ . '/beGateway/QueryByTrackingId.php');
  require_once (__DIR__ . '/beGateway/QueryByToken.php');
  require_once (__DIR__ . '/beGateway/GetPaymentToken.php');
  require_once (__DIR__ . '/beGateway/Webhook.php');
  require_once (__DIR__ . '/beGateway/CardToken.php');
  require_once (__DIR__ . '/beGateway/PaymentMethod/Base.php');
  require_once (__DIR__ . '/beGateway/PaymentMethod/Erip.php');
  require_once (__DIR__ . '/beGateway/PaymentMethod/CreditCard.php');
  require_once (__DIR__ . '/beGateway/PaymentMethod/CreditCardHalva.php');
  require_once (__DIR__ . '/beGateway/PaymentMethod/Emexvoucher.php');
}
?>
