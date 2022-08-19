<?php

declare(strict_types=1);

namespace BeGateway\PaymentMethod;

abstract class Base
{
    public function getName(): string
    {
        $class_name = get_class($this);

        $name = str_replace(__NAMESPACE__ . '\\', '', $class_name);

        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
    }

    public function getParamsArray(): array
    {
        return [];
    }
}
