<?php

require_once 'vendor/autoload.php';

header('Content-Type: application/json');

$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

$token = $easyCSRF->generate('knorr');

echo json_encode([
    'status' =>  0,
    'token' => $token
]);