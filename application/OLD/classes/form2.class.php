<?php

class form2
{	
	public $inputs = array();
	//inputs are an array of inputs
	//each input is an array containing:
		//label
		//name
	//optional values are
		//type -textfield
		//rules - rules for validating 
	
	public $par      = '';
	public $submit   = FALSE;
	public $action   = FALSE;
	public $details  = FALSE;
	public $autofill = FALSE;
	public $defaultMAX  = 32;
	public $defaultSIZE = 20;
	public $selects = array();

	
	public function _init($action = FALSE,$details = FALSE,$autofill = FALSE,$validate = FALSE)
	{
		$this->set_action($action);
		$this->set_details($details);//could be form id
		$this->autofill = $autofill;//set only when editing something from db
		$this->validate = $validate;//use if needs form validation like pass match or ==email
	}
	
	public function set_inputs($inputs,$sql = FALSE,$value = FALSE)
	{
		$_SESSION['inputs'] = $inputs;//just in case of error and can be used for refill.
		//if the input is not an array, create one and add it to it so it can be processed
		if($inputs[0] === null)
		{
			$iin = $inputs;
			$inputs[0] = $iin;
		}
		$tabCount = 1; //used for tab index
		//check if array
		foreach($inputs as $input)
		{
			if(!array_key_exists('type',$input))// sets default type as "text"
				$input['type'] = 'text';
			if(!array_key_exists('size',$input))//set default size
				$input['size'] = $this->defaultSIZE;	
			if(!array_key_exists('maxlength',$input))//sets default max length
				$input['maxlength'] = $this->defaultMAX;	
			if(!array_key_exists('value',$input))// sets a empty value if there isnt already a value
				$input['value'] = '';
			if(!array_key_exists('rules',$input))// sets a empty value if there isnt already a value
				$input['value'] = '';
			$input['tabindex'] = $tabCount;
			$tabCount++;
			array_push($this->inputs,$input);
		}
		$submit = array(
				'label' => '',
				'name'  => 'submit',
				'type'  => 'submit',
				'value' => 'submit',
				'id'    => 'submit',
				'rules' => ''   
			);
		array_push($this->inputs,$submit);
			
		
		if($this->autofill)
			$this->_autofill($sql,$value);
			
		if($this->validate)
		{
			$this->par->load("validator");
			$this->v = $this->par->validator;
			unset($this->par->validator);
			$this->v->inputs = $this->inputs;
			$this->v->selects = $this->selects;
			$this->error = $this->v->_validate();
			$this->message = $this->v->get_message();
		}
	}//eof set_inputs

	public function set_action($str)
	{
		if(!$str)
			$this->action = 'action="" ';
		else
		{
			if($str == 'ajax')
				$this->set_ajax();
			else
				$this->action = $str.' ';
		}
	}//eof action
	
	public function set_details($str)//can be used to set id or class
	{	
		if(!$str)
			$this->details = '';
		else
			$this->details = $str; 
			
	}//eof details
	
	public function set_ajax()
	{
		$this->action = 'action="'.$this->par->BASEPATH.'/ajax/" ';
	}//eof set ajax
	
	public function display()
	{
		//display error message if form validation has errors
		if($this->error && $this->message != FALSE)echo '<div class="error">'.$this->message.'</div>'."\n";
		
		$form = '<form class="form" '.$this->action." ".$this->details.' method="post">'."\n";
		echo $form;
		
		//so far we have 
		//<form action="if any then here" details(maybe id="form") method="post"> 
		//<fieldset>
		
		$this->display_inputs();
		
		echo "\n".'</form>';
	
	}//eof display
	
	public function display_inputs()
	{
		foreach($this->inputs as $input1)
		{
			$input = $input1;
			$this->{"create_".$input['type']}($input);
		}//eof inputs loop
	}//eof display_inputs
	
	public function get_inputs()
	{
        $params = array();
		foreach($this->inputs as $label)
		{
            if($_POST[$label['name']] != '' && $label['name'] != 'passconf' && $label['name'] != 'submit' && $_POST[$label['name']] != "--------")
				{
					$params[$label['name']] = $_POST[$label['name']];
				}
		}
		return $params;
	}

	
	//set_select is used to add options for an of type select and SETS the select to that input name
	public function set_select($name,$values)
	{
		$this->selects[$name] = $values;
	}
	
	
	//these create the inputs for each type of input to be displayed
	public function create_text($input)
	{
		//<div class="form-field"><label for="label_name">Label_Name</label>
		$label = '<div class="form-field"><label for="'.$input['name'].'">'.$input['label'].'</label>'."\n";
		unset($input['label']);//same reason ^^ but the label	
		
		
		$inputh = '<input class="text-input" ';
		unset($input['rules']);//so the rule arent added to the html form
			
		foreach($input as $param => $value)
		{
			$inputh .= $param.'="'.$value.'" ';
		}
		
		$inputh .=  '/></div>'."\n";
		
		
		echo $label.$inputh;//output the label
	}
	
	public function create_password($input)
	{
		//<div class="form-field"><label for="label_name">Label_Name</label>
		$label = '<div class="form-field"><label for="'.$input['name'].'">'.$input['label'].'</label>'."\n";
		
		unset($input['label']);//same reason ^^ but the label	
		
		$inputh = '<input class="pass-input" ';
		unset($input['rules']);//so the rule arent added to the html form
			
		foreach($input as $param => $value)
		{
			$inputh .= $param.'="'.$value.'" ';
		}
		
		$inputh .=  '/></div>'."\n";
		
		
		echo $label.$inputh;//output the label
	}
	

	public function create_textarea($input)
	{
		//<div class="form-field"><label for="label_name">Label_Name</label>
		$label = '<div class="form-field"><label for="'.$input['name'].'">'.$input['label'].'</label>'."\n";
		
		
		unset($input['label']);//same reason ^^ but the label	
		$inputh = '<textarea class="textarea-input" ';
		unset($input['rules']);//so the rule arent added to the html form
		unset($input['label']);//same reason ^^ but the label
		foreach($input as $param => $value)
		{
			$inputh .= $param.'="'.$value.'" ';
		}
		
		$inputh .=  '></textarea></div>'."\n";
		
		
		echo $label.$inputh;//output the label
	}
	
	public function create_select($input)
	{
		//<div class="form-field"><label for="label_name">Label_Name</label>
		$label = '<div class="form-field"><label for="'.$input['name'].'">'.$input['label'].'</label>'."\n";
		
		
		$inputh = '<select class="select-input" ';
		
        $selected = FALSE;
   
		unset($input['rules']);//so the rule arent added to the html form
		unset($input['label']);//same reason ^^ but the label	
		unset($input['type']);
		unset($input['maxlength']);
        unset($input['size']);
        if($input['value'] != "")
        {
            $selected = $input['value'];
        }
        unset($input['value']);	

        foreach($input as $param => $value)
		{
			$inputh .= $param.'="'.$value.'" ';
		}
		
        $inputh .=  '>';
        if(!$selected)
		 $inputh .= '<option value="" selected="selected">Please select..</option> ';
		foreach($this->selects[$input['name']] as $option => $value )
        {

            $inputh .= '<option ';
            if(!!$selected AND $value==$selected)
                $inputh .= 'selected="selected" ';
            $inputh .= 'value="'.$value.'">'.$option.'</option>'."\n"; 
		}
		
		$inputh .= '</select></div>'."\n";
		
		//for select, selected="selected" inside option tag for the selected option when filling values
		echo $label.$inputh;//output the label
	}
	
	public function create_radio($input)
	{
		# code...
	}

	public function create_checkbox($input)
	{
		# code...
		
		
		//<input  value="1" name="$name" id="$name" type="checkbox"><label for="$name">$label</label>
		
	}

	public function create_file($input)
	{
		# code...
	}
	
	public function create_hidden($input)
	{
        unset($input['rules']);//so the rule arent added to the html form
        unset($input['label']);//same reason ^^ but the label	
		unset($input['maxlength']); //^
		unset($input['size']);      //^
		$inputh = '<input ';
			
		foreach($input as $param => $value)
		{
			$inputh .= $param.'="'.$value.'" ';
		}
		
		$inputh .=  '/></div>'."\n";
		
		
		echo $label.$inputh;//output the label
	}
	
	public function create_submit($input)
	{
		$inputh = '<input ';
		
		unset($input['rules']);//so the rule arent added to the html form
		unset($input['label']);//same reason ^^ but the label
			
		foreach($input as $param => $value)
		{
			$inputh .= $param.'="'.$value.'" ';
		}
		
		$inputh .=  '/></div>'."\n";
		
		echo $inputh;//output the label
	}
	
	public function _autofill($sql,$value)//sql(DB method) to search for and value to input
	{
		if(!$sql || !$value)
			return FALSE;
		$result = $this->par->DB->{$sql}($value);
		if(!$result)
			return FALSE;
		else
        {
			foreach($this->inputs as $key => $label)
            {
				if( isset($result[$label['name']]) AND $result[$label['name']] != "" AND ($label['value'] == "") AND ($label['name'] != "pass"))
				{
						$label['value'] = $result[$label['name']];
						$this->inputs[$key] = $label;
				}
			}
		}
	}
	//returns an array to add a select state input
	public function get_states_array()
	{
		return array( 
			'Alaska' => 'AK', 
			'Arizona' => 'AZ', 
			'Arkansas' => 'AR', 
			'California' => 'CA', 
			'Colorado' => 'CO', 
			'Connecticut' => 'CT', 
			'Delaware' => 'DE', 
			'District of Columbia' => 'DC', 
			'Florida' => 'FL', 
			'Georgia' => 'GA', 
			'Hawaii' => 'HI', 
			'Idaho' => 'ID', 
			'Illinois' => 'IL', 
			'Indiana' => 'IN', 
			'Iowa' => 'IA', 
			'Kansas' => 'KS', 
			'Kentucky' => 'KY', 
			'Louisiana' => 'LA', 
			'Maine' => 'ME', 
			'Maryland' => 'MD', 
			'Massachusetts' => 'MA', 
			'Michigan' => 'MI', 
			'Minnesota' => 'MN', 
			'Mississippi' => 'MS', 
			'Missouri' => 'MO', 
			'Montana' => 'MT', 
			'Nebraska' => 'NE', 
			'Nevada' => 'NV', 
			'New Hampshire' => 'NH', 
			'New Jersey' => 'NJ', 
			'New Mexico' => 'NM', 
			'New York' => 'NY', 
			'North Carolina' => 'NC', 
			'North Dakota' => 'ND', 
			'Ohio' => 'OH', 
			'Oklahoma' => 'OK', 
			'Oregon' => 'OR', 
			'Pennsylvania' => 'PA', 
			'Rhode Island' => 'RI', 
			'South Carolina' => 'SC', 
			'South Dakota' => 'SD', 
			'Tennessee' => 'TN', 
			'Texas' => 'TX', 
			'Utah' => 'UT', 
			'Vermont' => 'VT',
			'Virginia' => 'VA', 
			'Washington' => 'WA', 
			'West Virginia' => 'WV', 
			'Wisconsin' => 'WI', 
			'Wyoming' => 'WY'
		); 
	}	
}

