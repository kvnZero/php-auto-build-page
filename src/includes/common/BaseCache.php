<?php
namespace AutoBuild\common;

abstract class BaseCache{

    abstract public static function set_cache($post_title);

    abstract public static function get_cache($post_title);

    public static function get_cache_expires_time(){
        return CACHE_TIME; 
    }
}
