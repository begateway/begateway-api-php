<?php
namespace beGateway;

class TestCase extends \UnitTestCase {

  const SHOP_ID = 361;
  const SHOP_KEY = 'b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d';
  const SHOP_PUB_KEY = 'cc803ec0-6038-4fe6-abf0-a514d5e89d6f';
  const SHOP_ID_3D = 362;
  const SHOP_KEY_3D = '9ad8ad735945919845b9a1996af72d886ab43d3375502256dbf8dd16bca59a4e';
  const SHOP_PUB_KEY_3D = 'ee7257d4-dcff-41bf-a95f-fe0ff79bf64f';

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

      $shop_pub_key = getenv('SHOP_PUB_KEY_3D');
      if (!$shop_pub_key) {
        $shop_key = self::SHOP_PUB_KEY_3D;
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
      $shop_pub_key = getenv('SHOP_PUB_KEY');
      if (!$shop_pub_key) {
        $shop_key = self::SHOP_PUB_KEY;
      }
    }

    Settings::$shopId = $shop_id;
    Settings::$shopKey = $shop_key;
    Settings::$shopPubKey = $shop_pub_key;
  }
}
?>
