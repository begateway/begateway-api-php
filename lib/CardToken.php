<?php

declare(strict_types=1);

namespace BeGateway;

class CardToken extends ApiAbstract
{
    public $card;

    public function __construct()
    {
        $this->card = new Card();
    }

    public function submit(): ResponseCardToken
    {
        return new ResponseCardToken($this->_remoteRequest());
    }

    protected function _endpoint(): string
    {
        return Settings::$gatewayBase . '/credit_cards';
    }

    protected function _buildRequestMessage(): array
    {
        $request = [
            'request' => [
                'holder' => $this->card->getCardHolder(),
                'number' => $this->card->getCardNumber(),
                'exp_month' => $this->card->getCardExpMonth(),
                'exp_year' => $this->card->getCardExpYear(),
                'token' => $this->card->getCardToken(),
            ],
        ];

        Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

        return $request;
    }
}
