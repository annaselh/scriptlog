<?php
/**
 * File authorizer.php
 * checking whether session or cookies exists or not
 * 
 * @category File
 * @package  SCRIPTLOG
 * @author   Contributors
 * @license  https://opensource.org/licenses/MIT MIT License
 * 
 */
$current_date = date("Y-m-d H:i:s", time()); 

$cookie_expired_time = time() + (30 * 24 * 60 * 60); // Expired cookies for 1 month

$isUserLoggedIn = false;

if (!empty($_SESSION['user_id'])) {

    $isUserLoggedIn = true;

} elseif (!empty($_COOKIE['cookie_user_email']) && !empty($_COOKIE['random_pwd']) 
          && !empty($_COOKIE['random_selector'])) {

    $isPasswordVerified = false;
    $isSelectorVerified = false;
    $isExpiredDateVerified = false;

    // retrieve user token info
    $token_info = $authenticator -> findTokenByUserEmail($_COOKIE['cookie_user_email'], 0);

    if (password_verify($_COOKIE['random_pwd'], $token_info['pwd_hash'])) {
        $isPasswordVerified = true;
    }

    if (password_verify($_COOKIE['random_selector'], $token_info['selector_hash'])) {
        $isSelectorVerified = true;
    }

    if ($token_info['expired_date'] >= $current_date) {
        $isExpiredDateVerified = true;
    }

    if (!empty($token_info['ID']) && $isPasswordVerified && $isSelectorVerified && $isExpiredDateVerified) {

        $isUserLoggedIn = true;

    } else {

         if (!empty($token_info['ID'])) {

             $userToken -> updateTokenExpired($token_info['ID']);
             
         }

         $authenticator -> removeCookies();
         
    } 

}