<?php
namespace AutoBuild\common;

abstract class BaseCache{

    abstract public static function set_cache($post_title, $vlaue);

    abstract public static function get_cache($post_title);

    public static function get_cache_expires_time(){
        return CACHE_TIME; 
    }

    public static function is_expires($post_title){
        $expires_time = self::get_cache_expires_time();
        $create_time  = self::get_cache($post_title);
        return ($create_time + $expires_time) < time();
    }
}
