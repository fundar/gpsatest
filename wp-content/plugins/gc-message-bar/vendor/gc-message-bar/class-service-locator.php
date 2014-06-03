<?php
if(class_exists("Gc_MessageBar_Service_Locator")){
    return;
}

class Gc_MessageBar_Service_Locator{
    protected static $services = array();
    public static function set($name,$service){
        self::$services[$name] = $service;
    }
    public static function get($name){
        if(!isset(self::$services[$name])){
            return null;
        }
        return self::$services[$name];
    }

    public static function has($name){
        return isset(self::$services[$name]);
    }
    public static function dump(){
        var_dump(self::$services);
    }

}