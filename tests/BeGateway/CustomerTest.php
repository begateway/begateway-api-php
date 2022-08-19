<?php

namespace BeGateway;

class CustomerTest extends TestCase
{
    public function test_set_get_FirstName()
    {
        $customer = $this->getTestObject();

        $customer->setFirstName('John');
        $this->assertEqual($customer->getFirstName(), 'John');

        $customer->setFirstName('');
        $this->assertEqual($customer->getFirstName(), null);
    }

    public function test_set_get_LastName()
    {
        $customer = $this->getTestObject();

        $customer->setLastName('Doe');
        $this->assertEqual($customer->getLastName(), 'Doe');

        $customer->setLastName('');
        $this->assertEqual($customer->getLastName(), null);
    }

    public function test_set_get_Address()
    {
        $customer = $this->getTestObject();

        $customer->setAddress('po box 123');
        $this->assertEqual($customer->getAddress(), 'po box 123');

        $customer->setAddress('');
        $this->assertEqual($customer->getAddress(), null);
    }

    public function test_set_get_Country()
    {
        $customer = $this->getTestObject();

        $customer->setCountry('LV');
        $this->assertEqual($customer->getCountry(), 'LV');

        $customer->setCountry('');
        $this->assertEqual($customer->getCountry(), null);
    }

    public function test_set_get_Zip()
    {
        $customer = $this->getTestObject();

        $customer->setZip('LV1024');
        $this->assertEqual($customer->getZip(), 'LV1024');

        $customer->setZip('');
        $this->assertEqual($customer->getZip(), null);
    }

    public function test_set_get_Email()
    {
        $customer = $this->getTestObject();

        $customer->setEmail('test@example.com');
        $this->assertEqual($customer->getEmail(), 'test@example.com');

        $customer->setEmail('');
        $this->assertEqual($customer->getEmail(), null);
    }

    protected function getTestObject()
    {
        return new Customer();
    }
}
