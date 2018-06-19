<?php
/**
 * Autolink function 
 * 
 * @link http://www.couchcode.com/php/auto-link-function/
 * @param string $text
 * @return mixed
 */
function autolink($text) {
    $pattern = '/(((http[s]?:\/\/(.+(:.+)?@)?)|(www\.))[a-z0-9](([-a-z0-9]+\.)*\.[a-z]{2,})?\/?[a-z0-9.,_\/~#&=:;%+!?-]+)/is';
    $text = preg_replace($pattern, ' <a href="$1">$1</a>', $text);
    // fix URLs without protocols
    $text = preg_replace('/href="www/', 'href="http://www', $text);
    return $text;
}