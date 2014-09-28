<?php
class CreditCardTokenizationTest extends UnitTestCase {

  public function test_buildRequestMessage() {
    $token = $this->getTestObject();
    $arr = array(
      'request' => array(
          'number' => '4200000000000000',
          'holder' => 'John Smith',
          'exp_month' => '02',
          'exp_year' => '2030',
          'token' => ''
        )
    );


    $reflection = new ReflectionClass( 'eComCharge\CardToken');
    $method = $reflection->getMethod('_buildRequestMessage');
    $method->setAccessible(true);

    $request = $method->invoke($token, '_buildRequestMessage');

    $this->assertEqual($arr, $request);
  }

  public function test_endpoint() {

    $token = $this->getTestObjectInstance();

    $reflection = new ReflectionClass('eComCharge\CardToken');
    $method = $reflection->getMethod('_endpoint');
    $method->setAccessible(true);
    $url = $method->invoke($token, '_endpoint');

    $this->assertEqual($url, 'https://processing.ecomcharge.com/credit_cards');

  }

  public function test_successTokenCreationUpdateAndAuthorization() {
    $token = $this->getTestObject();

    # create token
    $response = $token->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isSuccess());
    $this->assertEqual($response->card->getCardHolder(), 'John Smith');
    $this->assertEqual($response->card->getBrand(), 'visa');
    $this->assertEqual($response->card->getFirst_1(), '4');
    $this->assertEqual($response->card->getLast_4(), '0000');
    $this->assertEqual($response->card->getCardExpMonth(), '2');
    $this->assertEqual($response->card->getCardExpYear(), '2030');
    $this->assertNotNull($response->card->getCardToken());

    # update token
    $token->card->setCardExpMonth(1);
    $token->card->setCardHolder('John Doe');
    $old_token = $response->card->getCardToken();
    $token->card->setCardToken($old_token);
    $token->card->setCardNumber(NULL);

    $response2 = $token->submit();
    $this->assertEqual($response2->card->getCardHolder(), 'John Doe');
    $this->assertEqual($response2->card->getBrand(), 'visa');
    $this->assertEqual($response2->card->getFirst_1(), '4');
    $this->assertEqual($response2->card->getLast_4(), '0000');
    $this->assertEqual($response2->card->getCardExpMonth(), '1');
    $this->assertEqual($response2->card->getCardExpYear(), '2030');
    $this->assertNotNull($response2->card->getCardToken());
    $this->assertNotEqual($response2->card->getCardToken(), $old_token);

    # make authorization with token
    $amount = rand(0,10000) / 100;

    $auth = $this->getAuthorizationTestObject();

    $auth->money->setAmount($amount);
    $cents = $auth->money->getCents();

    $auth->card->setCardToken($response2->card->getCardToken());
    $auth->card->setCardCvc('123');

    $response3 = $auth->submit();
    $this->assertTrue($response3->isValid());
    $this->assertTrue($response3->isSuccess());
    $this->assertEqual($response3->getMessage(), 'Successfully processed');
    $this->assertNotNull($response3->getUid());
    $this->assertEqual($response3->getStatus(), 'successful');
    $this->assertEqual($cents, $response3->getResponse()->transaction->amount);

  }

  protected function getTestObject($threed = false) {

    $transaction = $this->getTestObjectInstance($threed);

    $transaction->card->setCardNumber('4200000000000000');
    $transaction->card->setCardHolder('John Smith');
    $transaction->card->setCardExpMonth(2);
    $transaction->card->setCardExpYear(2030);

    return $transaction;
  }

  protected function getAuthorizationTestObject($threed = false) {

    $transaction = $this->getAuthorizationTestObjectInstance($threed);

    $transaction->money->setCurrency('EUR');
    $transaction->setDescription('test');
    $transaction->setTrackingId('my_custom_variable');


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

    return new eComCharge\CardToken($id, $key);
  }

  protected function getAuthorizationTestObjectInstance($threed = false) {
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
