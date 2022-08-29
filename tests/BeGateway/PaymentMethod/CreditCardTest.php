<?php

declare(strict_types=1);

namespace Tests\BeGateway\PaymentMethod;

use BeGateway\PaymentMethod\CreditCard;
use Tests\AbstractTestCase;

class CreditCardTest extends AbstractTestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('credit_card', $this->getTestObject()->getName());
    }

    public function testGetParamsArray(): void
    {
        $this->assertEquals([], $this->getTestObject()->getParamsArray());
    }

    public function getTestObject()
    {
        return new CreditCard;
    }
}
