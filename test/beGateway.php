<?php
echo "Running the beGateway bePaid PHP bindings test suite.\n".
     "If you're trying to use the PHP bindings you'll probably want ".
     "to require('lib/beGateway.php'); instead of this file\n\n" .
     "Setup the env variable LOG_LEVEL=DEBUG for more verbose output\n" ;

$ok = @include_once(dirname(__FILE__).'/simpletest/autorun.php');
if (!$ok) {
  echo "MISSING DEPENDENCY: The beGateway API test cases depend on SimpleTest. ".
       "Download it at <http://www.simpletest.org/>, and either install it ".
       "in your PHP include_path or put it in the test/ directory.\n";
  exit(1);
}

require_once(dirname(__FILE__) . '/../lib/beGateway.php');
// Throw an exception on any error
function exception_error_handler($errno, $errstr, $errfile, $errline) {
  throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler('exception_error_handler');
error_reporting(E_ALL | E_STRICT);

require_once(dirname(__FILE__) . '/../lib/beGateway.php');


$log_level = getenv('LOG_LEVEL');

if ($log_level == 'DEBUG') {
  \beGateway\Logger::getInstance()->setLogLevel(\beGateway\Logger::DEBUG);
} else {
  \beGateway\Logger::getInstance()->setLogLevel(\beGateway\Logger::INFO);
}

require_once(dirname(__FILE__) . '/beGateway/TestCase.php');
require_once(dirname(__FILE__) . '/beGateway/MoneyTest.php');
require_once(dirname(__FILE__) . '/beGateway/AuthorizationTest.php');
require_once(dirname(__FILE__) . '/beGateway/PaymentTest.php');
require_once(dirname(__FILE__) . '/beGateway/CaptureTest.php');
require_once(dirname(__FILE__) . '/beGateway/VoidTest.php');
require_once(dirname(__FILE__) . '/beGateway/RefundTest.php');
require_once(dirname(__FILE__) . '/beGateway/CreditTest.php');
require_once(dirname(__FILE__) . '/beGateway/GetPaymentTokenTest.php');
require_once(dirname(__FILE__) . '/beGateway/QueryByUidTest.php');
require_once(dirname(__FILE__) . '/beGateway/QueryByTrackingIdTest.php');
require_once(dirname(__FILE__) . '/beGateway/QueryByTokenTest.php');
require_once(dirname(__FILE__) . '/beGateway/WebhookTest.php');
require_once(dirname(__FILE__) . '/beGateway/GatewayExceptionTest.php');
require_once(dirname(__FILE__) . '/beGateway/CreditCardTokenizationTest.php');
require_once(dirname(__FILE__) . '/beGateway/PaymentMethod/CreditCardTest.php');
require_once(dirname(__FILE__) . '/beGateway/PaymentMethod/EripTest.php');
?>
