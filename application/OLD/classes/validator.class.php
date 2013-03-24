<?php
class validator
{
	public $rules         = array(
		'pass'     => '^[a-zA-Z0-9-\+\$]+$^',
		'email'    => '^[\w\.-]+@[\w\.-]+\.\w{2,4}^',
		'match'    => '',
		'min'      => '',
		'max'      => '',
		'required' => '^.{1,}^', 
    );

	public $errorMessages = array(
		'pass'     => 'Invalid password. A password may contain numbers(0-9), letters(a-z,A-Z), hypens(-), plus signs(+), and dollar signs($).',
		'email'    => 'Please enter a valid email address.',
		'match'    => 'does not match',
		'min'      => 'does meet the required minimum length: ',
		'max'      => 'does not meet the required maximum length: ',
		'required' => 'is a required field.', 
    );

    public $message       = "";
    public $inputs        = array();

	public function isPOST()
	{
		if(count($_POST) < 1 )
			return FALSE;
		else
			return TRUE;
			
	}
	
	//validate makes sure the POST info is safe and that there is post info
		//then it cycles through POST and checks rules
	public function _validate()//returns TRUE if theres an error and false if not
	{
		if(!$this->isPOST())
			return TRUE;
		$this->check_inputs();
		
		if(!isset($this->error))
		return FALSE;
		
		
		return TRUE;
	}//eof validate
	
	//compares rules to the input
	public function check_inputs()
    {
		foreach($this->inputs as $input)
		{
			$input['data'] = $_POST[$input['name']];
			if(strlen($input['rules']) > 1)
				$this->{"check_".$input['type']}($input);
		}
	}//eof check inputs
	
	
	
	public function get_message()
	{
		if(!$this->isPOST())
			return FALSE;
		return $this->message;
	}
	
	public function check_text($input)
	{
		$data = $input['data'];
		$label = $input['label'];
		$this->_rules($data,$label,$input['rules']);
		
	}
	
	public function check_password($input)
	{
		$data = $input['data'];
		$label = $input['label'];
		$this->_rules($data,$label,$input['rules']);
		if($data != $_POST['passconf'])
		{
			$this->error = TRUE;
			$this->message .= '"<strong>'.$input['label']."</strong>\" and \"<strong>Confirm Password</strong>\" do not match".'<br />';
		
		}
	}
	
	public function check_textarea($input)
	{
		$data = $input['data'];
		$label = $input['label'];
		$this->_rules($data,$label,$input['rules']);
		
	}
	
	public function check_file($input)
	{
		$data = $input['data'];
		$label = $input['label'];
		$this->_rules($data,$label,$input['rules']);
		
	}
	
	public function check_select($input)
	{ 
		$data = $input['data'];
		$rules = explode(" ",$input['rules']);
			
		if( ( $data == 'noSelection' && in_array('required',$rules) ) || !in_array($data,$this->selects[$input['name']]) )
		{
			$this->error = TRUE;
			$this->message .= '"<strong>'.$input['label']."</strong>\" is required. Please select a(n) ".$input['label'].'<br />';
		}
	}
	
	public function check_hidden($input)
	{
		$data = $input['data'];
		$label = $input['label'];
		$this->_rules($data,$label,$input['rules']);
		
	}
	
	public function check_radio($input)
	{
		$data = $input['data'];
		$label = $input['label'];
		$this->_rules($data,$label,$input['rules']);
		
	}
	
	public function check_checkbox($input)
	{
		$data = $input['data'];
		$label = $input['label'];
		$this->_rules($data,$label,$input['rules']);
		
	}
	
	public function _rules($data,$label,$rules)
	{
		$rules = explode(" ",$rules);
		foreach($rules as $rule)
		{
			if(stripos($rule,"=") !== false)
			{
				$opt = explode("=",$rule);
				if($opt[0] == 'min')
				{
					$this->rules['min'] = '^.{'.$opt[2].',}^'; 
				}
				if($opt[0] == 'max')
				{
					$this->rules['max'] = '^.{0,'.$opt[2].'}^'; 
				}
				
			}
			else if(count($this->rules[$rule]) > 0)
			{	
				if(!preg_match($this->rules[$rule],$data))
				{
					$this->error = TRUE;
					$this->message .= '"<strong>'.$label."</strong>\" ".$this->errorMessages[$rule].'<br />';
					//$this->message = $this->get_input_label($input_name).' does not match required rule: '.$rule.'<br />';
				}
			}
		}
	}
}
?>
