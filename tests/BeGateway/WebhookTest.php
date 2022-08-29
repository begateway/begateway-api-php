<?php

declare(strict_types=1);

namespace Tests\BeGateway;

use BeGateway\Settings;
use BeGateway\Webhook;
use ReflectionClass;
use Tests\AbstractTestCase;

class WebhookTest extends AbstractTestCase
{
    public function testWebhookIsSentWithCorrectCredentials(): void
    {
        $w = $this->getTestObjectInstance();
        $s = Settings::$shopId;
        $k = Settings::$shopKey;

        $_SERVER['PHP_AUTH_USER'] = $s;
        $_SERVER['PHP_AUTH_PW'] = $k;

        $this->assertTrue($w->isAuthorized());

        $this->_clearAuthData();
    }

    public function testWebhookIsSentWithIncorrectCredentials(): void
    {
        $w = $this->getTestObjectInstance();

        $_SERVER['PHP_AUTH_USER'] = '123';
        $_SERVER['PHP_AUTH_PW'] = '321';

        $this->assertFalse($w->isAuthorized());

        $this->_clearAuthData();
    }

    public function testWebhookIsSentWithCorrectCredentialsWhenHttpAuthorization(): void
    {
        $w = $this->getTestObjectInstance();
        $s = Settings::$shopId;
        $k = Settings::$shopKey;

        $_SERVER['HTTP_AUTHORIZATION'] = 'Basic ' . base64_encode($s . ':' . $k);

        $this->assertTrue($w->isAuthorized());

        $this->_clearAuthData();
    }

    public function testWebhookIsSentWithCorrectCredentialsWhenRedirectHttpAuthorization(): void
    {
        $w = $this->getTestObjectInstance();
        $s = Settings::$shopId;
        $k = Settings::$shopKey;

        $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] = 'Basic ' . base64_encode($s . ':' . $k);

        $this->assertTrue($w->isAuthorized());

        $this->_clearAuthData();
    }

    public function testWebhookIsSentWithIncorrectCredentialsWhenHttpAuthorization(): void
    {
        $_SERVER['HTTP_AUTHORIZATION'] = 'Basic QWxhZGRpbjpPcGVuU2VzYW1l';

        $w = $this->getTestObjectInstance();

        $this->assertFalse($w->isAuthorized());

        $this->_clearAuthData();
    }

    public function testWebhookIsSentWithIncorrectCredentialsWhenRedirectHttpAuthorization(): void
    {
        $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] = 'Basic QWxhZGRpbjpPcGVuU2VzYW1l';

        $w = $this->getTestObjectInstance();

        $this->assertFalse($w->isAuthorized());

        $this->_clearAuthData();
    }

    public function testWebhookIsSentWithCorrectCredentialsWhenContentSignature(): void
    {
        $w = $this->getTestObjectInstance();
        $keys = $this->_get_rsa_keys();

        Settings::$shopPubKey = $keys['public_key'];

        $json = $this->webhookMessage();
        openssl_sign($json, $signature, $keys['private_key'], OPENSSL_ALGO_SHA256);

        $_SERVER['HTTP_CONTENT_SIGNATURE'] = base64_encode($signature);

        $reflection = new ReflectionClass('BeGateway\Webhook');
        $property = $reflection->getProperty('_raw_response');
        $property->setAccessible(true);
        $property->setValue($w, $json);

        $this->assertTrue($w->isAuthorized());

        $this->_clearAuthData();
    }

    public function testWebhookIsSentWithIncorrectCredentialsWhenContentSignature(): void
    {
        $w = $this->getTestObjectInstance();
        $keys = $this->_get_rsa_keys();

        Settings::$shopPubKey = $keys['public_key'];

        $json = $this->webhookMessage();
        openssl_sign($json, $signature, $keys['private_key'], OPENSSL_ALGO_SHA256);

        $_SERVER['HTTP_CONTENT_SIGNATURE'] = base64_encode($signature);

        $reflection = new ReflectionClass('BeGateway\Webhook');
        $property = $reflection->getProperty('_raw_response');
        $property->setAccessible(true);
        $property->setValue($w, $this->webhookMessage('failed'));

        $this->assertFalse($w->isAuthorized());

        $this->_clearAuthData();
    }

    public function testRequestIsValidAndItIsSuccess(): void
    {
        $w = $this->getTestObjectInstance();

        $reflection = new ReflectionClass('BeGateway\Webhook');
        $property = $reflection->getProperty('_response');
        $property->setAccessible(true);
        $property->setValue($w, json_decode($this->webhookMessage()));

        $this->assertTrue($w->isValid());
        $this->assertTrue($w->isSuccess());
        $this->assertEquals($w->getMessage(), 'Successfully processed');
        $this->assertNotNull($w->getUid());
        $this->assertEquals($w->getPaymentMethod(), 'credit_card');
    }

    public function testRequestIsValidAndItIsFailed(): void
    {
        $w = $this->getTestObjectInstance();

        $reflection = new ReflectionClass('BeGateway\Webhook');
        $property = $reflection->getProperty('_response');
        $property->setAccessible(true);
        $property->setValue($w, json_decode($this->webhookMessage('failed')));

        $this->assertTrue($w->isValid());
        $this->assertTrue($w->isFailed());
        $this->assertEquals('Payment was declined', $w->getMessage());
        $this->assertNotNull($w->getUid());
        $this->assertEquals('failed', $w->getStatus());
    }

    public function testRequestIsValidAndItIsTest(): void
    {
        $w = $this->getTestObjectInstance();

        $reflection = new ReflectionClass('BeGateway\Webhook');
        $property = $reflection->getProperty('_response');
        $property->setAccessible(true);
        $property->setValue($w, json_decode($this->webhookMessage('failed', true)));

        $this->assertTrue($w->isValid());
        $this->assertTrue($w->isFailed());
        $this->assertTrue($w->isTest());
        $this->assertEquals('Payment was declined', $w->getMessage());
        $this->assertNotNull($w->getUid());
        $this->assertEquals('failed', $w->getStatus());
    }

    public function test_NotValidRequestReceived(): void
    {
        $w = $this->getTestObjectInstance();

        $reflection = new ReflectionClass('BeGateway\Webhook');
        $property = $reflection->getProperty('_response');
        $property->setAccessible(true);
        $property->setValue($w, json_decode(''));

        $this->assertFalse($w->isValid());
    }

    private function getTestObjectInstance(): Webhook
    {
        self::authorizeFromEnv();

        return new Webhook();
    }

    private function _clearAuthData(): void
    {
        unset($_SERVER['PHP_AUTH_USER']);
        unset($_SERVER['PHP_AUTH_PW']);
        unset($_SERVER['HTTP_AUTHORIZATION']);
        unset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
        unset($_SERVER['HTTP_CONTENT_SIGNATURE']);
    }

    private function _get_rsa_keys(): array
    {
        $config = [
            'digest_alg' => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privKey);

        $pubKey = openssl_pkey_get_details($res);
        $pubKey = $pubKey['key'];
        $pubKey = str_replace(
            ['-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----', "\n"],
            '',
            $pubKey
        );

        return [
            'private_key' => $privKey,
            'public_key' => $pubKey,
        ];
    }

    private function webhookMessage($status = 'successful', $test = true): string
    {
        if ($status == 'successful') {
            $message = 'Successfully processed';
            $p_message = 'Payment was approved';
        } else {
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
