<?php

declare(strict_types=1);

namespace Tests\BeGateway\PaymentMethod;

use BeGateway\PaymentMethod\CreditCardHalva;
use Tests\AbstractTestCase;

class CreditCardHalvaTest extends AbstractTestCase
{
    public function testGetName(): void
    {
        $cc = $this->getTestObject();

        $this->assertEquals('halva', $cc->getName());
    }

    public function testGetParamsArray(): void
    {
        $cc = $this->getTestObject();

        $this->assertEquals([], $cc->getParamsArray());
    }

    public function getTestObject(): CreditCardHalva
    {
        return new CreditCardHalva;
    }
}
