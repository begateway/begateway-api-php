<?php
namespace beGateway;

class TestCase extends \UnitTestCase {

  const SHOP_ID = 115;
  const SHOP_KEY = '1cf24f84fa4f6a152e1b55942308988cd8217851b1c065ab2549d74879119c41';
  const SHOP_ID_3D = 223;
  const SHOP_KEY_3D = 'b038f3190fb0a4463a2bfee3413b5f53b66b93b643c3286078efd4dc9ce0eb36';

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
