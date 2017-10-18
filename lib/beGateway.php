<?php

// Tested on PHP 5.3

// This snippet (and some of the curl code) due to the Facebook SDK.
if (!function_exists('curl_init')) {
  throw new Exception('BeGateway needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('BeGateway needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
  throw new Exception('BeGateway needs the Multibyte String PHP extension.');
}

if (!class_exists('\BeGateway\Settings')) {
  require_once (__DIR__ . '/BeGateway/Settings.php');
  require_once (__DIR__ . '/BeGateway/Logger.php');
  require_once (__DIR__ . '/BeGateway/Language.php');
  require_once (__DIR__ . '/BeGateway/Customer.php');
  require_once (__DIR__ . '/BeGateway/Card.php');
  require_once (__DIR__ . '/BeGateway/Money.php');
  require_once (__DIR__ . '/BeGateway/ResponseBase.php');
  require_once (__DIR__ . '/BeGateway/Response.php');
  require_once (__DIR__ . '/BeGateway/ResponseCheckout.php');
  require_once (__DIR__ . '/BeGateway/ResponseCardToken.php');
  require_once (__DIR__ . '/BeGateway/ApiAbstract.php');
  require_once (__DIR__ . '/BeGateway/ChildTransaction.php');
  require_once (__DIR__ . '/BeGateway/GatewayTransport.php');
  require_once (__DIR__ . '/BeGateway/Authorization.php');
  require_once (__DIR__ . '/BeGateway/Payment.php');
  require_once (__DIR__ . '/BeGateway/Capture.php');
  require_once (__DIR__ . '/BeGateway/Void.php');
  require_once (__DIR__ . '/BeGateway/Refund.php');
  require_once (__DIR__ . '/BeGateway/Credit.php');
  require_once (__DIR__ . '/BeGateway/QueryByUid.php');
  require_once (__DIR__ . '/BeGateway/QueryByTrackingId.php');
  require_once (__DIR__ . '/BeGateway/QueryByToken.php');
  require_once (__DIR__ . '/BeGateway/GetPaymentToken.php');
  require_once (__DIR__ . '/BeGateway/Webhook.php');
  require_once (__DIR__ . '/BeGateway/CardToken.php');
  require_once (__DIR__ . '/BeGateway/PaymentMethod/Base.php');
  require_once (__DIR__ . '/BeGateway/PaymentMethod/Erip.php');
  require_once (__DIR__ . '/BeGateway/PaymentMethod/CreditCard.php');
  require_once (__DIR__ . '/BeGateway/PaymentMethod/CreditCardHalva.php');
  require_once (__DIR__ . '/BeGateway/PaymentMethod/Emexvoucher.php');
}
?>
