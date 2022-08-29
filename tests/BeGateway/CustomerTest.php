<?php

declare(strict_types=1);

namespace Tests\BeGateway;

use BeGateway\Customer;
use Tests\AbstractTestCase;

class CustomerTest extends AbstractTestCase
{
    public function testSetGetFirstName(): void
    {
        $customer = $this->getTestObject();

        $customer->setFirstName('John');
        $this->assertEquals('John', $customer->getFirstName());

        $customer->setFirstName('');
        $this->assertEquals(null, $customer->getFirstName());
    }

    public function testSetGetLastName(): void
    {
        $customer = $this->getTestObject();

        $customer->setLastName('Doe');
        $this->assertEquals('Doe', $customer->getLastName());

        $customer->setLastName('');
        $this->assertEquals(null, $customer->getLastName());
    }

    public function testSetGetAddress(): void
    {
        $customer = $this->getTestObject();

        $customer->setAddress('po box 123');
        $this->assertEquals('po box 123', $customer->getAddress());

        $customer->setAddress('');
        $this->assertEquals(null, $customer->getAddress());
    }

    public function testSetGetCountry(): void
    {
        $customer = $this->getTestObject();

        $customer->setCountry('LV');
        $this->assertEquals('LV', $customer->getCountry());

        $customer->setCountry('');
        $this->assertEquals(null, $customer->getCountry());
    }

    public function testSetGetZip(): void
    {
        $customer = $this->getTestObject();

        $customer->setZip('LV1024');
        $this->assertEquals('LV1024', $customer->getZip());

        $customer->setZip('');
        $this->assertEquals(null, $customer->getZip());
    }

    public function testSetGetEmail(): void
    {
        $customer = $this->getTestObject();

        $customer->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $customer->getEmail());

        $customer->setEmail('');
        $this->assertEquals(null, $customer->getEmail());
    }

    private function getTestObject(): Customer
    {
        return new Customer();
    }
}
