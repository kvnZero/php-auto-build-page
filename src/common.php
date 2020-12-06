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

function find_post_head_and_clear(&$content){
    $content_all = explode("\n", $content);
    $head = -1;
    $keys = [];
    foreach ($content_all as $line) {
        if($line == '/---'){
            $head++;
            $keys[$head] = $line;
        } else if ($line == '---/'){
            $head++;
            $keys[$head] = $line;
            break;
        } else {
            $head++;
            $keys[$head] = $line;
        } 
    }
    $search_key = [];
    foreach ($keys as $key => $value) {
        if($value != '/---' && $value != '---/') {
            preg_match('/(?<key>[a-z]+): +(?<value>.+)/', $value, $matches);
            $search_key['post_'.$matches['key']] = $matches['value'];
        }

        unset($content_all[$key]);
    }
    $content = join(PHP_EOL, $content_all);
    return $search_key;
}