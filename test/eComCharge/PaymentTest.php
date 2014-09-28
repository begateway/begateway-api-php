<?php
class PaymentTest extends UnitTestCase {

  public function test_setDescription() {

    $auth = $this->getTestObjectInstance();

    $description = 'Test description';

    $auth->setDescription($description);

    $this->assertEqual($auth->getDescription(), $description);
  }

  public function test_setTrackingId() {

    $auth = $this->getTestObjectInstance();

    $tracking_id = 'Test tracking_id';

    $auth->setTrackingId($tracking_id);
    $this->assertEqual($auth->getTrackingId(), $tracking_id);
  }

  public function test_setNotificationUrl() {

    $auth = $this->getTestObjectInstance();

    $url = 'http://www.example.com';

    $auth->setNotificationUrl($url);

    $this->assertEqual($auth->getNotificationUrl(), $url);
  }

  public function test_setReturnUrl() {

    $auth = $this->getTestObjectInstance();

    $url = 'http://www.example.com';

    $auth->setReturnUrl($url);

    $this->assertEqual($auth->getReturnUrl(), $url);

  }

  public function test_endpoint() {

    $auth = $this->getTestObjectInstance();

    $reflection = new ReflectionClass('eComCharge\Payment');
    $method = $reflection->getMethod('_endpoint');
    $method->setAccessible(true);
    $url = $method->invoke($auth, '_endpoint');

    $this->assertEqual($url, 'https://processing.ecomcharge.com/transactions/payments');

  }

  public function test_buildRequestMessage() {
    $auth = $this->getTestObject();
    $arr = array(
      'request' => array(
        'amount' => 1233,
        'currency' => 'EUR',
        'description' => 'test',
        'tracking_id' => 'my_custom_variable',
        'notification_url' => '',
        'return_url' => '',
        'credit_card' => array(
          'number' => '4200000000000000',
          'verification_value' => '123',
          'holder' => 'John Doe',
          'exp_month' => '01',
          'exp_year' => '2030',
          'token' => '',
          'skip_three_d_secure_verification' => '',
        ),

        'customer' => array(
          'ip' => '127.0.0.1',
          'email' => 'john@example.com',
        ),

        'billing_address' => array(
          'first_name' => 'John',
          'last_name' => 'Doe',
          'country' => 'LV',
          'city' => 'Riga',
          'state' => '',
          'zip' => 'LV-1082',
          'address' => 'Demo str 12',
          'phone' => ''
        )
      )
    );

    $reflection = new ReflectionClass( 'eComCharge\Payment');
    $method = $reflection->getMethod('_buildRequestMessage');
    $method->setAccessible(true);

    $request = $method->invoke($auth, '_buildRequestMessage');

    $this->assertEqual($arr, $request);
  }


  public function test_successPayment() {
    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);
    $cents = $auth->money->getCents();

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isSuccess());
    $this->assertEqual($response->getMessage(), 'Successfully processed');
    $this->assertNotNull($response->getUid());
    $this->assertEqual($response->getStatus(), 'successful');
    $this->assertEqual($cents, $response->getResponse()->transaction->amount);

  }

  public function test_incompletePayment() {
    $auth = $this->getTestObject(true);

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);
    $auth->card->setCardNumber('4012001037141112');
    $cents = $auth->money->getCents();

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isIncomplete());
    $this->assertFalse($response->getMessage());
    $this->assertNotNull($response->getUid());
    $this->assertNotNull($response->getResponse()->transaction->redirect_url);
    $this->assertTrue(preg_match('/process/', $response->getResponse()->transaction->redirect_url));
    $this->assertEqual($response->getStatus(), 'incomplete');
    $this->assertEqual($cents, $response->getResponse()->transaction->amount);

  }

  public function test_failedPayment() {
    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);
    $cents = $auth->money->getCents();
    $auth->card->setCardExpMonth(10);

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isFailed());
    $this->assertEqual($response->getMessage(), 'Payment was declined');
    $this->assertNotNull($response->getUid());
    $this->assertEqual($response->getStatus(), 'failed');
    $this->assertEqual($cents, $response->getResponse()->transaction->amount);

  }

  protected function getTestObject($threed = false) {

    $transaction = $this->getTestObjectInstance($threed);

    $transaction->money->setAmount(12.33);
    $transaction->money->setCurrency('EUR');
    $transaction->setDescription('test');
    $transaction->setTrackingId('my_custom_variable');

    $transaction->card->setCardNumber('4200000000000000');
    $transaction->card->setCardHolder('John Doe');
    $transaction->card->setCardExpMonth(1);
    $transaction->card->setCardExpYear(2030);
    $transaction->card->setCardCvc('123');

    $transaction->customer->setFirstName('John');
    $transaction->customer->setLastName('Doe');
    $transaction->customer->setCountry('LV');
    $transaction->customer->setAddress('Demo str 12');
    $transaction->customer->setCity('Riga');
    $transaction->customer->setZip('LV-1082');
    $transaction->customer->setIp('127.0.0.1');
    $transaction->customer->setEmail('john@example.com');

    return $transaction;
  }

  protected function getTestObjectInstance($threed = false) {
    authorizeFromEnv();

    $id = TestData::getShopId();
    $key =  TestData::getShopKey();

    if ($threed) {
      $id = TestData::getShopId3d();
      $key =  TestData::getShopKey3d();
    }

    return new eComCharge\Payment($id, $key);
  }


}
?>
