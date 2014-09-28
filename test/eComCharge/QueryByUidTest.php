<?php
class QueryByUidTest extends UnitTestCase {

  public function test_setUid() {
    $q = $this->getTestObjectInstance();

    $q->setUid('123456');

    $this->assertEqual($q->getUid(), '123456');
  }

  public function test_endpoint() {

    $q = $this->getTestObjectInstance();
    $q->setUid('1234');

    $reflection = new ReflectionClass('eComCharge\QueryByUid');
    $method = $reflection->getMethod('_endpoint');
    $method->setAccessible(true);
    $url = $method->invoke($q, '_endpoint');

    $this->assertEqual($url, 'https://processing.ecomcharge.com/transactions/1234');

  }

  public function test_queryRequest() {
    $amount = rand(0,10000);

    $parent = $this->runParentTransaction($amount);

    $q = $this->getTestObjectInstance();

    $q->setUid($parent->getUid());

    $response = $q->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isSuccess());
    $this->assertNotNull($response->getUid());
    $this->assertEqual($parent->getUid(), $response->getUid());
  }

  public function test_queryResponseForUnknownUid() {
    $q = $this->getTestObjectInstance();

    $q->setUid('1234567890qwerty');

    $response = $q->submit();

    $this->assertTrue($response->isValid());

    $this->assertEqual($response->getMessage(), 'Record not found');
  }

  protected function runParentTransaction($amount = 10.00 ) {
    authorizeFromEnv();

    $transaction = new eComCharge\Payment(TestData::getShopId(), TestData::getShopKey());

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

  protected function getTestObjectInstance() {
    authorizeFromEnv();

    $id = TestData::getShopId();
    $key =  TestData::getShopKey();

    return new eComCharge\QueryByUid($id, $key);
  }
}
?>
