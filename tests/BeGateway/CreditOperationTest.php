<?php

declare(strict_types=1);

namespace Tests\BeGateway;

use BeGateway\CreditOperation;
use BeGateway\PaymentOperation;
use BeGateway\Response;
use BeGateway\Settings;
use ReflectionClass;
use Tests\AbstractTestCase;

class CreditOperationTest extends AbstractTestCase
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

    public function testBuildRequestMessage(): void
    {
        $transaction = $this->getTestObject();
        $arr = [
            'request' => [
                'amount' => 1256,
                'currency' => 'RUB',
                'description' => 'description',
                'tracking_id' => 'tracking',
                'credit_card' => [
                    'token' => '12345',
                ],
            ],
        ];

        $reflection = new ReflectionClass('BeGateway\CreditOperation');
        $method = $reflection->getMethod('_buildRequestMessage');
        $method->setAccessible(true);

        $request = $method->invoke($transaction, '_buildRequestMessage');

        $this->assertEquals($arr, $request);
    }

    public function testEndpoint(): void
    {
        $auth = $this->getTestObjectInstance();

        $reflection = new ReflectionClass('BeGateway\CreditOperation');
        $method = $reflection->getMethod('_endpoint');
        $method->setAccessible(true);
        $url = $method->invoke($auth, '_endpoint');

        $this->assertEquals($url, Settings::$gatewayBase . '/transactions/credits');
    }

    public function testSuccessCreditRequest(): void
    {
        $amount = rand(0, 10000);

        $parent = $this->runParentTransaction((float) $amount);

        $transaction = $this->getTestObjectInstance();

        $transaction->money->setAmount($amount * 2);
        $transaction->money->setCurrency('EUR');
        $transaction->setDescription('test description');
        $transaction->setTrackingId('tracking_id');
        $transaction->card->setCardToken($parent->getResponse()->transaction->credit_card->token);

        $t_response = $transaction->submit();

        $this->assertTrue($t_response->isValid());
        $this->assertTrue($t_response->isSuccess());
        $this->assertNotNull($t_response->getUid());
        $this->assertEquals('Successfully processed', $t_response->getMessage());
    }

    public function testErrorCreditRequest(): void
    {
        $amount = rand(0, 10000);

        $transaction = $this->getTestObjectInstance();

        $transaction->money->setAmount($amount * 2);
        $transaction->money->setCurrency('EUR');
        $transaction->setDescription('test description');
        $transaction->setTrackingId('tracking_id');
        $transaction->card->setCardToken('12345');

        $t_response = $transaction->submit();

        $this->assertTrue($t_response->isValid());
        $this->assertTrue($t_response->isError());
        $this->assertMatchesRegularExpression('|Token does not exist.|', $t_response->getMessage());
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

    private function getTestObject(): CreditOperation
    {
        $transaction = $this->getTestObjectInstance();

        $transaction->money->setAmount(12.56);
        $transaction->money->setCurrency('RUB');
        $transaction->card->setCardToken('12345');
        $transaction->setDescription('description');
        $transaction->setTrackingId('tracking');

        return $transaction;
    }

    private function getTestObjectInstance(): CreditOperation
    {
        self::authorizeFromEnv();

        return new CreditOperation();
    }
}
