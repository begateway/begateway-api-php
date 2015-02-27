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

require_once(dirname(__FILE__) . '/../lib/ecomcharge.php');
// Throw an exception on any error
function exception_error_handler($errno, $errstr, $errfile, $errline) {
  throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler('exception_error_handler');
error_reporting(E_ALL | E_STRICT);

require_once(dirname(__FILE__) . '/../lib/ecomcharge.php');


eComCharge\Logger::getInstance()->setLogLevel(eComCharge\Logger::INFO);

require_once(dirname(__FILE__) . '/eComCharge/TestCase.php');
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
