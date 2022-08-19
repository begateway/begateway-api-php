<?php

namespace BeGateway;

class Webhook extends Response
{
    protected $_json_source = 'php://input';
    protected $_id = null;
    protected $_key = null;

    public function __construct()
    {
        parent::__construct(file_get_contents($this->_json_source));
    }

    public function isAuthorized()
    {
        if (isset($_SERVER['HTTP_CONTENT_SIGNATURE']) && ! is_null(Settings::$shopPubKey)) {
            $signature = base64_decode($_SERVER['HTTP_CONTENT_SIGNATURE']);
            $public_key = str_replace(["\r\n", "\n"], '', Settings::$shopPubKey);
            $public_key = chunk_split($public_key, 64);
            $public_key = "-----BEGIN PUBLIC KEY-----\n" . $public_key . '-----END PUBLIC KEY-----';
            $key = openssl_pkey_get_public($public_key);
            if ($key) {
                return openssl_verify($this->getRawResponse(), $signature, $key, OPENSSL_ALGO_SHA256) == 1;
            }
        }

        $this->process_auth_data();

        return $this->_id == Settings::$shopId
               && $this->_key == Settings::$shopKey;
    }

    private function process_auth_data()
    {
        $token = null;

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $this->_id = $_SERVER['PHP_AUTH_USER'];
            $this->_key = $_SERVER['PHP_AUTH_PW'];
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION']) && ! is_null($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && ! is_null($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        if ($token != null) {
            if (strpos(strtolower($token), 'basic') === 0) {
                list($this->_id, $this->_key) = explode(':', base64_decode(substr($token, 6)));
            }
        }
    }
}
