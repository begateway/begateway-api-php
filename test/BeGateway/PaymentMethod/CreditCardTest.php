<?php

namespace BeGateway\PaymentMethod;

class CreditCardTest extends \BeGateway\TestCase
{
    public function test_getName()
    {
        $cc = $this->getTestObject();

        $this->assertEqual($cc->getName(), 'credit_card');
    }

    public function test_getParamsArray()
    {
        $cc = $this->getTestObject();

        $this->assertEqual($cc->getParamsArray(), []);
    }

    public function getTestObject()
    {
        return new CreditCard;
    }
}
