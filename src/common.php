<?php
function param($name){
    return htmlspecialchars($_REQUEST[$name]?? ''); 
}

function static_file_list(){
    $handler = opendir(OUTPUT_PATH);
    while (($filename = readdir($handler)) !== false){
        if($filename == '.' || $filename == '..') continue;
        $files[] = $filename;
    }
    closedir($handler);
    return $files;
}

function static_page_exists($filename){
    return in_array($filename, static_file_list());
}

function post_file_list(){
    $handler = opendir(POST_PATH);
    while (($filename = readdir($handler)) !== false){
        if($filename == '.' || $filename == '..') continue;
        $files[] = pathinfo($filename, PATHINFO_FILENAME);
    }
    closedir($handler);
    return $files;
}
