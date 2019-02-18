<?php

require_once 'vendor/autoload.php';

session_start();

$sessionProvider = new EasyCSRF\NativeSessionProvider();
$easyCSRF = new EasyCSRF\EasyCSRF($sessionProvider);

$token = $easyCSRF->generate('knorr');
// print_r($_SESSION);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<style>
.form-control {
    width: 100%
}
</style>
<body>
    <form action="store.php" method="POST">
        
        <input type="text" name="token" id="" class="form-control" value="<?php echo $token;?>">
        <input type="text" name="name" id="" class="form-control" value="LongChang" >
        <input type="text" name="phone" id="" class="form-control" value="0988071132" >
        <input type="text" name="email" id="" class="form-control" value="cklong2k@gmail.com" >
        <input type="text" name="marriage" id="" class="form-control" value="0" >
        <input type="text" name="hasChild" id="" class="form-control" value="0" >
        <input type="text" name="child" id="" class="form-control" value="[]" >
        <input type="text" name="agreeToSendMeInfo" id="" class="form-control" value="1" >
        <input type="text" name="score" id="" class="form-control" value="5" >
        <div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</body>
</html>