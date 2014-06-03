<?php
if(class_exists('Gc_MessageBar_Option_Repository_Factory')) {
    return;
}

class Gc_MessageBar_Option_Repository_Factory{
    private static $repository = null;

    public function get_instance()
    {
        if(null === self::$repository) {
            self::$repository = Gc_MessageBar_CF::create("Option_Repository");
        }
        return self::$repository;
    }

    public function set_instance($repository)
    {
        self::$repository = $repository;
    }        
}

