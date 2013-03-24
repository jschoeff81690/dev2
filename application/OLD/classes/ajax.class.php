<?php

class easyajax
{
	$this->par = '';
	
	public function create()
	{
		
		$this->par->helper('includes/phpf/classes/form.class.php');
		$fb = new form_class($this->par); 
		$params = array(
			
		);
		$fb->add_label($params);
	}
}