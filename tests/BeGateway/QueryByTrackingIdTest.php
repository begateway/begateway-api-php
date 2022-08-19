<?php

namespace BeGateway;

class QueryByTrackingIdTest extends TestCase
{
    public function test_trackingId()
    {
        $q = $this->getTestObjectInstance();

        $q->setTrackingId('123456');

        $this->assertEqual($q->getTrackingId(), '123456');
    }

    public function test_endpoint()
    {
        $q = $this->getTestObjectInstance();
        $q->setTrackingId('1234');

        $reflection = new \ReflectionClass('BeGateway\QueryByTrackingId');
        $method = $reflection->getMethod('_endpoint');
        $method->setAccessible(true);
        $url = $method->invoke($q, '_endpoint');

        $this->assertEqual($url, Settings::$gatewayBase . '/v2/transactions/tracking_id/1234');
    }

    public function test_queryRequest()
    {
        $amount = rand(0, 10000);
        $tracking_id = bin2hex(openssl_random_pseudo_bytes(32));

        $parent = $this->runParentTransaction($amount, $tracking_id);

        $q = $this->getTestObjectInstance();

        $q->setTrackingId($tracking_id);

        $response = $q->submit();

        $this->assertTrue($response->isValid());

        $arTrx = $response->getResponse()->transactions;

        $this->assertEqual(sizeof($arTrx), 1);
        $this->assertNotNull($arTrx[0]->uid);
        $this->assertEqual($arTrx[0]->amount, $amount * 100);
        $this->assertEqual($arTrx[0]->tracking_id, $tracking_id);
        $this->assertEqual($parent->getUid(), $arTrx[0]->uid);
    }

    public function test_queryResponseForUnknownUid()
    {
        $q = $this->getTestObjectInstance();

        $q->setTrackingId('1234567890qwerty');

        $response = $q->submit();

        $this->assertTrue($response->isValid());

        $arTrx = $response->getResponse()->transactions;
        $this->assertEqual(sizeof($arTrx), 0);
    }

    protected function runParentTransaction($amount = 10.00, $tracking_id = '12345')
    {
        self::authorizeFromEnv();

        $transaction = new PaymentOperation();

        $transaction->money->setAmount($amount);
        $transaction->money->setCurrency('EUR');
        $transaction->setDescription('test');
        $transaction->setTrackingId($tracking_id);
        $transaction->setTestMode(true);

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

    protected function getTestObjectInstance()
    {
        self::authorizeFromEnv();

        return new QueryByTrackingId();
    }
}
