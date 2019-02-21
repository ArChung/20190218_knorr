<?php

require_once 'vendor/autoload.php';

header('Content-Type: application/json');

$db = \ParagonIE\EasyDB\Factory::create(
    'mysql:host=localhost;dbname=knorr',
    'root',
    'longlong'
);

// print_r($_POST);
session_start();

$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

if(!empty($_POST['token'])) {
    try {
        $easyCSRF->check('knorr', $_POST['token']);
    }
    catch(Exception $e) {
        echo json_encode([
            'status' =>  1,
            'msg' => $e->getMessage()
        ]);
        exit;
    }
}else{
    echo json_encode([
            'status' =>  1,
            'msg' => ''
        ]);
    exit;
}

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING|FILTER_SANITIZE_EMAIL|FILTER_VALIDATE_EMAIL);
$marriage = filter_input(INPUT_POST, 'marriage', FILTER_SANITIZE_STRING|FILTER_VALIDATE_BOOLEAN);
$hasChild = filter_input(INPUT_POST, 'hasChild', FILTER_SANITIZE_STRING|FILTER_VALIDATE_BOOLEAN);
$child = filter_input(INPUT_POST, 'child', FILTER_SANITIZE_STRING);
$agreeToSendMeInfo = filter_input(INPUT_POST, 'agreeToSendMeInfo', FILTER_SANITIZE_STRING|FILTER_VALIDATE_BOOLEAN);
$score = filter_input(INPUT_POST, 'score', FILTER_SANITIZE_STRING);

try {
    $result = $db->insert('users', [
        'name' => $name,
        'phone' => $phone,
        'email' => $email,
        'marriage' => $marriage,
        'hasChild' => $hasChild,
        'child' => $child,
        'agreeToSendMeInfo' => $agreeToSendMeInfo,
        'score' => $score,
    ]);
} catch (\Throwable $th) {
    throw $th;
}
// print_r($result);

echo json_encode([
    'status' =>  0,
    'sn' => md5($result)
]);