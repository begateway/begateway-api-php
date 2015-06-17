<?php
namespace beGateway;

class TestCase extends \UnitTestCase {

  const SHOP_ID = 361;
  const SHOP_KEY = 'b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d';
  const SHOP_ID_3D = 362;
  const SHOP_KEY_3D = '9ad8ad735945919845b9a1996af72d886ab43d3375502256dbf8dd16bca59a4e';

  public static function authorizeFromEnv($threeds = false) {
    $shop_id = null;
    $shop_key = null;

    if ($threeds) {
      $shop_id = getenv('SHOP_ID_3D');

      if (!$shop_id) {
        $shop_id = self::SHOP_ID_3D;
      }

      $shop_key = getenv('SHOP_SECRET_KEY_3D');
      if (!$shop_key) {
        $shop_key = self::SHOP_KEY_3D;
      }
    }else{
      $shop_id = getenv('SHOP_ID');

      if (!$shop_id) {
        $shop_id = self::SHOP_ID;
      }

      $shop_key = getenv('SHOP_SECRET_KEY');
      if (!$shop_key) {
        $shop_key = self::SHOP_KEY;
      }
    }

    Settings::setShopId($shop_id);
    Settings::setShopKey($shop_key);
  }
}
?>
