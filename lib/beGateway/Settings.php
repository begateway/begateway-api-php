<?php

namespace beGateway;

class Settings {
  public static $shopId;
  public static $shopKey;
  public static $gatewayBase = 'https://demo-gateway.begateway.com';
  public static $checkoutBase = 'https://checkout.begateway.com';

  public static function getShopId() {
    return self::$shopId;
  }

  public static function setShopId($shopId) {
    self::$shopId = $shopId;
  }

  public static function getShopKey() {
    return self::$shopKey;
  }

  public static function setShopKey($shopKey) {
    self::$shopKey = $shopKey;
  }
}
?>
