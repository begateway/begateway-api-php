<?php
require_once __DIR__ . '/../lib/BeGateway.php';
require_once __DIR__ . '/test_shop_data.php';

\BeGateway\Logger::getInstance()->setLogLevel(\BeGateway\Logger::DEBUG);
$token = $argv[1];
print("Trying to Query by Payment token " . $token . PHP_EOL);

$query = new \BeGateway\QueryByToken;
$query->setToken($token);

$query_response = $query->submit();

print_r($query_response);
?>
