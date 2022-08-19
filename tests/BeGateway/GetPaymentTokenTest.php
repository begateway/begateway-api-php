<?php

declare(strict_types=1);

namespace Tests\BeGateway;

use BeGateway\GetPaymentToken;
use BeGateway\PaymentMethod\CreditCard;
use BeGateway\PaymentMethod\Erip;
use BeGateway\Settings;
use ReflectionClass;
use Tests\AbstractTestCase;

class GetPaymentTokenTest extends AbstractTestCase
{
    public function testSetDescription(): void
    {
        $auth = $this->getTestObjectInstance();
        $description = 'Test description';
        $auth->setDescription($description);
        $this->assertEquals($auth->getDescription(), $description);
    }

    public function testSetTrackingId(): void
    {
        $auth = $this->getTestObjectInstance();
        $tracking_id = 'Test tracking_id';
        $auth->setTrackingId($tracking_id);
        $this->assertEquals($auth->getTrackingId(), $tracking_id);
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
        $auth->setCancelUrl($url . '/c');
        $auth->setSuccessUrl($url . '/s');
        $auth->setDeclineUrl($url . '/d');
        $auth->setFailUrl($url . '/f');

        $this->assertEquals($auth->getNotificationUrl(), $url . '/n');
        $this->assertEquals($auth->getCancelUrl(), $url . '/c');
        $this->assertEquals($auth->getSuccessUrl(), $url . '/s');
        $this->assertEquals($auth->getDeclineUrl(), $url . '/d');
        $this->assertEquals($auth->getFailUrl(), $url . '/f');
    }

    public function testReadonly()
    {
        $auth = $this->getTestObjectInstance();

        $auth->setFirstNameReadonly();
        $auth->setLastNameReadonly();
        $auth->setEmailReadonly();
        $auth->setCityReadonly();

        $this->assertEquals([], array_diff($auth->getReadOnlyFields(), ['first_name', 'last_name', 'email', 'city']));

        $auth->unsetFirstNameReadonly();

        $this->assertEquals([], array_diff($auth->getReadOnlyFields(), ['last_name', 'email', 'city']));
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

        $this->assertEquals($auth->getTransactionType(), 'authorization');
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

    public function testSetAttempts(): void
    {
        $auth = $this->getTestObjectInstance();
        $auth->setAttempts(10);
        $this->assertEquals(10, $auth->getAttempts());
    }

    public function test_buildRequestMessage()
    {
        $auth = $this->getTestObject();

        $arr = [
            'checkout' => [
                'transaction_type' => 'payment',
                'attempts' => 5,
                'test' => true,
                'order' => [
                    'amount' => 1233,
                    'currency' => 'EUR',
                    'description' => 'test',
                    'tracking_id' => 'my_custom_variable',
                    'expired_at' => '2030-12-30T21:21:46+00:00',
                    'additional_data' => [
                        'receipt_text' => [],
                        'contract' => [],
                        'meta' => [],
                        'fiscalization' => [],
                        'platform_data' => 'beGateway',
                        'integration_data' => '1.2.3',
                    ],
                ],
                'settings' => [
                    'success_url' => 'http://www.example.com/s',
                    'cancel_url' => 'http://www.example.com/c',
                    'decline_url' => 'http://www.example.com/d',
                    'fail_url' => 'http://www.example.com/f',
                    'notification_url' => 'http://www.example.com/n',
                    'language' => 'zh',
                    'customer_fields' => [
                        'visible' => [],
                        'read_only' => [],
                    ],
                ],
                'customer' => [
                    'email' => 'john@example.com',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'country' => 'LV',
                    'city' => 'Riga',
                    'state' => '',
                    'zip' => 'LV-1082',
                    'address' => 'Demo str 12',
                    'phone' => '',
                    'birth_date' => '',
                ],
            ],
        ];

        $reflection = new ReflectionClass('BeGateway\GetPaymentToken');
        $method = $reflection->getMethod('_buildRequestMessage');
        $method->setAccessible(true);

        $auth->additional_data->setPlatformData('beGateway');
        $auth->additional_data->setIntegrationData('1.2.3');

        $request = $method->invoke($auth, '_buildRequestMessage');

        $this->assertEquals($arr, $request);

        $arr['checkout']['test'] = false;
        $auth->setTestMode(false);
        $request = $method->invoke($auth, '_buildRequestMessage');

        $this->assertEquals($arr, $request);
    }

    public function testBuildRequestMessageWithErip(): void
    {
        $auth = $this->getTestObject();
        $auth->money->setAmount(100);
        $auth->money->setCurrency('BYN');

        $erip = new Erip([
            'account_number' => '1234',
            'service_no' => '99999999',
            'order_id' => 100001,
            'service_info' => ['Test payment'],
        ]);

        $cc = new CreditCard();

        $auth->addPaymentMethod($erip);
        $auth->addPaymentMethod($cc);

        $arr = [
            'checkout' => [
                'transaction_type' => 'payment',
                'attempts' => 5,
                'test' => true,
                'order' => [
                    'amount' => 10000,
                    'currency' => 'BYN',
                    'description' => 'test',
                    'tracking_id' => 'my_custom_variable',
                    'expired_at' => '2030-12-30T21:21:46+00:00',
                    'additional_data' => [
                        'receipt_text' => [],
                        'contract' => [],
                        'meta' => [],
                        'fiscalization' => [],
                        'platform_data' => null,
                        'integration_data' => null,
                    ],
                ],
                'settings' => [
                    'success_url' => 'http://www.example.com/s',
                    'cancel_url' => 'http://www.example.com/c',
                    'decline_url' => 'http://www.example.com/d',
                    'fail_url' => 'http://www.example.com/f',
                    'notification_url' => 'http://www.example.com/n',
                    'language' => 'zh',
                    'customer_fields' => [
                        'visible' => [],
                        'read_only' => [],
                    ],
                ],
                'customer' => [
                    'email' => 'john@example.com',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'country' => 'LV',
                    'city' => 'Riga',
                    'state' => '',
                    'zip' => 'LV-1082',
                    'address' => 'Demo str 12',
                    'phone' => '',
                    'birth_date' => null,
                ],
                'payment_method' => [
                    'types' => ['erip', 'credit_card'],
                    'erip' => [
                        'account_number' => '1234',
                        'service_no' => '99999999',
                        'order_id' => 100001,
                        'service_info' => ['Test payment'],
                    ],
                    'credit_card' => [],
                ],
            ],
        ];

        $reflection = new ReflectionClass('BeGateway\GetPaymentToken');
        $method = $reflection->getMethod('_buildRequestMessage');
        $method->setAccessible(true);

        $request = $method->invoke($auth, '_buildRequestMessage');

        $this->assertEquals($arr, $request);
    }

    public function testBuildRequestMessageWithCreditCardAndErip(): void
    {
        $auth = $this->getTestObject();
        $auth->money->setAmount(100);
        $auth->money->setCurrency('USD');
        $erip = new Erip(['account_number' => 12345]);
        $cc = new CreditCard();

        $auth->addPaymentMethod($erip);
        $auth->addPaymentMethod($cc);

        $arr = [
            'checkout' => [
                'transaction_type' => 'payment',
                'test' => true,
                'attempts' => 5,
                'order' => [
                    'amount' => 10000,
                    'currency' => 'USD',
                    'description' => 'test',
                    'tracking_id' => 'my_custom_variable',
                    'expired_at' => '2030-12-30T21:21:46+00:00',
                    'additional_data' => [
                        'receipt_text' => [],
                        'contract' => [],
                        'meta' => [],
                        'fiscalization' => [],
                        'platform_data' => null,
                        'integration_data' => null,
                    ],
                ],
                'settings' => [
                    'success_url' => 'http://www.example.com/s',
                    'cancel_url' => 'http://www.example.com/c',
                    'decline_url' => 'http://www.example.com/d',
                    'fail_url' => 'http://www.example.com/f',
                    'notification_url' => 'http://www.example.com/n',
                    'language' => 'zh',
                    'customer_fields' => [
                        'visible' => [],
                        'read_only' => [],
                    ],
                ],
                'customer' => [
                    'email' => 'john@example.com',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'country' => 'LV',
                    'city' => 'Riga',
                    'state' => '',
                    'zip' => 'LV-1082',
                    'address' => 'Demo str 12',
                    'phone' => '',
                    'birth_date' => null,
                ],
                'payment_method' => [
                    'types' => ['erip', 'credit_card'],
                    'credit_card' => [],
                    'erip' => [
                        'order_id' => null,
                        'account_number' => 12345,
                        'service_no' => null,
                    ],
                ],
            ],
        ];

        $reflection = new ReflectionClass('BeGateway\GetPaymentToken');
        $method = $reflection->getMethod('_buildRequestMessage');
        $method->setAccessible(true);

        $request = $method->invoke($auth, '_buildRequestMessage');

        $this->assertEquals($arr, $request);
    }

    public function testEndpoint(): void
    {
        $auth = $this->getTestObjectInstance();

        $reflection = new ReflectionClass('BeGateway\GetPaymentToken');
        $method = $reflection->getMethod('_endpoint');
        $method->setAccessible(true);
        $url = $method->invoke($auth, '_endpoint');

        $this->assertEquals($url, Settings::$checkoutBase . '/ctp/api/checkouts');
    }

    public function testSuccessTokenRequest(): void
    {
        $auth = $this->getTestObject();

        $amount = rand(0, 10000) / 100;

        $auth->money->setAmount($amount);

        $response = $auth->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertNotNull($response->getToken());
    }

    public function testRedirectUrl(): void
    {
        $auth = $this->getTestObject();

        $amount = rand(0, 10000) / 100;

        $auth->money->setAmount($amount);

        $response = $auth->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertNotNull($response->getToken());
        $this->assertNotNull($response->getRedirectUrl());
        $this->assertEquals(
            Settings::$checkoutBase . '/widget/hpp.html?token=' . $response->getToken(),
            $response->getRedirectUrl()
        );

        $this->assertEquals(
            Settings::$checkoutBase . '/widget/hpp.html',
            $response->getRedirectUrlScriptName()
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

    private function getTestObject(): GetPaymentToken
    {
        $transaction = $this->getTestObjectInstance();

        $url = 'http://www.example.com';

        $transaction->money->setAmount(12.33);
        $transaction->money->setCurrency('EUR');
        $transaction->setPaymentTransactionType();
        $transaction->setAttempts(5);
        $transaction->setDescription('test');
        $transaction->setTrackingId('my_custom_variable');
        $transaction->setNotificationUrl($url . '/n');
        $transaction->setCancelUrl($url . '/c');
        $transaction->setSuccessUrl($url . '/s');
        $transaction->setDeclineUrl($url . '/d');
        $transaction->setFailUrl($url . '/f');
        $transaction->setLanguage('zh');
        $transaction->setExpiryDate('2030-12-31T00:21:46+0300');
        $transaction->setTestMode(true);

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

    private function getTestObjectInstance(): GetPaymentToken
    {
        self::authorizeFromEnv();

        return new GetPaymentToken();
    }
}
