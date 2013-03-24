<?php

/**
 * Config Class has all config info as vars
 **/
class config
{
    public $master;
    
    function __construct()
    {
        $this->master = master::get_instance();
        
        /* PHP SITE INFO */
        $config = array(
            'TITLE' => 'Client Management Panel',
	    'BASEPATH'     => 'http://localhost/dev2/dev2',
	    'ASSETS'    => 'http://localhost/dev2/dev2/application/assets'
        );
        define('BASEPATH',$config['BASEPATH']);
        foreach($config as $key => $value)// got lazy so i added the object this way
            $this->master->{$key} = $value;

        
        // default controller
        $this->default_controller = "handler";
        
        // errorS~!!!
			ini_set('display_errors', 1);
			error_reporting(E_ALL | E_STRICT);


        // Database info// 
         $db = array(
		   'DB_IP'         => 'localhost',
	    'DB_USER'      => 'root',
	    'DB_PASS'      => 'root',
	    'DB_NAME'      => 'webdev'
        );
        foreach($db as $key => $value)//  got lazy so i added the  this way
        {
            $this->{$key} = $value;
        }

        //  words that if are equal to first segment of url, override normal activity and call that class name first.
        //   ie if url is http://  www.site.com/trigger and "trigger" is in triggers list, then it will load the trigger class
        $this->triggers = array("ajax","phpMyAdmin");
        		
		// check if GETS AND POSTS ARE SAFE//  - will unset them if unsafe
		//  $this->security_check();
		//  
		//  // sets location and URL Segments
        $this->set_location();
        


        // resources to be loaded before anything
        $this->resources = array("DB","template","fb","validator",'login');

    }

	public function _db()// returns false if no connections, if connects successful then it wil return the connection 
	{
	   $con = mysql_connect($this->DB_IP, $this->DB_USER, $this->DB_PASS);
		if (!$con){return false;}
		else {mysql_select_db($this->DB_NAME,$con);return $con;}// eof else
    }

	
	public function safeitup($String)// returns the true if its safe else it will return false
		{
			if (preg_match('^[a-zA-Z0-9-\s\+\$\.\,/\!\?]+$^',$String)) 
			{
				return true;
			}
			else
			{
				return false;
			}
		}// eof safeitup
		
	public function safeEmail($String)
	{
		if (preg_match('^[\w\.-]+@[\w\.-]+\.\w{2,4}^',$String)) 
			{
				return true;
			}
			else 
			{
				return false;
			}
	}// eof  safeEmail
	
	public function _regex($string,$data)
	{
		if (preg_match($string,$data)) 
			{
				return true;
			}
			else 
			{
				return false;
			}
	}
	
	
	public function cyclePOST()
	{
		foreach($_POST as $key => $value)  
		{
		
			// $_POST[$key] = mysql_real_escape_string($value,$this->db->co);
			if($key == 'email')
			{	
				if(!$this->_regex('^[a-zA-Z0-9-@\s\+\$\.\,/\!\?]+$^',$value))
					$_POST[$key]= '--------';
			}// eof if email
			else
			{
				if(!$this->safeitup($value))
					$_POST[$key]= '--------';
			}
			
		}// eof for each
		
	}// eof cyclePOST
    
    public function cycleSESS()
	{
		$sCount=0;// safe values count
		$count=0;// count
		// is scount and count are eqaul after for loop then all posts are safe and php can continue
		foreach($_SESSION as $key => $value)  
		{
			if($this->safeitup($value))
			 {
				$sCount ++;
			 }
			$count++;
		}// eof for each
		if($count == $sCount)
		return true; // true for its safe
		else
		return false;
	}// eof cyclePOST
	
	public function cycleGET()
	{
		$sCount=0;// safe values count
		$count=0;// count
		// is scount and count are equal after for loop then all posts are safe and php can continue
		foreach($_GET as $key => $value)  
		{
			 if($this->safeitup($value))
			 {
				$sCount ++;
			 }
			$count++;
		}// eof for each
		if($count == $sCount)
		return true; // true for its safe
		else
		return false;
	}// eof cyclePOST
	
	public function set_location()
    {
        if ( isset($_GET["loc"]))
		{
			$loc = "===";
			$len = strlen($_GET["loc"]);
            $url = $_GET["loc"];

            // remove trailing slash e.g., site/profile/ becomes site/profile
			if(substr($_GET["loc"],-1) == "/")
                $url = substr($url,0,-1);

            $url = explode("/",$url);
            $this->master->URL = $url;

			$location = $url[0];			

            if(isset($url[1]) AND is_numeric($url[1]))// if url[1] is there it could be the location id or an action
				$this->master->locationID = $url[1];
			else
                $this->master->locationID = FALSE;

			$this->master->action = FALSE;	
			if(!$this->master->locationID AND isset($url[1]))
				$this->master->action = $url[1];
			if(isset($url[2]) && is_numeric($url[1]))
                $this->master->action = $url[2];

            if($this->safe_location($location))
            {
                $this->master->location  = $location;
            }
            else $this->master->location = $this->default_controller;
		}
		else if(isset($_SESSION['ref']))
		{
			$url = $_SESSION['ref'];
			$this->master->URL = $url;
			$location = $url[0];			
			if(is_numeric($url[1]))
				$this->master->locationID = $url[1];
			else
				$this->master->locationID = FALSE;
			$this->master->action = FALSE;	
			if(!$this->master->locationID)
				$this->master->action = $url[1];
			if(isset($url[2]) && is_numeric($url[1]))
                $this->master->action = $url[2];
            
            if($this->safe_location($location))
            {
                $this->master->location  = $location;
            }
            else $this->master->location = $this->default_controller;
		}
		else
		{
			
			$this->master->action = FALSE;
			$this->master->locationID = FALSE;
			$this->master->location =  $this->default_controller;
		}
	}// eof get location
	
	public function safe_location($location)
	{
		$loc = str_replace("_"," ",$location);
        return true;
		$result = $this->DB->find_locations_by_location_limit($loc);		
		if(!$result)
		{
			$result2 = $this->DB->find_sublocations_by_sublocation_limit($loc);		
			if(!$result2)
			return false;
			else
			return TRUE;
		}
		else
		return TRUE;
    }
	public function security_check()
	{
		if(!$this->cycleGET())
			unset($_GET);
		$this->cyclePOST();
		
    }

    public function triggered() {
        
        if(!isset($this->triggers))
            return false;
        if(is_array($this->triggers))
            foreach($this->triggers as $trigger)
                if($trigger == $this->master->location)
                    return true;// needs to change to something..
    }
}



// paypal
// APIKEY => 'ALMh2qiBud1rQz9jSS863lHk3wntAdJYSSCHeQUoyY0WWBKz.5ag-2Lj',// paypal signature
// APIUSER => 'justnj_1249596700_biz_api1.gmail.com',
// APIPASS ;=> '1249596710',
?>
