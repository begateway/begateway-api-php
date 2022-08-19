<?php

declare(strict_types=1);

namespace Tests\BeGateway\PaymentMethod;

use BeGateway\PaymentMethod\Erip;
use Tests\BaseTestCase;

class EripTest extends BaseTestCase
{
    public function testGetName(): void
    {
        $this->assertEquals('erip', $this->getTestObject()->getName());
    }

    public function testGetParamsArray(): void
    {
        $this->assertEquals([
            'account_number' => '1234',
            'service_no' => '99999999',
            'order_id' => 100001,
            'service_info' => ['Test payment'],
        ], $this->getTestObject()->getParamsArray());
    }

    public function getTestObject(): Erip
    {
        return new Erip([
            'account_number' => '1234',
            'service_no' => '99999999',
            'order_id' => 100001,
            'service_info' => ['Test payment'],
        ]);
    }
}
