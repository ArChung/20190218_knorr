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

$dbConnString = 'mysql:charset=utf8;host=localhost; dbname=knorr_farm';
try {
    $dbh = new PDO($dbConnString, 'knorr', '6hbtXaYKrPmR32vU');
    $dbh->exec('set names utf8');
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
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$marriage = filter_input(INPUT_POST, 'marriage', FILTER_SANITIZE_STRING);
$hasChild = filter_input(INPUT_POST, 'hasChild', FILTER_SANITIZE_STRING);
$child = filter_input(INPUT_POST, 'child', FILTER_SANITIZE_STRING);
$remktg_consent = filter_input(INPUT_POST, 'remktg_consent', FILTER_SANITIZE_STRING);
$optin_cmpgn = filter_input(INPUT_POST, 'optin_cmpgn', FILTER_SANITIZE_STRING);
$score = filter_input(INPUT_POST, 'score', FILTER_SANITIZE_STRING);

$remktg_consent = intval($remktg_consent);
$optin_cmpgn = intval($optin_cmpgn);

$name = preg_replace('/[<>"%()&+\\/\?\n\r\t]/', '', $name);

if($email === false){
    echo '{"status": 1, "msg": "email"}';
    exit;
}

<<<<<<< HEAD
if(!preg_match('/^09[0-9]{8}$/', $phone)) {
=======
if(!preg_match('/^09/', $phone)) {
>>>>>>> 56974d0e5e3ddc805c2c93c70943c7eb894db00d
    echo '{"status": 1, "msg": "phone"}';
    exit;
}

<<<<<<< HEAD
=======
if(strlen($phone)!=10) {
    echo '{"status": 1, "msg": "phpone"}';
    exit;
}

>>>>>>> 56974d0e5e3ddc805c2c93c70943c7eb894db00d
if($optin_cmpgn!=1) {
    echo '{"status": 1, "msg": "optin_cmpgn"}';
    exit;
}

try {
    $sql = 'INSERT INTO users SET name=?, phone=?, email=?, marriage=?, hasChild=?, child=?, remktg_consent=?, optin_cmpgn=?, score=?, time_create=NOW()';
    $stmt = $dbh->prepare($sql);
<<<<<<< HEAD
    $result = $stmt->execute(array(
=======
    $stmt->execute(array(
>>>>>>> 56974d0e5e3ddc805c2c93c70943c7eb894db00d
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
<<<<<<< HEAD
    if(!$result) {
      echo '{"status": 1, "msg": "db insert error"}';
      exit;
    }
=======
>>>>>>> 56974d0e5e3ddc805c2c93c70943c7eb894db00d
    
} catch (Exception $th) {
    // throw $th;
    // print_r($th);
}


$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

$token = $easyCSRF->generate('knorr');

echo '{"status": 0, "sn": "'.($user_id*56789).'","token": "'.$token.'"}';