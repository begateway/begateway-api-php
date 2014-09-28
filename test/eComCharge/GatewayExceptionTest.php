<?php
class GatewayTransportExceptionTest extends UnitTestCase {

  public function test_networkIssuesHandledCorrectly() {
    $auth = $this->getTestObject();

    $reflection = new ReflectionClass('eComCharge\Authorization');
    $property = $reflection->getProperty('_service_url');
    $property->setAccessible(true);
    $property->setValue($auth, 'https://thedomaindoesntexist.ecomcharge.com');

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);
    $cents = $auth->money->getCents();

    $response = $auth->submit();

    $this->assertTrue($response->isError());
    $this->assertPattern('|Could not resolve host: thedomaindoesntexist.ecomcharge.com|', $response->getMessage());

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
      $key = TestData::getShopKey3d();
    }

    return new eComCharge\Authorization($id, $key);
  }


}
?>
