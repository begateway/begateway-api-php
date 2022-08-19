<?php

namespace BeGateway\PaymentMethod;

class CreditCardHalvaTest extends \BeGateway\TestCase
{
    public function test_getName()
    {
        $cc = $this->getTestObject();

        $this->assertEqual($cc->getName(), 'halva');
    }

    public function test_getParamsArray()
    {
        $cc = $this->getTestObject();

        $this->assertEqual($cc->getParamsArray(), []);
    }

    public function getTestObject()
    {
        return new CreditCardHalva;
    }
}
