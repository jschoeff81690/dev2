<?php

class phpMyAdmin
{
	public function _init()
	{
		if($this->par->login->LOGGED)
		{
			
			header("Location: http://www.justinschoeff.com/dev/phpMyAdmin-1/index.php");
		}
	}
	
}