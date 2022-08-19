<?php

declare(strict_types=1);

namespace Tests\BeGateway;

use BeGateway\AuthorizationOperation;
use BeGateway\Settings;
use Tests\AbstractTestCase;

class GatewayTransportExceptionTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        $this->_apiBase = Settings::$gatewayBase;

        Settings::$gatewayBase = 'https://thedomaindoesntexist.begatewaynotexist.com';
    }

    protected function tearDown(): void
    {
        Settings::$gatewayBase = $this->_apiBase;
    }

    public function testNetworkIssuesHandledCorrectly()
    {
        $auth = $this->getTestObject();

        $amount = rand(0, 10000) / 100;

        $auth->money->setAmount($amount);

        $response = $auth->submit();

        $this->assertTrue($response->isError());
        $this->assertMatchesRegularExpression('|thedomaindoesntexist.begatewaynotexist.com|', $response->getMessage());
    }

    private function getTestObject(bool $threed = false): AuthorizationOperation
    {
        $transaction = $this->getTestObjectInstance($threed);

        $transaction->money->setAmount(12.33);
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

        return $transaction;
    }

    private function getTestObjectInstance(bool $threed = false): AuthorizationOperation
    {
        self::authorizeFromEnv($threed);

        return new AuthorizationOperation();
    }
}
