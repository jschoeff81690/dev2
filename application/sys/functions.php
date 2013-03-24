<?php
//public functions_class -- public functions that are commonly used, not specific to other single classes
/* 
//**** public functions Used ****\\
/Log -- saves to error log file, sends email, 
	$str = string of error type ie POST or GET NOT SAFE
	$loc = file where error occured ie cart.php actions.php...etc
	
/safeitup -- checks if a string is sql safe, returns true or false respectively
/cyclePOST -- goes though all POSTs received and checks if sql safe returns true or false
/cycleGeT -- goes though all GETs received and checks if sql safe returns true or false
/DB public functionS -- query the DB
/ ~~DB_Connect(IP,USERNAME,PASSWORD,DB to connect) -- returns false if no connections, if connects successful then it wil return the connection 
/DB_Fetch - a one liner for sql query num rows and associative arrays
//returns array of all results or false
*/

class functions
{	

	public function format_money($number, $fractional=false) 
	{
		if ($fractional) 
		{
			$number = sprintf('%.2f', $number);
		}
		while (true) 
		{
			$replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
			if ($replaced != $number) {
				$number = $replaced;
			} else {
				break;
			}
		}
		return $number;
	}
		
	public function proper_case($str)
	{
		$str = str_replace("_"," ",$str);
		$words = explode(" ", $str);
		foreach($words as $word)
		{
			$s = strtolower($word);
			$s = substr_replace($s, strtoupper(substr($s, 0, 1)), 0, 1);
			$result .= "$s ";
		}
		return trim($result);		
	}
	
	public function get_date($date,$mo = TRUE,$da = TRUE,$ye = FALSE)//yyyy-mm-dd to month dd
	{
		$y = substr($date,0,4);
		$m = substr($date,5,2);
		$d = substr($date,8);
		
		$time = mktime(0, 0, 0, $m, $d, $y);
		if($mo && $da && !$ye)
			return date("M d", $time);
		if($mo && $da && $ye)
			return date("M d, Y", $time);
	}


    //* OBSOLETE ?? *//
	function file_rename($file)
	{
		$file = strtolower($file);
		$ext = strstr($file,".");
		return "user-id-".$this->user->id."-0".$ext;
	}
	
	public function helper($location)
	{
		require_once($location);
	}
	
	public function view($filename,$VARS = NULL)
	{
		$this->vars = $VARS;
		require_once("application/views/".$filename.".php");
	}
	
	public function load($class,$alt = FALSE)
	{
		
		if(is_array($class))
		{
			foreach($class as $cla)
			{
				$cl = strtolower($cla);
				$this->helper("application/classes/".$cl.".php");
				$this->$cla = new $cla();
				$this->$cla->par =& $this;
			}
		}
		else
		{
			$clas = strtolower($class);
			if($alt==FALSE)
				$alt = $class;
			$this->helper("application/classes/".$clas.".php");
			$this->$alt = new $class();
			$this->$alt->par =& $this;
			
			
		}
	}
	
	public function security_check()
	{
		if(!$this->cycleGET())
			unset($_GET);
		$this->cyclePOST();
		
	}
	
	public function check_triggers($triggers)
	{
		$location = $this->location;
		foreach($triggers as $trigger)
		{
			if($location == $trigger)
			{  
				$this->load($trigger);
				$this->$trigger->_init();
				return true;
			}
		}
		return false;
    }

    public function controller($filename,$alt = FALSE)
    {
            if(is_array($filename))
            {
                foreach($filename as $file)
                { 
                   
                    if(file_exists('application/controllers/'.$filename.".php"))
                    {
                        $cl = strtolower($file);
                        $this->helper("application/controllers/".$fl.".php");
                        $this->$file = new $file($this);
                        $this->$file->par =& $this;

                    }
                    else return FALSE;
                }
                
            }
            else
            {
                if(file_exists('application/controllers/'.$filename.".php"))
                {
                    $file = strtolower($filename);
                    if($alt==FALSE)
                        $alt = $filename;
                    $this->helper("application/controllers/".$file.".php");
                    $this->$alt = new $filename($this);
                    $this->$alt->par =& $this;
                }
                else
                    return FALSE;
             }
            return TRUE;
        
       	
    }
}//eof class


