<?php
require_once __DIR__ . '/../lib/beGateway.php';
require_once __DIR__ . '/test_shop_data.php';

\beGateway\Logger::getInstance()->setLogLevel(\beGateway\Logger::DEBUG);
$token = $argv[1];
print("Trying to Query by Payment token " . $token . PHP_EOL);

$query = new \beGateway\QueryByToken;
$query->setToken($token);

$query_response = $query->submit();

print_r($query_response);
?>
