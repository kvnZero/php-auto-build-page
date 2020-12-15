<?php
namespace AutoBuild;

use AutoBuild\common\BaseCache;

class FileCache extends BaseCache{

    private function __construct(){}

    private static $json = [];

    private static $cache_file = APP_PATH.'_cache.json';

    private static function get_cache_file(){
        if(empty(self::$json)){
            if(file_exists(self::$cache_file)){
                $file_fd = fopen(self::$cache_file , "r");
                self::$json = json_decode(fread($file_fd, filesize(self::$cache_file)), true);
                fclose($file_fd);
            }else{
                self::$json = [];
                self::save_cache_file();
            }
        }
    }

    private static function save_cache_file(){
        $save_json = is_null(self::$json) ? '' : json_encode(self::$json); 
        $file_fd = fopen(APP_PATH.'_cache.json', "w");
        fwrite($file_fd, $save_json);
        fclose($file_fd);
    }

    public static function get_cache($post_title){
        self::get_cache_file();
        $post_title = basename($post_title,".php");
        return self::$json[$post_title] ?? 0;
    }

    public static function set_cache($post_title, $value){
        $post_title = basename($post_title,".php");
        $value = $value ?: time();
        self::get_cache_file();
        self::$json[$post_title] = $value;
        self::save_cache_file();
    }
}