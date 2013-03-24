<?php 
//required files for master
 require_once("application/sys/functions.php");//used for random functions, there ar no actual functions in master.php
// require_once("includes/phpf/classes/user.class.php");//user info
// require_once("includes/phpf/classes/login.class.php");//user info
// require_once("includes/phpf/GLOBALS.php");//..actually is the config file, havent changed name yet
// require_once("includes/phpf/classes/db.class.php");//...
// require_once("includes/phpf/classes/controller.php");//...
// require_once("includes/phpf/models/model.php");//...

class master extends functions
{

	public function __construct()
	{
        self::helper("application/sys/config.php");
        $config = new config($this);
        //loads resources and creates them as objects of this class
		$this->load($config->resources);
				
		// initiate login (check if user is logged or not and sets basic user info if is)
        //$this->login->_init();
        //// //before anything else unlog the user if they want to be
		// if($this->location == "Logout")
		// 	$this->login->logout();
		// 
		// //initialize user class with login info
		// $this->user->_init($this->login->user);

        // self::helper('application/classes/db.class.php');
        // $this->DB = new DB();
        
		//connect to DB
		$this->DB->con = $config->_db();

        // self::helper('application/classes/template.php');
        // $this->template = new template();
        // $this->template->BASEPATH = $config->ROOTURL;
       // $this->template->par =&$this;
        // //$this->user     = new user_class($this,$this->login->user);
		$triggered= FALSE;
		// //got to main view
		if(!$triggered)
        {

            if(!$this->controller($this->location))
            { 
                $this->location = $config->default_controller;
                $this->controller($this->location);
            }
            $this->{$this->location}->index();    

		}
		
		
		//echo memory_get_usage() . "\n";
		//echo memory_get_peak_usage() . "\n"; 
	}//eof construct
	
}//eof class 
