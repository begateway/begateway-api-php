<?php

namespace BeGateway;

class MoneyTest extends TestCase
{
    public function test_setAmount_with_decimals()
    {
        $money = $this->getTestObject();

        $money->setAmount(10.57);
        $money->setCurrency('EUR');

        $this->assertEqual($money->getCents(), 1057);
        $this->assertEqual($money->getAmount(), 10.57);
    }

    public function test_setAmount_without_decimals()
    {
        $money = $this->getTestObject();

        $money->setAmount(2550);
        $money->setCurrency('BYR');

        $this->assertEqual($money->getCents(), 2550);
        $this->assertEqual($money->getAmount(), 2550);
    }

    public function test_setCents_with_decimals()
    {
        $money = $this->getTestObject();

        $money->setCents(1057);
        $money->setCurrency('EUR');

        $this->assertEqual($money->getCents(), 1057);
        $this->assertEqual($money->getAmount(), 10.57);
    }

    public function test_setCents_without_decimals()
    {
        $money = $this->getTestObject();

        $money->setCents(2550);
        $money->setCurrency('JPY');

        $this->assertEqual($money->getCents(), 2550);
        $this->assertEqual($money->getAmount(), 2550);
    }

    public function test_set99Amount()
    {
        $money = $this->getTestObject();

        $money->setAmount(20.99);
        $money->setCurrency('EUR');

        $this->assertEqual($money->getCents(), 2099);
        $this->assertEqual($money->getAmount(), 20.99);
    }

    public function test_setRoundAmount()
    {
        $money = $this->getTestObject();

        $money->setAmount(151.2);
        $money->setCurrency('EUR');

        $this->assertEqual($money->getCents(), 15120);
        $this->assertEqual($money->getAmount(), 151.20);
    }

    protected function getTestObject()
    {
        return new Money();
    }
}
