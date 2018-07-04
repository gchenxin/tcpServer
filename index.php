<?php 
    require_once './vendor/autoload.php';
    
    define('ROOT_PATH', __DIR__);
    define('CONF_PATH', ROOT_PATH.'/conf');
    define('LOG_PATH', ROOT_PATH.'/log');
    
    $test = new server\Server();
    $test->start();
?>