<?php

use AutoBuild\BuildFile;
use AutoBuild\BuildPage;

define('BASE_DIR', __DIR__.'/');

include BASE_DIR.'common.php';
include BASE_DIR.'hook.php';
include BASE_DIR.'config/config.php';

$routes = include BASE_DIR.'route.php';

$controller_class  = null;
$controller_method = null;

if(empty(CURRENT_PAGE)) {
    $controller_class = $routes['/'][0] ?? null;
    $controller_method = $routes['/'][1] ?? null;
}else{
    foreach($routes as $k => $v){
        if(strpos($k, CURRENT_PAGE) !== false) {
            $controller_class = $v[0];
            $controller_method = $v[1];
            break;
        }
    }
}

ControllerManger::setClass($controller_class);

$include_file = ControllerManger::$controller_method();

include $include_file;
