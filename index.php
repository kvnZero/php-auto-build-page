<?php

use AutoBuild\BuildFile;
use AutoBuild\BuildPage;
use AutoBuild\RedisCache;

define('BASE_DIR', __DIR__);

include BASE_DIR.'/src/config/config.php';

if(empty(CURRENT_PAGE) || empty(CURRENT_POST)){
    include OUTPUT_PATH.'index.php';
}else{

    $filename =  (new BuildPage())->setBuildClass(new BuildFile())
            ->setCacheSystem(new RedisCache())
            ->buildHtml();

    include $filename;
}