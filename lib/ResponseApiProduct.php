<?php

namespace BeGateway;

class ResponseApiProduct extends ResponseApi
{
    public function getPayLink()
    {
        return implode(
            '/',
            [
              \BeGateway\Settings::$checkoutBase,
              'v2', 'confirm_order',
              $this->getId(),
              \BeGateway\Settings::$shopId,
            ]
        );
    }

    public function getPayUrl()
    {
        return implode(
            '/',
            [
              \BeGateway\Settings::$apiBase,
              'products',
              $this->getId(),
              'pay',
            ]
        );
    }
}
