<?php
if(class_exists("Gc_MessageBar_CF")){
    return;
}

class Gc_MessageBar_CF{
    protected static $prefix ="Gc_MessageBar";
    const C_PREFIX_SEPARATOR = "_";
    public static function set_prefix($prefix){
        self::$prefix = $prefix;
    }

    public static function get_prefix(){
        return self::$prefix;
    }

    public static function get_class_name($class_name,$prefix = false){
        $pref = self::$prefix;
        if ($prefix) {
            $pref = $prefix;
        }
        $pref = rtrim($pref,self::C_PREFIX_SEPARATOR);
        return $pref.self::C_PREFIX_SEPARATOR.$class_name;

    }

    public static function create($class_name){
        return self::create_with_prefix_and_init($class_name, self::$prefix);
    }

    public static function create_with_prefix_and_init($class_name,$prefix,array $option = array()){
        $prefix = rtrim($prefix,self::C_PREFIX_SEPARATOR);
        $real_class_name = $prefix.self::C_PREFIX_SEPARATOR.$class_name;
        $res = new $real_class_name();
        if(method_exists($res, "configure")){
            $res->configure($option);
        }
        return $res;

    }

    public static function create_with_prefix($class_name,$prefix){
        return self::create_with_prefix_and_init($class_name, $prefix);
    }

    public static function create_and_init($class_name,array $option){
        return self::create_with_prefix_and_init($class_name, self::$prefix, $option);
    }
}
