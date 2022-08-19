<?php

declare(strict_types=1);

namespace Tests\BeGateway;

use BeGateway\Product;
use BeGateway\Settings;
use ReflectionClass;
use Tests\AbstractTestCase;

class ProductTest extends AbstractTestCase
{
    public function testSetDescription(): void
    {
        $auth = $this->getTestObjectInstance();
        $description = 'Test description';
        $auth->setDescription($description);
        $this->assertEquals($auth->getDescription(), $description);
    }

    public function testSetName(): void
    {
        $auth = $this->getTestObjectInstance();
        $name = 'Test name';
        $auth->setName($name);
        $this->assertEquals($auth->getName(), $name);
    }

    public function testSetExpiryDate(): void
    {
        $auth = $this->getTestObjectInstance();
        $date = '2020-12-30 23:21:46';
        $date_iso8601 = date('c', strtotime($date));
        $auth->setExpiryDate($date);
        $this->assertEquals($auth->getExpiryDate(), $date_iso8601);

        $date = null;
        $auth->setExpiryDate($date);
        $this->assertEquals(null, $auth->getExpiryDate());
    }

    public function testSetUrls(): void
    {
        $auth = $this->getTestObjectInstance();

        $url = 'http://www.example.com';

        $auth->setNotificationUrl($url . '/n');
        $auth->setReturnUrl($url . '/r');
        $auth->setSuccessUrl($url . '/s');
        $auth->setFailUrl($url . '/f');

        $this->assertEquals($auth->getNotificationUrl(), $url . '/n');
        $this->assertEquals($auth->getSuccessUrl(), $url . '/s');
        $this->assertEquals($auth->getReturnUrl(), $url . '/r');
        $this->assertEquals($auth->getFailUrl(), $url . '/f');
    }

    public function testVisible(): void
    {
        $auth = $this->getTestObjectInstance();
        $auth->setPhoneVisible();
        $auth->setAddressVisible();

        $this->assertEquals([], array_diff($auth->getVisibleFields(), ['phone', 'address']));

        $auth->unsetAddressVisible();

        $this->assertEquals([], array_diff($auth->getVisibleFields(), ['phone']));
    }

    public function testTransactionType(): void
    {
        $auth = $this->getTestObjectInstance();
        $auth->setAuthorizationTransactionType();

        $this->assertEquals('authorization', $auth->getTransactionType());
    }

    public function testSetTestMode(): void
    {
        $auth = $this->getTestObjectInstance();
        $this->assertFalse($auth->getTestMode());
        $auth->setTestMode();
        $this->assertTrue($auth->getTestMode());
        $auth->setTestMode(false);
        $this->assertFalse($auth->getTestMode());
    }

    public function testBuildRequestMessage(): void
    {
        $auth = $this->getTestObject();

        $arr = [
            'transaction_type' => 'payment',
            'test' => true,
            'amount' => 1233,
            'currency' => 'EUR',
            'name' => 'name',
            'description' => 'test',
            'infinite' => true,
            'immortal' => false,
            'expired_at' => '2030-12-30T21:21:46+00:00',
            'additional_data' => [
                'receipt_text' => [],
                'contract' => [],
                'meta' => [],
                'fiscalization' => [],
                'platform_data' => 'beGateway',
                'integration_data' => '1.1.1',
            ],
            'success_url' => 'http://www.example.com/s',
            'fail_url' => 'http://www.example.com/f',
            'notification_url' => 'http://www.example.com/n',
            'return_url' => 'http://www.example.com/r',
            'language' => 'zh',
            'visible' => [],
        ];

        $reflection = new ReflectionClass('BeGateway\Product');
        $method = $reflection->getMethod('_buildRequestMessage');
        $method->setAccessible(true);

        $auth->additional_data->setPlatformData('beGateway');
        $auth->additional_data->setIntegrationData('1.1.1');

        $request = $method->invoke($auth, '_buildRequestMessage');
        $this->assertEquals($arr, $request);

        $arr['test'] = false;
        $auth->setTestMode(false);

        $request = $method->invoke($auth, '_buildRequestMessage');
        $this->assertEquals($arr, $request);

        $arr['quantity'] = 5;
        $arr['infinite'] = false;
        $auth->setQuantity(5);

        $request = $method->invoke($auth, '_buildRequestMessage');
        $this->assertEquals($arr, $request);
    }

    public function testEndpoint(): void
    {
        $auth = $this->getTestObjectInstance();

        $reflection = new ReflectionClass('BeGateway\Product');
        $method = $reflection->getMethod('_endpoint');
        $method->setAccessible(true);
        $url = $method->invoke($auth, '_endpoint');

        $this->assertEquals($url, Settings::$apiBase . '/products');
    }

    public function testGetPayLink(): void
    {
        $auth = $this->getTestObject();

        $amount = rand(0, 10000) / 100;

        $auth->money->setAmount($amount);

        $response = $auth->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertNotNull($response->getPayLink());
        $this->assertEquals(
            Settings::$checkoutBase . '/v2/confirm_order/' . $response->getId() . '/' . Settings::$shopId,
            $response->getPayLink()
        );
    }

    public function testGetPayUrl(): void
    {
        $auth = $this->getTestObject();

        $amount = rand(0, 10000) / 100;

        $auth->money->setAmount($amount);

        $response = $auth->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertNotNull($response->getPayUrl());
        $this->assertEquals(
            Settings::$apiBase . '/products/' . $response->getId() . '/pay',
            $response->getPayUrl()
        );
    }

    public function testErrorTokenRequest(): void
    {
        $auth = $this->getTestObject();

        $auth->money->setAmount(0);
        $auth->setDescription('');

        $response = $auth->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isError());
    }

    private function getTestObject(): Product
    {
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

    private function getTestObjectInstance(): Product
    {
        self::authorizeFromEnv();

        return new Product();
    }
}
