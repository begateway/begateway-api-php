<?php
class WebhookTest extends UnitTestCase {

  public function test_WebhookIsSentWithCorrectCredentials() {
    $w = $this->getTestObjectInstance();
    $s = TestData::getShopId();
    $k = TestData::getShopKey();

    $_SERVER['PHP_AUTH_USER'] = $s;
    $_SERVER['PHP_AUTH_PW'] = $k;

    $this->assertTrue($w->isAuthorized());
  }
  public function test_WebhookIsSentWithIncorrectCredentials() {
    $w = $this->getTestObjectInstance();
    $s = '123';
    $k = '123';

    $_SERVER['PHP_AUTH_USER'] = $s;
    $_SERVER['PHP_AUTH_PW'] = $k;

    $this->assertFalse($w->isAuthorized());
  }

  public function test_RequestIsValidAndItIsSuccess() {
    $w = $this->getTestObjectInstance();

    $reflection = new ReflectionClass('eComCharge\Webhook');
    $property = $reflection->getProperty('_response');
    $property->setAccessible(true);
    $property->setValue($w,json_decode($this->webhookMessage()));

    $this->assertTrue($w->isValid());
    $this->assertTrue($w->isSuccess());
    $this->assertEqual($w->getMessage(), 'Successfully processed');
    $this->assertNotNull($w->getUid());
  }


  public function test_RequestIsValidAndItIsFailed() {
    $w = $this->getTestObjectInstance();

    $reflection = new ReflectionClass('eComCharge\Webhook');
    $property = $reflection->getProperty('_response');
    $property->setAccessible(true);
    $property->setValue($w,json_decode($this->webhookMessage('failed')));

    $this->assertTrue($w->isValid());
    $this->assertTrue($w->isFailed());
    $this->assertEqual($w->getMessage(), 'Payment was declined');
    $this->assertNotNull($w->getUid());
    $this->assertEqual($w->getStatus(), 'failed');

  }

  public function test_RequestIsValidAndItIsTest() {
    $w = $this->getTestObjectInstance();

    $reflection = new ReflectionClass('eComCharge\Webhook');
    $property = $reflection->getProperty('_response');
    $property->setAccessible(true);
    $property->setValue($w,json_decode($this->webhookMessage('failed', true)));

    $this->assertTrue($w->isValid());
    $this->assertTrue($w->isFailed());
    $this->assertTrue($w->isTest());
    $this->assertEqual($w->getMessage(), 'Payment was declined');
    $this->assertNotNull($w->getUid());
    $this->assertEqual($w->getStatus(), 'failed');

  }

  public function test_NotValidRequestReceived() {
    $w = $this->getTestObjectInstance();

    $reflection = new ReflectionClass('eComCharge\Webhook');
    $property = $reflection->getProperty('_response');
    $property->setAccessible(true);
    $property->setValue($w,json_decode(''));

    $this->assertFalse($w->isValid());
  }

  protected function getTestObjectInstance() {
    authorizeFromEnv();

    $id = TestData::getShopId();
    $key =  TestData::getShopKey();

    return new eComCharge\Webhook($id, $key);
  }

  private function webhookMessage($status = 'successful', $test = true ) {
    if ($status == 'successful') {
      $message = 'Successfully processed';
      $p_message = 'Payment was approved';
    }else{
      $message = 'Payment was declined';
      $p_message = 'Payment was declined';
    }

    return <<<EOD
{
   "transaction":{
      "customer":{
         "ip":"127.0.0.1",
         "email":"john@example.com"
      },
      "credit_card":{
         "holder":"John Doe",
         "stamp":"3709786942408b77017a3aac8390d46d77d181e34554df527a71919a856d0f28",
         "token":"d46d77d181e34554df527a71919a856d0f283709786942408b77017a3aac8390",
         "brand":"visa",
         "last_4":"0000",
         "first_1":"4",
         "exp_month":5,
         "exp_year":2015
      },
      "billing_address":{
         "first_name":"John",
         "last_name":"Doe",
         "address":"1st Street",
         "country":"US",
         "city":"Denver",
         "zip":"96002",
         "state":"CO",
         "phone":null
      },
      "payment":{
         "auth_code":"654321",
         "bank_code":"05",
         "rrn":"999",
         "ref_id":"777888",
         "message":"$p_message",
         "gateway_id":317,
         "billing_descriptor":"TEST GATEWAY BILLING DESCRIPTOR",
         "status":"$status"
      },
      "uid":"1-310b0da80b",
      "status":"$status",
      "message":"$message",
      "amount":100,
      "test":$test,
      "currency":"USD",
      "description":"Test order",
      "type":"payment"
   }
}
EOD;
  }
}
?>
