<?php

declare(strict_types=1);

namespace Tests\BeGateway;

use BeGateway\AuthorizationOperation;
use BeGateway\CardToken;
use BeGateway\Settings;
use ReflectionClass;
use Tests\AbstractTestCase;

class CreditCardTokenizationTest extends AbstractTestCase
{
    public function testBuildRequestMessage(): void
    {
        $token = $this->getTestObject();
        $arr = [
            'request' => [
                'number' => '4200000000000000',
                'holder' => 'John Smith',
                'exp_month' => '02',
                'exp_year' => '2030',
                'token' => '',
            ],
        ];

        $reflection = new ReflectionClass('BeGateway\CardToken');
        $method = $reflection->getMethod('_buildRequestMessage');
        $method->setAccessible(true);

        $request = $method->invoke($token, '_buildRequestMessage');

        $this->assertEquals($arr, $request);
    }

    public function testEndpoint(): void
    {
        $token = $this->getTestObjectInstance();

        $reflection = new ReflectionClass('BeGateway\CardToken');
        $method = $reflection->getMethod('_endpoint');
        $method->setAccessible(true);
        $url = $method->invoke($token, '_endpoint');

        $this->assertEquals($url, Settings::$gatewayBase . '/credit_cards');
    }

    public function testSuccessTokenCreationUpdateAndAuthorization(): void
    {
        $token = $this->getTestObject();

        // create token
        $response = $token->submit();

        $this->assertTrue($response->isValid());
        $this->assertTrue($response->isSuccess());
        $this->assertEquals('John Smith', $response->card->getCardHolder());
        $this->assertEquals('visa', $response->card->getBrand());
        $this->assertEquals('4', $response->card->getFirst_1());
        $this->assertEquals('0000', $response->card->getLast_4());
        $this->assertEquals('02', $response->card->getCardExpMonth());
        $this->assertEquals('2030', $response->card->getCardExpYear());
        $this->assertNotNull($response->card->getCardToken());

        // update token
        $token->card->setCardExpMonth('01');
        $token->card->setCardHolder('John Doe');
        $old_token = $response->card->getCardToken();
        $token->card->setCardToken($old_token);
        $token->card->setCardNumber(null);

        $response2 = $token->submit();
        $this->assertEquals('John Doe', $response2->card->getCardHolder());
        $this->assertEquals('visa', $response2->card->getBrand());
        $this->assertEquals('4', $response2->card->getFirst_1());
        $this->assertEquals('0000', $response2->card->getLast_4());
        $this->assertEquals('01', $response2->card->getCardExpMonth());
        $this->assertEquals('2030', $response2->card->getCardExpYear());
        $this->assertNotNull($response2->card->getCardToken());
        $this->assertEquals($response2->card->getCardToken(), $old_token);

        // make authorization with token
        $amount = rand(0, 10000) / 100;

        $auth = $this->getAuthorizationTestObject();

        $auth->money->setAmount($amount);
        $cents = $auth->money->getCents();

        $auth->card->setCardToken($response2->card->getCardToken());
        $auth->card->setCardCvc('123');

        $response3 = $auth->submit();
        $this->assertTrue($response3->isValid());
        $this->assertTrue($response3->isSuccess());
        $this->assertEquals('Successfully processed', $response3->getMessage());
        $this->assertNotNull($response3->getUid());
        $this->assertEquals('successful', $response3->getStatus());
        $this->assertEquals($cents, $response3->getResponse()->transaction->amount);
    }

    private function getTestObject(bool $threed = false): CardToken
    {
        $transaction = $this->getTestObjectInstance($threed);

        $transaction->card->setCardNumber('4200000000000000');
        $transaction->card->setCardHolder('John Smith');
        $transaction->card->setCardExpMonth('02');
        $transaction->card->setCardExpYear(2030);

        return $transaction;
    }

    private function getAuthorizationTestObject(bool $threed = false): AuthorizationOperation
    {
        $transaction = $this->getAuthorizationTestObjectInstance($threed);

        $transaction->money->setCurrency('EUR');
        $transaction->setDescription('test');
        $transaction->setTrackingId('my_custom_variable');

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

    private function getTestObjectInstance($threed = false): CardToken
    {
        self::authorizeFromEnv($threed);

        return new CardToken();
    }

    private function getAuthorizationTestObjectInstance(bool $threed = false): AuthorizationOperation
    {
        self::authorizeFromEnv($threed);

        return new AuthorizationOperation();
    }
}
