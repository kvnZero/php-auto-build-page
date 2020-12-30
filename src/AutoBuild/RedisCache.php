<?php
namespace AutoBuild;

use AutoBuild\common\BaseCache;
use Redis;

class RedisCache extends BaseCache{
    private static $instance;

    private static $cache_key = 'blog:filesystem:cache:'; //自定义

    private function __construct(){}

    private static function get_instance(){
        if(is_null(self::$instance)){
            $redis = new Redis();
			$redis->pconnect('127.0.0.1', '6379');
            self::$instance = $redis;
        }
        return self::$instance;
    }

    public static function get_cache($post_title){
        $post_title = basename($post_title, '.php');
        return self::get_instance()->get(self::$cache_key.$post_title) ?: 0;
    }

    public static function set_cache($post_title, $value){
        $post_title = basename($post_title, '.php');
        self::get_instance()->setEx(self::$cache_key.$post_title, self::get_cache_expires_time(), $value);
    }
}