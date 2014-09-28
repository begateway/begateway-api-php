<?php

class CaptureTest extends UnitTestCase {

  public function test_setParentUid() {
    $transaction = $this->getTestObjectInstance();
    $uid = '1234567';

    $transaction->setParentUid($uid);

    $this->assertEqual($uid, $transaction->getParentUid());
  }

  public function test_buildRequestMessage() {
    $transaction = $this->getTestObject();
    $arr = array(
      'request' => array(
        'parent_uid' => '12345678',
        'amount' => 1256
      )
    );

    $reflection = new ReflectionClass( 'eComCharge\Capture' );
    $method = $reflection->getMethod('_buildRequestMessage');
    $method->setAccessible(true);

    $request = $method->invoke($transaction, '_buildRequestMessage');

    $this->assertEqual($arr, $request);
  }

  public function test_endpoint() {

    $auth = $this->getTestObjectInstance();

    $reflection = new ReflectionClass('eComCharge\Capture');
    $method = $reflection->getMethod('_endpoint');
    $method->setAccessible(true);
    $url = $method->invoke($auth, '_endpoint');

    $this->assertEqual($url, 'https://processing.ecomcharge.com/transactions/captures');

  }

  public function test_successCapture() {

    $amount = rand(0,10000);

    $parent = $this->runParentTransaction($amount);

    $transaction = $this->getTestObjectInstance();

    $transaction->money->setAmount($amount);
    $transaction->setParentUid($parent->getUid());

    $t_response = $transaction->submit();

    $this->assertTrue($t_response->isValid());
    $this->assertTrue($t_response->isSuccess());
    $this->assertNotNull($t_response->getUid());
    $this->assertEqual($t_response->getMessage(),'Successfully processed');
    $this->assertEqual($t_response->getResponse()->transaction->parent_uid,$parent->getUid());

  }

  public function test_errorCapture() {
    $amount = rand(0,10000);

    $parent = $this->runParentTransaction($amount);

    $transaction = $this->getTestObjectInstance();

    $transaction->money->setAmount($amount + 1);
    $transaction->setParentUid($parent->getUid());

    $t_response = $transaction->submit();

    $this->assertTrue($t_response->isValid());
    $this->assertTrue($t_response->isError());
    $this->assertTrue(preg_match('/Amount can\'t be greater than/', $t_response->getMessage()));

  }

  protected function runParentTransaction($amount = 10.00 ) {
    authorizeFromEnv();

    $transaction = new eComCharge\Authorization(TestData::getShopId(), TestData::getShopKey());

    $transaction->money->setAmount($amount);
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

    return $transaction->submit();
  }

  protected function getTestObject() {
    $transaction = $this->getTestObjectInstance();

    $transaction->setParentUid('12345678');

    $transaction->money->setAmount(12.56);

    return $transaction;

  }

  protected function getTestObjectInstance() {
    authorizeFromEnv();

    $id = TestData::getShopId();
    $key =  TestData::getShopKey();

    return new eComCharge\Capture($id, $key);
  }
}
?>
