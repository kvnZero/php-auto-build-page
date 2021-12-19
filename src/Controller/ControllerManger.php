<?php


class ControllerManger
{
    protected static $instance;

    public function __construct()
    {}

    public static function setClass($class)
    {
        self::$instance = new $class();
    }

    public static function __callStatic($name, $arguments)
    {
        $include_file = OUTPUT_PATH . md5( get_class(self::$instance) . $name . $arguments[0]). '.php';

        if(self::hasCache($include_file)){
            return $include_file;
        }
        self::$instance->$name();

        self::$instance->build($include_file);

        return $include_file;
    }

    protected static function hasCache($include_file)
    {
        return !file_can_build_with_cache($include_file);
    }
}