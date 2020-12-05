<?php

use AutoBuild\BuildFile;
use AutoBuild\BuildPage;

define('BASE_DIR', __DIR__.'/');

include BASE_DIR.'common.php';
include BASE_DIR.'config/config.php';

if(empty(CURRENT_PAGE) || empty(CURRENT_POST)){
    $include_file = OUTPUT_PATH.'index.php';
    if(!file_exists($include_file)){
        $html = '';  
        foreach (post_file_list() as $filename) {
            $html .= '<li><a href="/?page=post&post='.$filename.'">'.$filename.'</a></li>';   
        }
        $buildFile = new BuildFile();
        $buildFile->setBaseFile(TEMPLATE_PATH.'_list.php');
        $buildFile->setOutPutFath($include_file);
        $buildFile->setSearchKey([
            'list'=>$html
        ]);
    }
}else{
    $include_file = OUTPUT_PATH.CURRENT_POST.'.php';
    if(!file_exists($include_file)){
        $filename = POST_PATH.'/'.CURRENT_POST.'.md';
        $file_fd = fopen($filename , "r") or die('Unable to open file');
        $content = fread($file_fd, filesize($filename));
        $parsedown = new Parsedown();
        $content = $parsedown->text($content); 

        $buildFile = new BuildFile();
        $buildFile->setBaseFile(TEMPLATE_PATH.'_post.php');
        $buildFile->setOutPutFath($include_file);
        $buildFile->setSearchKey([
            'content'=>$content
        ]);
    }
}

$buildPage = new BuildPage(); 
$buildPage->setBuildClass($buildFile);
$buildPage->build();

include $include_file;