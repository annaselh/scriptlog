<?php
require(__DIR__ . '/../library/main.php');

if (isset($_GET['id'])) {
    
    $id = (int)$_GET['id'];
    $user_fullname = 'Hamizan Noermoehammad';
    $email = 'hamizan@abc.com';
    $password = 'iknowwhatyoudidlastsummer';
    $level = 'Author';
    
    //var_dump($id);
    
    $data = [
       
        'user_email' => $email,
        'user_pass' => $password,
        'user_level' => $level,
        'user_fullname' => $user_fullname,
        'user_url' => 'http://',
        'user_status' => '1'
        
    ];
    
    $users = new User();
    
    if ($users -> checkUserEmail($email) > 0) {
        
        $errors = "Email has been used, please log in with it";
        
    } elseif (email_validation($email) == 0) {
        
        $errors = "please enter your valid email address";
        
    }
    
    if (!empty($errors) === true) {
        
        echo $errors;
        
    } else {
        
        $sanitize = new Sanitize();
        
        $users -> updateUser("Administrator", $sanitize, $data, $id);
        
        header("Location: ../tests/index.php");
        
    }
    
    
}