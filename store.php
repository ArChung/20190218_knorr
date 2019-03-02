<?php

// echo '<pre>';print_r($_SERVER);echo '</pre>';die;

require_once 'vendor/autoload.php';

header('Content-Type: application/json');

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    echo '{"status": 1}';
    exit;
}

// print_r($_POST);
session_start();

$dbConnString = 'mysql:charset=utf8mb4;host=localhost; dbname=knorr_farm';
try {
    $dbh = new PDO($dbConnString, 'knorr', '6hbtXaYKrPmR32vU');
} catch (Exception $Exception) {
    die('db error');
}

$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

if(!empty($_POST['token'])) {
    try {
        $easyCSRF->check('knorr', $_POST['token']);
    }
    catch(Exception $e) {
        echo '{"status": 1}';
        exit;
    }
}else{
    echo '{"status": 1}';
    exit;
}

// print_r($_POST);

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING|FILTER_VALIDATE_EMAIL);
$marriage = filter_input(INPUT_POST, 'marriage', FILTER_SANITIZE_STRING);
$hasChild = filter_input(INPUT_POST, 'hasChild', FILTER_SANITIZE_STRING);
$child = filter_input(INPUT_POST, 'child', FILTER_SANITIZE_STRING);
$remktg_consent = filter_input(INPUT_POST, 'remktg_consent', FILTER_SANITIZE_STRING);
$optin_cmpgn = filter_input(INPUT_POST, 'optin_cmpgn', FILTER_SANITIZE_STRING);
$score = filter_input(INPUT_POST, 'score', FILTER_SANITIZE_STRING);

$remktg_consent = intval($remktg_consent);
$optin_cmpgn = intval($optin_cmpgn);

$name = preg_replace('/[<>"%()&+\\/\?\n\r\t]/', '', $name);

if(!preg_match('/^09/', $phone)) {
    echo '{"status": 1}';
    exit;
}

if($optin_cmpgn!=1) {
    echo '{"status": 1}';
    exit;
}

try {
    $sql = 'INSERT INTO users SET name=?, phone=?, email=?, marriage=?, hasChild=?, child=?, remktg_consent=?, optin_cmpgn=?, score=?, time_create=NOW()';
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(
        $name,
        $phone,
        $email,
        $marriage,
        $hasChild,
        $child,
        $remktg_consent,
        $optin_cmpgn,
        $score
    ));
    $user_id = $dbh->lastInsertId();
    
} catch (Exception $th) {
    // throw $th;
    // print_r($th);
}


$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

$token = $easyCSRF->generate('knorr');

echo '{"status": 0, "sn": "'.($user_id*56789).'","token": "'.$token.'"}';