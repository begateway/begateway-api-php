<?php

declare(strict_types=1);

namespace Tests\BeGateway;

use BeGateway\PaymentOperation;
use BeGateway\QueryByUid;
use BeGateway\Response;
use BeGateway\Settings;
use ReflectionClass;
use Tests\AbstractTestCase;

class QueryByUidTest extends AbstractTestCase
{
    public function testSetUid(): void
    {
        $q = $this->getTestObjectInstance();

        $q->setUid('123456');

        $this->assertEquals('123456', $q->getUid());
    }

    public function testEndpoint(): void
    {
        $q = $this->getTestObjectInstance();
        $q->setUid('1234');

        $reflection = new ReflectionClass('BeGateway\QueryByUid');
        $method = $reflection->getMethod('_endpoints');
        $method->setAccessible(true);
        $url = $method->invoke($q, '_endpoints');

        $this->assertEquals($url, [
            Settings::$apiBase . '/beyag/payments/1234',
            Settings::$apiBase . '/beyag/transactions/1234',
            Settings::$gatewayBase . '/transactions/1234',
        ]);
    }

    public function testQueryRequest(): void
    {
        $amount = rand(0, 10000);

        $parent = $this->runParentTransaction((float) $amount);

        $q = $this->getTestObjectInstance();

        $q->setUid($parent->getUid());

        $response = $q->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertNotNull($response->getUid());
        $this->assertEquals($parent->getUid(), $response->getUid());
    }

    public function testQueryResponseForUnknownUid(): void
    {
        $q = $this->getTestObjectInstance();

        $q->setUid('1234567890qwerty');

        $response = $q->submit();

        $this->assertTrue($response->isValid());

        $this->assertEquals('Record not found', $response->getMessage());
    }

    private function runParentTransaction(float $amount = 10.00): Response
    {
        self::authorizeFromEnv();

        $transaction = new PaymentOperation();

        $transaction->money->setAmount($amount);
        $transaction->money->setCurrency('EUR');
        $transaction->setDescription('test');
        $transaction->setTrackingId('my_custom_variable');

        $transaction->card->setCardNumber('4200000000000000');
        $transaction->card->setCardHolder('John Doe');
        $transaction->card->setCardExpMonth('01');
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

    private function getTestObjectInstance(): QueryByUid
    {
        self::authorizeFromEnv();

        return new QueryByUid();
    }
}
