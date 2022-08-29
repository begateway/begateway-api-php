<?php

declare(strict_types=1);

namespace BeGateway\PaymentMethod;

class Erip extends Base
{
    protected $_params;

    public function __construct($params)
    {
        $arDefault = [
            'order_id' => null,
            'account_number' => null,
            'service_no' => null,
            'service_info' => null,
        ];

        $this->_params = array_merge($arDefault, $params);
    }

    public function getParamsArray(): array
    {
        $arParams = [
            'order_id' => $this->_params['order_id'],
            'account_number' => $this->_params['account_number'],
            'service_no' => $this->_params['service_no'],
        ];

        $service_info = $this->_params['service_info'];

        if (gettype($service_info) == 'array' && ! empty($service_info)) {
            $arParams['service_info'] = $service_info;
        }

        return $arParams;
    }
}
