<?php 
//required files for master
 require_once("application/sys/functions.php");//used for random functions, there ar no actual functions in master.php
// require_once("includes/phpf/classes/user.class.php");//user info
// require_once("includes/phpf/classes/login.class.php");//user info
// require_once("includes/phpf/GLOBALS.php");//..actually is the config file, havent changed name yet
// require_once("includes/phpf/classes/db.class.php");//...
// require_once("includes/phpf/classes/controller.php");//...
// require_once("includes/phpf/models/model.php");//...

class master extends functions { 
    
    private static $instance;

    private function __construct() {}

    public static function get_instance()
    {
        if(!self::$instance)
        {
            self::$instance = new master();
        }
        return self::$instance;
    }
}//eof class 
