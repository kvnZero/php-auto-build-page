<?php
use AutoBuild\RedisCache;
use AutoBuild\FileCache;

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

function loadControllerFiles(){
    $handler = opendir(BASE_DIR . '/Controller/');
    $files   = [];
    while (($filename = readdir($handler)) !== false){
        if($filename == '.' || $filename == '..') continue;
        $files[] = BASE_DIR . '/Controller/' . pathinfo($filename, PATHINFO_FILENAME) . '.php';
    }
    closedir($handler);
    sort($files);
    foreach ($files as $file){
        include $file;
    }
}

function post_file_list(){
    $posts = [];
    $handler = opendir(POST_PATH);
    while (($filename = readdir($handler)) !== false){
        if($filename == '.' || $filename == '..') continue;
        if (strtolower(pathinfo($filename, PATHINFO_EXTENSION) ) != 'md') continue;

        $full_filename = POST_PATH . $filename;

        $file_fd = fopen($full_filename, "r") or die('Unable to open file');
        $content = fread($file_fd, filesize($full_filename));
        fclose($file_fd);
        $post_info = find_post_head_and_clear($content);
        
        if(empty($post_info)) continue;

        $posts[] = [
            'filename' => pathinfo($filename, PATHINFO_FILENAME),
            'filepath' => $full_filename,
            'filetime' => strtotime($post_info['post_date'] ?? fileatime($full_filename)),
        ];
    }
    closedir($handler);
    array_multisort(array_column($posts, 'filetime'), SORT_DESC, $posts);
    
    return array_column($posts, 'filename');
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

function get_cache_system(){
    switch (CACHE_SYSTEM) {
        case 'Redis':
            if(!class_exists('Redis')) return FileCache::class;
            return RedisCache::class;
            break;
        case 'File':
        default:
            return FileCache::class;
            break;
    }
}

function file_can_build_with_cache($filename){
    $cache_system = get_cache_system();
    return (!file_exists($filename) || $cache_system::is_expires($filename) || DEBUG_ALLWAYS_BUILD);
    //判断文件是否存在 或者文件缓存是否过期 判断是否需要总是生成页面
}

function file_set_cache_now($filename){
    $cache_system = get_cache_system();
    return $cache_system::set_cache($filename, time());
    //判断文件是否存在 或者文件缓存是否过期 判断是否需要总是生成页面
}