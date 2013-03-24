<?php
session_start();
require_once("application/sys/master.php");
$master = master::get_instance();
        
//create the config class
    $master->helper('application/sys/config.php');
    $master->config = new config();
    
    //load resources
    $master->load($master->config->resources);

    //create db connection
    $master->DB->con = $master->config->_db();
    

    if(!$master->config->triggered()) {

        
        if($master->location == 'logout')
            $master->login->logout();
        
        //check if location is safe, if not make it safe!!!!
        if(!$master->controller($master->location)) { 
            $master->location = $master->config->default_controller;
            $master->controller($master->location);
        }

        //goto location->index
        $master->{$master->location}->index();  

    }

    function redirect($loc) {
        header('Location: '.BASEPATH.$loc);
    }
