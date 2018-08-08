<?php
/**
 * @author Anthony Ferrara
 * @link https://blog.ircmaxell.com/2014/11/its-all-about-time.html
 * @link https://blog.ircmaxell.com/2012/12/seven-ways-to-screw-up-bcrypt.html
 * @param string $safe The internal (safe) value to be checked
 * @param string $user The user submitted (unsafe) value
 * @return boolean True if the two strings are identical.
 */
function timing_safe_equals($safe, $user)
{
    $safeLen = strlen($safe);
    $userLen = strlen($user);
    
    if ($userLen != $safeLen) {
        return false;
    }
    
    $result = 0;
    
    for ($i = 0; $i < $userLen; $i++) {
        $result |= (ord($safe[$i]) ^ ord($user[$i]));
    }
    
    // They are only identical strings if $result is exactly 0...
    return $result === 0;
    
}