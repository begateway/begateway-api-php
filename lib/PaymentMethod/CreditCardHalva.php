<?php

declare(strict_types=1);

namespace BeGateway\PaymentMethod;

class CreditCardHalva extends Base
{
    public function getName(): string
    {
        return 'halva';
    }
}
