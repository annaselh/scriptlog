<?php

// sanitize header email by Kevin Waterson
function sanitize_email($string)
{
    return preg_replace('((?:\n|\r|\t|%0A|%0D|%08|%09)+)i', '', $string );
}