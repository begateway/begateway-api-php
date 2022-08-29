<?php

declare(strict_types=1);

namespace Tests\BeGateway;

use BeGateway\GetPaymentToken;
use BeGateway\QueryByPaymentToken;
use BeGateway\ResponseCheckout;
use BeGateway\Settings;
use ReflectionClass;
use Tests\AbstractTestCase;

class QueryByPaymentTokenTest extends AbstractTestCase
{
    public function testSetToken(): void
    {
        $q = $this->getTestObjectInstance();

        $q->setToken('123456');

        $this->assertEquals('123456', $q->getToken());
    }

    public function testEndpoint(): void
    {
        $q = $this->getTestObjectInstance();
        $q->setToken('1234');

        $reflection = new ReflectionClass('BeGateway\QueryByPaymentToken');
        $method = $reflection->getMethod('_endpoint');
        $method->setAccessible(true);
        $url = $method->invoke($q, '_endpoint');

        $this->assertEquals($url, Settings::$checkoutBase . '/ctp/api/checkouts/1234');
    }

    public function testQueryRequest(): void
    {
        $amount = rand(0, 10000);

        $parent = $this->runParentTransaction((float) $amount);

        $q = $this->getTestObjectInstance();

        $q->setToken($parent->getToken());

        $response = $q->submit();

        $this->assertTrue($response->isValid());
        $this->assertNotNull($response->getToken(), $parent->getToken());
    }

    public function testQueryResponseForUnknownUid(): void
    {
        $q = $this->getTestObjectInstance();

        $q->setToken('1234567890qwerty');

        $response = $q->submit();

        $this->assertTrue($response->isValid());

        $this->assertEquals('Record not found', $response->getMessage());
    }

    private function runParentTransaction(float $amount = 10.00): ResponseCheckout
    {
        self::authorizeFromEnv();

        $transaction = new GetPaymentToken();

        $url = 'http://www.example.com';

        $transaction->money->setAmount($amount);
        $transaction->money->setCurrency('EUR');
        $transaction->setAuthorizationTransactionType();
        $transaction->setDescription('test');
        $transaction->setTrackingId('my_custom_variable');
        $transaction->setNotificationUrl($url . '/n');
        $transaction->setCancelUrl($url . '/c');
        $transaction->setSuccessUrl($url . '/s');
        $transaction->setDeclineUrl($url . '/d');
        $transaction->setFailUrl($url . '/f');

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

    private function getTestObjectInstance(): QueryByPaymentToken
    {
        self::authorizeFromEnv();

        return new QueryByPaymentToken();
    }
}
