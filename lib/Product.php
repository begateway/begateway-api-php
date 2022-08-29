<?php

declare(strict_types=1);

namespace BeGateway;

class Product extends ApiAbstract
{
    public $money;
    public $additional_data;
    protected $_name = null;
    protected $_description = null;
    protected $_quantity = null;
    protected $_infinite = true;
    protected $_language;
    protected $_success_url = null;
    protected $_fail_url = null;
    protected $_return_url = null;
    protected $_notification_url = null;
    protected $_immortal = true;
    protected $_transaction_type = 'payment';
    protected $_visible = [];
    protected $_expired_at = null;
    protected $_test_mode = false;

    public function __construct()
    {
        $this->customer = new Customer();
        $this->money = new Money();
        $this->additional_data = new AdditionalData();
        $this->_language = Language::getDefaultLanguage();
    }

    protected function _endpoint()
    {
        return Settings::$apiBase . '/products';
    }

    protected function _buildRequestMessage()
    {
        $request = [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'amount' => $this->money->getCents(),
            'currency' => $this->money->getCurrency(),
            'infinite' => $this->getInfiniteState(),
            'language' => $this->getLanguage(),
            'notification_url' => $this->getNotificationUrl(),
            'success_url' => $this->getSuccessUrl(),
            'fail_url' => $this->getFailUrl(),
            'return_url' => $this->getReturnUrl(),
            'immortal' => $this->getImmortalState(),
            'visible' => $this->getVisibleFields(),
            'test' => $this->getTestMode(),
            'transaction_type' => $this->getTransactionType(),
            'additional_data' => [
                'receipt_text' => $this->additional_data->getReceipt(),
                'contract' => $this->additional_data->getContract(),
                'meta' => $this->additional_data->getMeta(),
                'fiscalization' => $this->additional_data->getFiscalization(),
                'platform_data' => $this->additional_data->getPlatformData(),
                'integration_data' => $this->additional_data->getIntegrationData(),
            ],
        ];

        if ($this->_quantity > 0) {
            $request['quantity'] = $this->getQuantity();
            $request['infinite'] = false;
        }

        if (isset($this->_expired_at)) {
            $request['expired_at'] = $this->getExpiryDate();
            $request['immortal'] = false;
        }

        Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

        return $request;
    }

    public function submit()
    {
        return new ResponseApiProduct($this->_remoteRequest());
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;
    }

    public function getQuantity()
    {
        return $this->_quantity;
    }

    public function setInfiniteState($state = true)
    {
        $this->_infinite = $state;
    }

    public function getInfiniteState()
    {
        return $this->_infinite;
    }

    public function setImmortalState($state = true)
    {
        $this->_immortal = $state;
    }

    public function getImmortalState()
    {
        return $this->_immortal;
    }

    public function setNotificationUrl($notification_url)
    {
        $this->_notification_url = $notification_url;
    }

    public function getNotificationUrl()
    {
        return $this->_notification_url;
    }

    public function setSuccessUrl($success_url)
    {
        $this->_success_url = $success_url;
    }

    public function getSuccessUrl()
    {
        return $this->_success_url;
    }

    public function setFailUrl($fail_url)
    {
        $this->_fail_url = $fail_url;
    }

    public function getFailUrl()
    {
        return $this->_fail_url;
    }

    public function setReturnUrl($return_url)
    {
        $this->_return_url = $return_url;
    }

    public function getReturnUrl()
    {
        return $this->_return_url;
    }

    public function setAuthorizationTransactionType()
    {
        $this->_transaction_type = 'authorization';
    }

    public function setPaymentTransactionType()
    {
        $this->_transaction_type = 'payment';
    }

    public function getTransactionType()
    {
        return $this->_transaction_type;
    }

    public function setLanguage($language_code)
    {
        if (in_array($language_code, Language::getSupportedLanguages())) {
            $this->_language = $language_code;
        } else {
            $this->_language = Language::getDefaultLanguage();
        }
    }

    public function getLanguage()
    {
        return $this->_language;
    }

    // date when payment expires for payment
    // date is in ISO8601 format
    public function setExpiryDate($date)
    {
        $iso8601 = null;

        if ($date != null) {
            $iso8601 = date('c', strtotime($date));
        }

        $this->_expired_at = $iso8601;
    }

    public function setVisible(array $visible)
    {
        $this->_visible = $visible;
    }

    public function getExpiryDate()
    {
        return $this->_expired_at;
    }

    public function getVisibleFields()
    {
        return $this->_visible;
    }

    public function setPhoneVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'phone');
    }

    public function unsetPhoneVisible()
    {
        $this->_visible = array_diff($this->_visible, ['phone']);
    }

    public function setAddressVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'address');
    }

    public function unsetAddressVisible()
    {
        $this->_visible = array_diff($this->_visible, ['address']);
    }

    public function setFirstNameVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'first_name');
    }

    public function unsetFirstNameVisible()
    {
        $this->_visible = array_diff($this->_visible, ['first_name']);
    }

    public function setLastNameVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'last_name');
    }

    public function unsetLastNameVisible()
    {
        $this->_visible = array_diff($this->_visible, ['last_name']);
    }

    public function setCityVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'city');
    }

    public function unsetCityVisible()
    {
        $this->_visible = array_diff($this->_visible, ['city']);
    }

    public function setStateVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'state');
    }

    public function unsetStateVisible()
    {
        $this->_visible = array_diff($this->_visible, ['state']);
    }

    public function setZipVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'zip');
    }

    public function unsetZipVisible()
    {
        $this->_visible = array_diff($this->_visible, ['zip']);
    }

    public function setCountryVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'country');
    }

    public function unsetCountryVisible()
    {
        $this->_visible = array_diff($this->_visible, ['country']);
    }

    public function setEmailVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'email');
    }

    public function unsetEmailVisible()
    {
        $this->_visible = array_diff($this->_visible, ['email']);
    }

    public function setBirthDateVisible()
    {
        $this->_visible = self::_searchAndAdd($this->_visible, 'birth_date');
    }

    public function unsetBirthDateVisible()
    {
        $this->_visible = array_diff($this->_visible, ['birth_date']);
    }

    public function setTestMode(bool $mode = true): void
    {
        $this->_test_mode = $mode;
    }

    public function getTestMode()
    {
        return $this->_test_mode;
    }

    private function _searchAndAdd($array, $value)
    {
        // search for $value in $array
        // if not found, adds $value to $array and returns $array
        // otherwise returns not altered $array
        $arr = $array;
        if (! in_array($value, $arr)) {
            array_push($arr, $value);
        }

        return $arr;
    }
}
