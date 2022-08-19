<?php

declare(strict_types=1);

namespace Tests\BeGateway;

use BeGateway\Money;
use Tests\AbstractTestCase;

class MoneyTest extends AbstractTestCase
{
    public function testSetAmountWithDecimals(): void
    {
        $money = $this->getTestObject();

        $money->setAmount(10.57);
        $money->setCurrency('EUR');

        $this->assertEquals(1057, $money->getCents());
        $this->assertEquals(10.57, $money->getAmount());
    }

    public function testSetAmountWithoutDecimals(): void
    {
        $money = $this->getTestObject();

        $money->setAmount(2550);
        $money->setCurrency('BYR');

        $this->assertEquals(2550, $money->getCents());
        $this->assertEquals(2550, $money->getAmount());
    }

    public function testSetCentsWithDecimals(): void
    {
        $money = $this->getTestObject();

        $money->setCents(1057);
        $money->setCurrency('EUR');

        $this->assertEquals(1057, $money->getCents());
        $this->assertEquals(10.57, $money->getAmount());
    }

    public function testSetCentsWithoutDecimals(): void
    {
        $money = $this->getTestObject();

        $money->setCents(2550);
        $money->setCurrency('JPY');

        $this->assertEquals(2550, $money->getCents());
        $this->assertEquals(2550, $money->getAmount());
    }

    public function testSet99Amount(): void
    {
        $money = $this->getTestObject();

        $money->setAmount(20.99);
        $money->setCurrency('EUR');

        $this->assertEquals(2099, $money->getCents());
        $this->assertEquals(20.99, $money->getAmount());
    }

    public function testSetRoundAmount(): void
    {
        $money = $this->getTestObject();

        $money->setAmount(151.2);
        $money->setCurrency('EUR');

        $this->assertEquals(15120, $money->getCents());
        $this->assertEquals(151.20, $money->getAmount());
    }

    private function getTestObject(): Money
    {
        return new Money();
    }
}
