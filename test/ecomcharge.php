<?php
echo "Running the eComCharge bePaid PHP bindings test suite.\n".
     "If you're trying to use the PHP bindings you'll probably want ".
     "to require('lib/ecomcharge.php'); instead of this file\n";

$ok = @include_once(dirname(__FILE__).'/simpletest/autorun.php');
if (!$ok) {
  echo "MISSING DEPENDENCY: The eComCharge API test cases depend on SimpleTest. ".
       "Download it at <http://www.simpletest.org/>, and either install it ".
       "in your PHP include_path or put it in the test/ directory.\n";
  exit(1);
}

abstract class TestData {
  public static $_shop_id;
  public static $_shop_key;
  public static $_shop_id_3d;
  public static $_shop_key_3d;

  public static function setShopId($id) {
    self::$_shop_id = $id;
  }

  public static function getShopId() {
    return self::$_shop_id;
  }

  public static function setShopKey($key) {
    self::$_shop_key = $key;
  }

  public static function getShopKey() {
    return self::$_shop_key;
  }

  public static function setShopId3d($id) {
    self::$_shop_id_3d = $id;
  }

  public static function getShopId3d() {
    return self::$_shop_id_3d;
  }

  public static function setShopKey3d($key) {
    self::$_shop_key_3d = $key;
  }

  public static function getShopKey3d() {
    return self::$_shop_key_3d;
  }
}

function authorizeFromEnv()
{
  $shop_id = getenv('ECOMCHARGE_SHOP_ID');
  if (!$shop_id)
    $shop_id = 115;
  $shop_key = getenv('ECOMCHARGE_SHOP_SECRET_KEY');
  if (!$shop_key)
    $shop_key = '1cf24f84fa4f6a152e1b55942308988cd8217851b1c065ab2549d74879119c41';

  $shop_id_3d = getenv('ECOMCHARGE_SHOP_ID_3D');
  if (!$shop_id_3d)
    $shop_id_3d = 223;
  $shop_key_3d = getenv('ECOMCHARGE_SHOP_SECRET_KEY_3D');
  if (!$shop_key_3d)
    $shop_key_3d = 'b038f3190fb0a4463a2bfee3413b5f53b66b93b643c3286078efd4dc9ce0eb36';

  TestData::setShopId($shop_id);
  TestData::setShopKey($shop_key);
  TestData::setShopId3d($shop_id_3d);
  TestData::setShopKey3d($shop_key_3d);
}


require_once(dirname(__FILE__) . '/../lib/ecomcharge.php');
// Throw an exception on any error
function exception_error_handler($errno, $errstr, $errfile, $errline) {
  throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler('exception_error_handler');
error_reporting(E_ALL | E_STRICT);

require_once(dirname(__FILE__) . '/../lib/ecomcharge.php');


eComCharge\Logger::getInstance()->setLogLevel(eComCharge\Logger::INFO);

require_once(dirname(__FILE__) . '/eComCharge/AuthorizationTest.php');
require_once(dirname(__FILE__) . '/eComCharge/PaymentTest.php');
require_once(dirname(__FILE__) . '/eComCharge/CaptureTest.php');
require_once(dirname(__FILE__) . '/eComCharge/VoidTest.php');
require_once(dirname(__FILE__) . '/eComCharge/RefundTest.php');
require_once(dirname(__FILE__) . '/eComCharge/CreditTest.php');
require_once(dirname(__FILE__) . '/eComCharge/GetPaymentPageTokenTest.php');
require_once(dirname(__FILE__) . '/eComCharge/QueryByUidTest.php');
require_once(dirname(__FILE__) . '/eComCharge/QueryByTrackingIdTest.php');
require_once(dirname(__FILE__) . '/eComCharge/QueryByTokenTest.php');
require_once(dirname(__FILE__) . '/eComCharge/WebhookTest.php');
require_once(dirname(__FILE__) . '/eComCharge/GatewayExceptionTest.php');
require_once(dirname(__FILE__) . '/eComCharge/CreditCardTokenizationTest.php');
?>
