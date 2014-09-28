<?php

// Tested on PHP 5.3

// This snippet (and some of the curl code) due to the Facebook SDK.
if (!function_exists('curl_init')) {
  throw new Exception('eComCharge needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('eComCharge needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
  throw new Exception('eComCharge needs the Multibyte String PHP extension.');
}

require_once (__DIR__ . '/eComCharge/Logger.php');
require_once (__DIR__ . '/eComCharge/Language.php');
require_once (__DIR__ . '/eComCharge/Customer.php');
require_once (__DIR__ . '/eComCharge/Card.php');
require_once (__DIR__ . '/eComCharge/Money.php');
require_once (__DIR__ . '/eComCharge/ResponseBase.php');
require_once (__DIR__ . '/eComCharge/Response.php');
require_once (__DIR__ . '/eComCharge/ResponseCheckout.php');
require_once (__DIR__ . '/eComCharge/ResponseCardToken.php');
require_once (__DIR__ . '/eComCharge/Api.php');
require_once (__DIR__ . '/eComCharge/ChildTransaction.php');
require_once (__DIR__ . '/eComCharge/GatewayTransport.php');
require_once (__DIR__ . '/eComCharge/Authorization.php');
require_once (__DIR__ . '/eComCharge/Payment.php');
require_once (__DIR__ . '/eComCharge/Capture.php');
require_once (__DIR__ . '/eComCharge/Void.php');
require_once (__DIR__ . '/eComCharge/Refund.php');
require_once (__DIR__ . '/eComCharge/Credit.php');
require_once (__DIR__ . '/eComCharge/QueryByUid.php');
require_once (__DIR__ . '/eComCharge/QueryByTrackingId.php');
require_once (__DIR__ . '/eComCharge/QueryByToken.php');
require_once (__DIR__ . '/eComCharge/GetPaymentPageToken.php');
require_once (__DIR__ . '/eComCharge/Webhook.php');
require_once (__DIR__ . '/eComCharge/CardToken.php');

?>
