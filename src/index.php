<?php

use AutoBuild\BuildFile;
use AutoBuild\BuildPage;

define('BASE_DIR', __DIR__.'/');

include BASE_DIR.'common.php';
include BASE_DIR.'hook.php';
include BASE_DIR.'config/config.php';

if (empty(CURRENT_PAGE) || empty(CURRENT_POST)) {
    $include_file = OUTPUT_PATH.'index.php';
    if (file_can_build_with_cache($include_file)) {
        $list_html = '';  
        foreach (post_file_list() as $filename) {
            if(empty($filename)) continue;
            $list_html .= '<li><a href="/?page=post&post='.$filename.'">'.$filename.'</a></li>';   
        }

        $template_file = TEMPLATE_PATH.'_list.php';
        $search_keys   = [
            'list'=>$list_html
        ];
    }
} else {
    $post = POST_STATIC_FILE_MAD5 ? md5(CURRENT_POST): CURRENT_POST;
    $include_file = OUTPUT_PATH.$post.'.php';
    if (file_can_build_with_cache($include_file)) {
        $filename = POST_PATH.'/'.CURRENT_POST.'.md';
        if(!file_exists($filename)){
            die("post not found");
        }
        $file_fd = fopen($filename , "r") or die('Unable to open file');
        $content = fread($file_fd, filesize($filename));
        fclose($file_fd);
        $search_keys = find_post_head_and_clear($content);
        $parsedown = new Parsedown();
        $content = $parsedown->text($content); 

        $template_file = TEMPLATE_PATH.'_post.php';
        $search_keys   += [
            'content'=>$content
        ];
    }
}

if(isset($template_file)){
    $buildFile = new BuildFile();
    $buildFile->setBaseFile($template_file);
    $buildFile->setOutPutFath($include_file);
    $buildFile->setSearchKey($search_keys);

    $buildPage = new BuildPage(); 
    $buildPage->setBuildClass($buildFile);
    $buildPage->build();
    file_set_cache_now($include_file);
}
include $include_file;