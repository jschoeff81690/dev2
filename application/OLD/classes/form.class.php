<?php

class form
{	
	public $labels = array();
	public $par = '';
	public $submit = FALSE;
	public $action = FALSE;
	public $details = FALSE;
	
	public function add_label($arr)
	{
		if($arr[0] == null)
			array_push($this->labels,$arr);
		else
		{
			foreach($arr as $ar)
			array_push($this->labels,$ar);
			
		}
	}
	
	public function set_action($str)
	{
		$this->action = $str;
	}
	
	public function set_details($str)
	{
		$this->details = $str; 
	}
	
	public function set_ajax()
	{
		$this->action = ' action="'.$this->par->BASEPATH.'/ajax/"';
	}
	
	public function display_form($label = TRUE)
	{
		//display error message if form validation has errors
		if($this->error)echo '<div class="error">'.$this->message.'</div>'."\n";
		if($this->ajax == TRUE)
		{
			$this->set_Ajax();
		}
		$form = '<form ';
		if( $this->action != FALSE)
		{
			$form = $form.$this->action;
		}
		else
		{
			$form = $form.'action=""';
		}
		
		if($this->details != FALSE)
		{
			$form = $form.$this->details;
		}
			
			$form = $form.' method="post">';
			echo $form.'<fieldset>'."\n";
		foreach($this->labels as $input)
		{
			$label = $input['label'];
			//$form = array_slice($input,1,-1);
			unset($input['rules']);
			$form = $input;
			//defaults
			if(!array_key_exists('type',$form))
				$form['type'] = 'text';
			if(!array_key_exists('size',$form))
				$form['size'] = '20';	
			if(!array_key_exists('maxlength',$form))
				$form['maxlength'] = '32';	
			if(!array_key_exists('value',$form))
				$form['value'] = '';
			if($label)	
				echo '<p><label for="'.$form['name'].'">'.$label."</label>\n";
			
			if($form['type'] == "textarea")
				echo '<textarea '; 
			else
				echo '<input ';
				

			foreach($form as $attribute => $value)
			{
				echo $attribute.'="'.$value.'" ';
			}
			if($form['type'] != "textarea")
				if($label)
					echo ' class="text" /></p>'."\n";
				else
					echo '/>'."\n";
			else
				echo ' class="text" ></textarea></p>'."\n";
			
		}
		if(!$this->submit)
			echo '<input type="submit" value="Submit" /></fieldset></form>';
		else
			echo $this->getSubmit().'</fieldset></form>';
	}
	
	public function validate()
	{	
		
		if(!$this->par->cyclePOST() || count($_POST) < 1 )
		{ 
			if(count($_POST) > 0)
			{
				$this->error = TRUE;
				$this->message = 'The information sent is unsafe!';
			}
			else
				$this->message = '';
			
		}
		else
		{
		
			foreach($this->labels as $input)
					if(isset($_POST[$input['name']]))
					{
						$this->check_rules($_POST[$input['name']],$input['rules']);
						
					}
			if(!isset($this->error))
			{
				$this->error = FALSE;
			}
		}
		
	}
	public function check_rules($input,$rules)
	{
		$defaults = array(
		'pass'     => '^[a-zA-Z0-9-\+\$]+$^',
		'email'    => '^[\w\.-]+@[\w\.-]+\.\w{2,4}^',
		'match'    => '',
		'min'      => '',
		'max'      => '',
		'required' => '^.{1,}^', 
		);
		$rules = explode(" ",$rules);
		foreach($rules as $rule)
		{
			if(stripos($rule,"=") !== false)
			{
				$opt = explode("=",$rule);
				if($opt[0] == 'match')
				{
					if($input != $_POST[$opt[1]])
					{
						$this->message .= "Invalid: ".$input.' does not match '.$opt[1];
						$this->error = TRUE;
					}
				}
				if($opt[0] == 'min')
				{
					$defaults['min'] = '^.{'.$opt[2].',}^'; 
				}
				if($opt[0] == 'max')
				{
					$defaults['max'] = '^.{0,'.$opt[2].'}^'; 
				}
				
			}
			else if(count($defaults[$rule]) > 0)
				if(!preg_match($defaults[$rule],$input))
				{
					$this->error = TRUE;
					$this->message = $input.' does not match required rule: '.$rule;
				}
		}
	}
	
	public function get_params()
	{
		$params = array();
		foreach($this->labels as $label)
		{
				if($_POST[$label['name']] != '' && $label['name'] != 'passconf')
				{
					$params[$label['name']] = $_POST[$label['name']];
				}
		}
		return $params;
	}	
	
	public function fill_values($sql,$value)
	{
		$result = $this->par->DB->{$sql}($value);
		if(!$result)
			echo 'ERROR';
		else
		{
			foreach($this->labels as $key => $label)
			{
				if( isset($result[$label['name']]) AND $result[$label['name']] != "" AND !isset($label['value']))
				{
						$label['value'] = $result[$label['name']];
						$this->labels[$key] = $label;
				}
			}
		}
	}
}