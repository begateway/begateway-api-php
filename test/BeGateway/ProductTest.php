<?php
namespace BeGateway;

class ProductTest extends TestCase {

  public function test_setDescription() {
    $auth = $this->getTestObjectInstance();
    $description = 'Test description';
    $auth->setDescription($description);
    $this->assertEqual($auth->getDescription(), $description);
  }

  public function test_setName() {
    $auth = $this->getTestObjectInstance();
    $name = 'Test name';
    $auth->setName($name);
    $this->assertEqual($auth->getName(), $name);
  }

  public function test_setExpiryDate() {
    $auth = $this->getTestObjectInstance();
    $date = '2020-12-30 23:21:46';
    $date_iso8601 = date(DATE_ISO8601, strtotime($date));
    $auth->setExpiryDate($date);
    $this->assertEqual($auth->getExpiryDate(), $date_iso8601);

    $date = NULL;
    $auth->setExpiryDate($date);
    $this->assertEqual($auth->getExpiryDate(), NULL);
  }

  public function test_setUrls() {

    $auth = $this->getTestObjectInstance();

    $url = 'http://www.example.com';

    $auth->setNotificationUrl($url . '/n');
    $auth->setReturnUrl($url . '/r');
    $auth->setSuccessUrl($url . '/s');
    $auth->setFailUrl($url . '/f');

    $this->assertEqual($auth->getNotificationUrl(), $url . '/n');
    $this->assertEqual($auth->getSuccessUrl(), $url . '/s');
    $this->assertEqual($auth->getReturnUrl(), $url . '/r');
    $this->assertEqual($auth->getFailUrl(), $url . '/f');
  }

  public function test_visible() {
    $auth = $this->getTestObjectInstance();
    $auth->setPhoneVisible();
    $auth->setAddressVisible();

    $this->assertEqual(array_diff($auth->getVisibleFields(), array( 'phone', 'address' )), array() );

    $auth->unsetAddressVisible();

    $this->assertEqual(array_diff($auth->getVisibleFields(), array( 'phone' )), array() );
  }

  public function test_transaction_type() {
    $auth = $this->getTestObjectInstance();
    $auth->setAuthorizationTransactionType();

    $this->assertEqual($auth->getTransactionType(), 'authorization');
  }

  public function test_setTestMode() {
    $auth = $this->getTestObjectInstance();
    $this->assertFalse($auth->getTestMode());
    $auth->setTestMode(true);
    $this->assertTrue($auth->getTestMode());
    $auth->setTestMode(false);
    $this->assertFalse($auth->getTestMode());
  }

  public function test_buildRequestMessage() {
    $auth = $this->getTestObject();
    $arr = array(
      'transaction_type' => 'payment',
      'test' => true,
      'amount' => 1233,
      'currency' => 'EUR',
      'name' => 'name',
      'description' => 'test',
      'infinite' => true,
      'immortal' => false,
      'expired_at' => '2030-12-30T21:21:46+0000',
      'additional_data' => array(
        'receipt_text' => array(),
        'contract' => array(),
        'meta' => array(),
        'fiscalization' => array()
      ),
      'success_url' => 'http://www.example.com/s',
      'fail_url' => 'http://www.example.com/f',
      'notification_url' => 'http://www.example.com/n',
      'return_url' => 'http://www.example.com/r',
      'language' => 'zh',
      'visible' => array()
    );

    $reflection = new \ReflectionClass( 'BeGateway\Product');
    $method = $reflection->getMethod('_buildRequestMessage');
    $method->setAccessible(true);

    $request = $method->invoke($auth, '_buildRequestMessage');
    $this->assertEqual($arr, $request);

    $arr['test'] = false;
    $auth->setTestMode(false);

    $request = $method->invoke($auth, '_buildRequestMessage');
    $this->assertEqual($arr, $request);

    $arr['quantity'] = 5;
    $arr['infinite'] = false;
    $auth->setQuantity(5);

    $request = $method->invoke($auth, '_buildRequestMessage');
    $this->assertEqual($arr, $request);

  }

  public function test_endpoint() {

    $auth = $this->getTestObjectInstance();

    $reflection = new \ReflectionClass('BeGateway\Product');
    $method = $reflection->getMethod('_endpoint');
    $method->setAccessible(true);
    $url = $method->invoke($auth, '_endpoint');

    $this->assertEqual($url, Settings::$apiBase . '/products');
  }

  public function test_getPayLink() {
    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isSuccess());
    $this->assertNotNull($response->getPayLink());
    $this->assertEqual(
      \BeGateway\Settings::$checkoutBase .
      '/v2/confirm_order/' . $response->getId() . '/' . \BeGateway\Settings::$shopId,
      $response->getPayLink()
    );
  }

  public function test_getPayUrl() {
    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isSuccess());
    $this->assertNotNull($response->getPayUrl());
    $this->assertEqual(
      \BeGateway\Settings::$apiBase .
      '/products/' . $response->getId() . '/pay',
      $response->getPayUrl()
    );
  }

  public function test_errorTokenRequest() {

    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount(0);
    $auth->setDescription('');

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isError());

  }

  protected function getTestObject() {

    $transaction = $this->getTestObjectInstance();

    $url = 'http://www.example.com';

    $transaction->money->setAmount(12.33);
    $transaction->money->setCurrency('EUR');
    $transaction->setPaymentTransactionType();
    $transaction->setName('name');
    $transaction->setDescription('test');
    $transaction->setNotificationUrl($url . '/n');
    $transaction->setSuccessUrl($url . '/s');
    $transaction->setReturnUrl($url . '/r');
    $transaction->setFailUrl($url . '/f');
    $transaction->setLanguage('zh');
    $transaction->setExpiryDate('2030-12-31T00:21:46+0300');
    $transaction->setTestMode(true);

    return $transaction;
  }

  protected function getTestObjectInstance() {
    self::authorizeFromEnv();

    return new Product();
  }
}
