<?php 

define('APP_PATH', __DIR__ . '/');

include APP_PATH.'/vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    if(strstr($class_name, 'AutoBuild')){
        require_once APP_PATH.'/src/'.str_replace('\\','/',$class_name) . '.php';
    }
});

include APP_PATH.'/src/index.php';