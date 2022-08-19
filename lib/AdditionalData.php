<?php

declare(strict_types=1);

namespace BeGateway;

class AdditionalData
{
    protected $_receipt_text = [];
    protected $_contract = [];
    protected $_meta = [];
    protected $_fiscalization = [];
    protected $_platform_data = null;
    protected $_integration_data = null;

    public function setReceipt($receipt): void
    {
        $this->_receipt_text = $receipt;
    }

    public function getReceipt()
    {
        return $this->_receipt_text;
    }

    public function setContract($contract): void
    {
        $this->_contract = $contract;
    }

    public function getContract()
    {
        return $this->_contract;
    }

    public function setMeta($meta): void
    {
        $this->_meta = $meta;
    }

    public function getMeta()
    {
        return $this->_meta;
    }

    public function setFiscalization($fiscalization): void
    {
        $this->_fiscalization = $fiscalization;
    }

    public function getFiscalization()
    {
        return $this->_fiscalization;
    }

    public function setPlatformData($platform): void
    {
        $this->_platform_data = strval($platform);
    }

    public function getPlatformData()
    {
        return $this->_platform_data;
    }

    public function setIntegrationData($module): void
    {
        $this->_integration_data = strval($module);
    }

    public function getIntegrationData()
    {
        return $this->_integration_data;
    }
}
