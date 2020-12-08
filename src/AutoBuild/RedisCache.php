<?php
namespace AutoBuild;

use AutoBuild\common\BaseCache;
use Redis;

class RedisCache extends BaseCache{
    private static $instance;

    private static $cache_key = 'blog:filesystem:cache:filename'; //自定义

    private function __construct(){}

    private static function get_instance(){
        if(is_null(self::$instance)){
            $redis = new Redis();
			$redis->pconnect('127.0.0.1', '6379');
            self::$instance = $redis;
        }
        return self::$instance;
    }

    public static function get_cache($post_tile){
        self::get_instance()->hget(self::$cache_key, $post_tile);
    }

    public static function set_cache($post_title, $value){
        self::get_instance()->hSet(self::$cache_key, $post_title, $value);
    }
}