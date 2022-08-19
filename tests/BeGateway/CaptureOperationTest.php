<?php

declare(strict_types=1);

namespace Tests\BeGateway;

use BeGateway\AuthorizationOperation;
use BeGateway\CaptureOperation;
use BeGateway\Response;
use BeGateway\Settings;
use ReflectionClass;
use Tests\AbstractTestCase;

class CaptureOperationTest extends AbstractTestCase
{
    public function testSetParentUid(): void
    {
        $transaction = $this->getTestObjectInstance();
        $uid = '1234567';

        $transaction->setParentUid($uid);

        $this->assertEquals($uid, $transaction->getParentUid());
    }

    public function testBuildRequestMessage(): void
    {
        $transaction = $this->getTestObject();

        $arr = [
            'request' => [
                'parent_uid' => '12345678',
                'amount' => 1256,
            ],
        ];

        $reflection = new ReflectionClass('BeGateway\CaptureOperation');
        $method = $reflection->getMethod('_buildRequestMessage');
        $method->setAccessible(true);

        $request = $method->invoke($transaction, '_buildRequestMessage');

        $this->assertEquals($arr, $request);
    }

    public function testEndpoint(): void
    {
        $auth = $this->getTestObjectInstance();

        $reflection = new ReflectionClass('BeGateway\CaptureOperation');
        $method = $reflection->getMethod('_endpoint');
        $method->setAccessible(true);
        $url = $method->invoke($auth, '_endpoint');

        $this->assertEquals($url, Settings::$gatewayBase . '/transactions/captures');
    }

    public function testSuccessCapture(): void
    {
        $amount = rand(0, 10000);

        $parent = $this->runParentTransaction($amount);

        $transaction = $this->getTestObjectInstance();

        $transaction->money->setAmount($amount);
        $transaction->setParentUid($parent->getUid());

        $t_response = $transaction->submit();

        $this->assertTrue($t_response->isValid());
        $this->assertTrue($t_response->isSuccess());
        $this->assertNotNull($t_response->getUid());
        $this->assertEquals('Successfully processed', $t_response->getMessage());
        $this->assertEquals($t_response->getResponse()->transaction->parent_uid, $parent->getUid());
    }

    public function testErrorCapture(): void
    {
        $amount = rand(0, 10000);

        $parent = $this->runParentTransaction($amount);

        $transaction = $this->getTestObjectInstance();

        $transaction->money->setAmount($amount + 1);
        $transaction->setParentUid($parent->getUid());

        $t_response = $transaction->submit();

        $this->assertTrue($t_response->isValid());
        $this->assertTrue($t_response->isError());
        $this->assertMatchesRegularExpression('/Amount can\'t be greater than/', $t_response->getMessage());
    }

    private function runParentTransaction($amount = 10.00): Response
    {
        self::authorizeFromEnv();

        $transaction = new AuthorizationOperation();

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

    private function getTestObject(): CaptureOperation
    {
        $transaction = $this->getTestObjectInstance();

        $transaction->setParentUid('12345678');

        $transaction->money->setAmount(12.56);

        return $transaction;
    }

    private function getTestObjectInstance(): CaptureOperation
    {
        self::authorizeFromEnv();

        return new CaptureOperation();
    }
}
