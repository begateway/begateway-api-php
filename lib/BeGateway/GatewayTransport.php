<?php
namespace BeGateway;

class GatewayTransport {

    public static function submit($shop_id, $shop_key, $host, $t_request, $headers = array(), $curl_timeout = 30, $curl_connect_timeout = 10) {

        $process = curl_init($host);
        $json = json_encode($t_request);

        Logger::getInstance()->write("Request to $host", Logger::DEBUG, get_class() );
        Logger::getInstance()->write("with Shop Id " . Settings::$shopId . " & Shop key " . Settings::$shopKey, Logger::DEBUG, get_class() );
        if (!empty($json))
          Logger::getInstance()->write("with message " .  $json, Logger::DEBUG, get_class());

        if (!empty($t_request)) {
          curl_setopt($process, CURLOPT_HTTPHEADER,
            array_merge(
              array('Accept: application/json', 'Content-type: application/json'),
              $headers
            )
          );
          curl_setopt($process, CURLOPT_POST, 1);
          curl_setopt($process, CURLOPT_POSTFIELDS, $json);
        } else {
          curl_setopt($process, CURLOPT_HTTPHEADER,
            array_merge(
              array('Accept: application/json'),
              $headers
            )
          );
        }

        curl_setopt($process, CURLOPT_URL, $host);
        curl_setopt($process, CURLOPT_USERPWD, Settings::$shopId . ":" . Settings::$shopKey);
        curl_setopt($process, CURLOPT_CONNECTTIMEOUT, $curl_connect_timeout);
        curl_setopt($process, CURLOPT_TIMEOUT, $curl_timeout);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, true);
        $response = curl_exec($process);
        $error = curl_error($process);
        curl_close($process);

        if ($response === false) {
          throw new \Exception("cURL error " . $error);
        }

        Logger::getInstance()->write("Response $response", Logger::DEBUG, get_class() );
        return $response;
    }
}
?>
