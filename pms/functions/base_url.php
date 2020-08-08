<?php
if (session_status() === PHP_SESSION_NONE) { session_start();}
$path = pathinfo($_SERVER['PHP_SELF']);
if($path['filename'] === 'base_url') {
    header('Location: ../404.php');
}

//  site base url
function base_url() {
    $path = sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        $_SERVER['PHP_SELF']
    );
    return in_array('functions', explode('/', dirname($path))) ? dirname(dirname($path)).'/':dirname($path).'/';
}
$base_url = base_url();
