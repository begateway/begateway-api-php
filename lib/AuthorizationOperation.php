<?php

declare(strict_types=1);

namespace BeGateway;

class AuthorizationOperation extends ApiAbstract
{
    public $customer;
    public $card;
    public $money;
    public $additional_data;
    protected $_description;
    protected $_tracking_id;
    protected $_notification_url;
    protected $_return_url;
    protected $_test_mode;
    protected $_duplicate_check;

    public function __construct()
    {
        $this->customer = new Customer();
        $this->money = new Money();
        $this->card = new Card();
        $this->additional_data = new AdditionalData();
        $this->_language = Language::getDefaultLanguage();
        $this->_test_mode = false;
        $this->_duplicate_check = true;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setTrackingId($tracking_id)
    {
        $this->_tracking_id = $tracking_id;
    }

    public function getTrackingId()
    {
        return $this->_tracking_id;
    }

    public function setNotificationUrl($notification_url)
    {
        $this->_notification_url = $notification_url;
    }

    public function getNotificationUrl()
    {
        return $this->_notification_url;
    }

    public function setReturnUrl($return_url)
    {
        $this->_return_url = $return_url;
    }

    public function getReturnUrl()
    {
        return $this->_return_url;
    }

    public function setTestMode($mode = true)
    {
        $this->_test_mode = $mode;
    }

    public function getTestMode(): bool
    {
        return $this->_test_mode;
    }

    public function setDuplicateCheck($duplicate_check = true)
    {
        $this->_duplicate_check = $duplicate_check;
    }

    public function getDuplicateCheck()
    {
        return $this->_duplicate_check;
    }

    protected function _buildCard()
    {
        $encrypted_card = [];

        $card = [
          'number' => $this->card->getCardNumber(),
          'verification_value' => $this->card->getCardCvc(),
          'holder' => $this->card->getCardHolder(),
          'exp_month' => $this->card->getCardExpMonth(),
          'exp_year' => $this->card->getCardExpYear(),
          'token' => $this->card->getCardToken(),
          'skip_three_d_secure_verification' => $this->card->getSkip3D(),
        ];

        $card = array_filter($card);

        foreach ($card as $k => $v) {
            if (strpos((string) $v, '$begatewaycse') !== false) {
                $encrypted_card[$k] = $v;

                unset($card[$k]);
            }
        }

        $response = [];

        if (count($card) > 0) {
            $response['credit_card'] = $card;
        }

        if (count($encrypted_card) > 0) {
            $response['encrypted_credit_card'] = $encrypted_card;
        }

        return $response;
    }

    protected function _buildRequestMessage()
    {
        $request = [
          'request' => [
            'amount' => $this->money->getCents(),
            'currency' => $this->money->getCurrency(),
            'description' => $this->getDescription(),
            'tracking_id' => $this->getTrackingId(),
            'notification_url' => $this->getNotificationUrl(),
            'return_url' => $this->getReturnUrl(),
            'language' => $this->getLanguage(),
            'test' => $this->getTestMode(),
            'duplicate_check' => $this->getDuplicateCheck(),
            'customer' => [
              'ip' => $this->customer->getIP(),
              'email' => $this->customer->getEmail(),
              'birth_date' => $this->customer->getBirthDate(),
            ],
            'billing_address' => [
              'first_name' => $this->customer->getFirstName(),
              'last_name' => $this->customer->getLastName(),
              'country' => $this->customer->getCountry(),
              'city' => $this->customer->getCity(),
              'state' => $this->customer->getState(),
              'zip' => $this->customer->getZip(),
              'address' => $this->customer->getAddress(),
              'phone' => $this->customer->getPhone(),
            ],
            'additional_data' => [
              'receipt_text' => $this->additional_data->getReceipt(),
              'contract' => $this->additional_data->getContract(),
              'meta' => $this->additional_data->getMeta(),
              'fiscalization' => $this->additional_data->getFiscalization(),
              'platform_data' => $this->additional_data->getPlatformData(),
              'integration_data' => $this->additional_data->getIntegrationData(),
            ],
          ],
        ];

        $request['request'] = array_merge($request['request'], $this->_buildCard());

        Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

        return $request;
    }
}
