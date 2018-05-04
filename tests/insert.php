<?php
require(__DIR__ . '/../library/main.php');

if (isset($_GET['id']) && $_GET['id'] == 0) {

    $errors = null;
    
    $userlogin = 'hamizan';
    $email = 'hamizan@gmail.com';
    $password = 'testing4ja';
    $level = 'Administrator';
    $registered = date("Ymd");
    $activation_key = md5($email);
    $status = '1';
    $user_session = bin2hex(openssl_random_pseudo_bytes(32).microtime()*10000000);
    
    $data = [
        'user_login' => $userlogin,
        'user_email' => $email,
        'user_pass' => $password,
        'user_level' => $level,
        'user_registered' => $registered,
        'user_activation_key' => $activation_key,
        'user_status' => $status,
        'user_session' => $user_session
    ];
    
    $users = new User();
    
    if ($users -> isUserLoginExists($userlogin) === true) {
        
        $errors = "Username already used";
        
    } elseif ($users -> checkUserEmail($email) > 0) {
        
        $errors = "Email has been used, please log in with it";
        
    } elseif (email_validation($email) == 0) {
        
        $errors = "please enter your valid email address";
        
    }
    
    if (!empty($errors) === true) {
        
        echo $errors;
        
    } else {
        
        $users -> createUser($data);
        header("Location: ../tests/index.php");
        
    }
    
}
