<?php
namespace BeGateway;

class WebhookTest extends TestCase {

  public function test_WebhookIsSentWithCorrectCredentials() {
    $w = $this->getTestObjectInstance();
    $s = Settings::$shopId;
    $k = Settings::$shopKey;

    $_SERVER['PHP_AUTH_USER'] = $s;
    $_SERVER['PHP_AUTH_PW'] = $k;

    $this->assertTrue($w->isAuthorized());

    $this->_clearAuthData();
  }

  public function test_WebhookIsSentWithIncorrectCredentials() {
    $w = $this->getTestObjectInstance();

    $_SERVER['PHP_AUTH_USER'] = '123';
    $_SERVER['PHP_AUTH_PW'] = '321';

    $this->assertFalse($w->isAuthorized());

    $this->_clearAuthData();
  }

  public function test_WebhookIsSentWithCorrectCredentialsWhenHttpAuthorization() {
    $w = $this->getTestObjectInstance();
    $s = Settings::$shopId;
    $k = Settings::$shopKey;

    $_SERVER['HTTP_AUTHORIZATION'] = 'Basic ' . base64_encode($s . ':' . $k);

    $this->assertTrue($w->isAuthorized());

    $this->_clearAuthData();
  }

  public function test_WebhookIsSentWithCorrectCredentialsWhenRedirectHttpAuthorization() {
    $w = $this->getTestObjectInstance();
    $s = Settings::$shopId;
    $k = Settings::$shopKey;

    $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] = 'Basic ' . base64_encode($s . ':' . $k);;

    $this->assertTrue($w->isAuthorized());

    $this->_clearAuthData();
  }

  public function test_WebhookIsSentWithIncorrectCredentialsWhenHttpAuthorization() {
    $_SERVER['HTTP_AUTHORIZATION'] = 'Basic QWxhZGRpbjpPcGVuU2VzYW1l';

    $w = $this->getTestObjectInstance();

    $this->assertFalse($w->isAuthorized());

    $this->_clearAuthData();
  }

  public function test_WebhookIsSentWithIncorrectCredentialsWhenRedirectHttpAuthorization() {
    $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] = 'Basic QWxhZGRpbjpPcGVuU2VzYW1l';

    $w = $this->getTestObjectInstance();

    $this->assertFalse($w->isAuthorized());

    $this->_clearAuthData();
  }

  public function test_WebhookIsSentWithCorrectCredentialsWhenContentSignature() {
    $w = $this->getTestObjectInstance();
    $keys = $this->_get_rsa_keys();

    Settings::$shopPubKey = $keys['public_key'];

    $json = $this->webhookMessage();
    openssl_sign($json, $signature, $keys['private_key'], OPENSSL_ALGO_SHA256);

    $_SERVER['HTTP_CONTENT_SIGNATURE'] = base64_encode($signature);

    $reflection = new \ReflectionClass('BeGateway\Webhook');
    $property = $reflection->getProperty('_raw_response');
    $property->setAccessible(true);
    $property->setValue($w,$json);

    $this->assertTrue($w->isAuthorized());

    $this->_clearAuthData();
  }

  public function test_WebhookIsSentWithIncorrectCredentialsWhenContentSignature() {
    $w = $this->getTestObjectInstance();
    $keys = $this->_get_rsa_keys();

    Settings::$shopPubKey = $keys['public_key'];

    $json = $this->webhookMessage();
    openssl_sign($json, $signature, $keys['private_key'], OPENSSL_ALGO_SHA256);

    $_SERVER['HTTP_CONTENT_SIGNATURE'] = base64_encode($signature);

    $reflection = new \ReflectionClass('BeGateway\Webhook');
    $property = $reflection->getProperty('_raw_response');
    $property->setAccessible(true);
    $property->setValue($w,$this->webhookMessage('failed'));

    $this->assertFalse($w->isAuthorized());

    $this->_clearAuthData();
  }

  public function test_RequestIsValidAndItIsSuccess() {
    $w = $this->getTestObjectInstance();

    $reflection = new \ReflectionClass('BeGateway\Webhook');
    $property = $reflection->getProperty('_response');
    $property->setAccessible(true);
    $property->setValue($w,json_decode($this->webhookMessage()));

    $this->assertTrue($w->isValid());
    $this->assertTrue($w->isSuccess());
    $this->assertEqual($w->getMessage(), 'Successfully processed');
    $this->assertNotNull($w->getUid());
    $this->assertEqual($w->getPaymentMethod(), 'credit_card');
  }

  public function test_RequestIsValidAndItIsFailed() {
    $w = $this->getTestObjectInstance();

    $reflection = new \ReflectionClass('BeGateway\Webhook');
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

    $reflection = new \ReflectionClass('BeGateway\Webhook');
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

    $reflection = new \ReflectionClass('BeGateway\Webhook');
    $property = $reflection->getProperty('_response');
    $property->setAccessible(true);
    $property->setValue($w,json_decode(''));

    $this->assertFalse($w->isValid());
  }

  protected function getTestObjectInstance() {
    self::authorizeFromEnv();

    return new Webhook();
  }

  private function _clearAuthData() {
    unset($_SERVER['PHP_AUTH_USER']);
    unset($_SERVER['PHP_AUTH_PW']);
    unset($_SERVER['HTTP_AUTHORIZATION']);
    unset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
    unset($_SERVER['HTTP_CONTENT_SIGNATURE']);
  }

  private function _get_rsa_keys() {
    $config = array(
      "digest_alg" => "sha256",
      "private_key_bits" => 2048,
      "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );

    $res = openssl_pkey_new($config);
    openssl_pkey_export($res, $privKey);

    $pubKey = openssl_pkey_get_details($res);
    $pubKey = $pubKey["key"];
    $pubKey = str_replace(
      array('-----BEGIN PUBLIC KEY-----','-----END PUBLIC KEY-----', "\n"),
      '',
      $pubKey
    );

    return array(
      'private_key' => $privKey,
      'public_key'  => $pubKey
    );
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
      "type":"payment",
      "payment_method_type":"credit_card"
   }
}
EOD;
  }
}
?>
