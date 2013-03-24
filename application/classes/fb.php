<?php
/* 
 * ex of
 * load the form and create it
 *
  require_once("form3.class.php");
  $fb = new form3();
 *
 *
 * Initialize it - no action attr, autofill the form == true, validate the form == true
  $fb->_init(false,TRUE,TRUE);
 *
 * 
 *create an array by group of fields
    //$logininfo = array(
        //array(
            //'label' => 'Email Address',
            //'name'  => 'email',
            //'rules' => 'email'
        //),
        //array(
            //'label' => 'New Password',
            //'type'  => 'password',
            //'name'  => 'pass',
            //'value' => '',
            //'rules' => 'pass'
        //),
        //array(
            //'label' => 'Confirm Password',
            //'type'  => 'password',
            //'name'  => 'passconf',
            //'value' => '',
            //'rules' => 'pass match=pass'
        //));
 *
 *add the array to the form list with no title, a class and the array to be added
    $fb->add_group("","login-fields",$logininfo);*/
   



class fb
{	
	public $groups = array();
	//inputs are an array of groups of inputs
	//each input is an array containing:
		//label
		//name
	//optional values are
		//type -textfield
		//rules - rules for validating 
	
	public $par      = '';
	public $submit   = FALSE;
	public $action   = FALSE;
	public $autofill = FALSE;
	public $defaultMAX  = 32;
	public $defaultSIZE = 20;
    public $selects = array();
    public $title = "";
    public $tabCount = 1; //used for tab index
   
	
	public function _init($title = "",$action = FALSE,$validate = FALSE)
	{
		$this->set_action($action);
        $this->title=$title;
        $this->validate = $validate;//use if needs form validation like pass match or ==email
        if($this->validate)
        {
			$this->par->load("validator");
			$this->v = $this->par->validator;
			unset($this->par->validator);
			//$this->v->inputs = $this->inputs;
			//$this->v->selects = $this->selects;
			//$this->error = $this->v->_validate();
			//$this->message = $this->v->get_message();
        }
	}

    public function add_title($str)
    {
        $this->title=$str;
    }

    public function add_group($title,$class,$group)// adds a group of forms to the formbuilder
    {
        $filtered_inputs = $this->filter_inputs($group);
        array_push($this->groups,array('inputs'=> $filtered_inputs,'title' => $title, 'class' => $class));
    }

    public function filter_inputs($inputs) //takes a filter and add default values not customized
    {
        
        $filtered = array();//array to be returned

        //if the input is not an array, create one and add it to it so it can be processed
		if($inputs[0] === null)
		{
			$iin = $inputs;
			$inputs[0] = $iin;
        }	
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
				$input['rules'] = '';
			$input['tabindex'] = $this->tabCount;
			$this->tabCount++;
            array_push($filtered,$input);
            array_push($this->v->inputs,$input);
		}
		
        return $filtered;
    }

    public function random()// items that need to be implemented
    {
        if($this->autofill)
			$this->_autofill($sql,$value);
			
		
    }

	public function set_action($str=FALSE)
	{
		if(!$str)
            $this->action = 'action="" ';
        else
            $this->action = 'action="'.$str.'"';
	}//eof action
	
	public function display()
    {
        $this->v->selects = $this->selects;
        if(!isset($this->error))
            $this->error = $this->v->_validate();
        if(!isset($this->message))
            $this->message = '';
        $this->message .= $this->v->get_message();

        if($this->error)
        {
            //display error message if form validation has errors
            if($this->message != FALSE)echo '<div class="alert alert-error">'.$this->message.'</div>'."\n";
            
            $form = '<form class="well" '.$this->action.' method="post">'."\n";
            echo $form."\n <fieldset>";
            if($this->title != "")
                echo '<legend class="form-title"><strong>'.$this->title.'</strong></legend>'."\n";
            
            //so far we have 
            //<form action="if any then here" details(maybe id="form") method="post"> 
            //<fieldset>
            
            $this->display_inputs();
            echo '<button name="submit" type="submit" id="submit" class="btn btn-primary">submit</button>';   
            echo '</fieldset>';
            echo "\n".'</form>';
        }
        else
            return true;
	}//eof display
	
	public function display_inputs()
    {
        foreach($this->groups as $group)
        { 

            if($group['class'] != "")
            echo "\n<div class=\"".$group['class']." control-group\"> \n";
            
            echo '<fieldset>'."\n";
            
            if($group['title']!= "")
            echo '<legend class="form-group-title"><strong>'.$group['title'].'</strong></legend>'." \n";
            
            foreach($group['inputs'] as $input)
            {
                $this->{"create_".$input['type']}($input);
            }
           
            echo '</fieldset>'."\n";

           if($group['class'] != "") echo '</div>'."\n";
		}//eof inputs loop
	}//eof display_inputs
	
	public function get_inputs()
	{
        $params = array();
        foreach($this->groups as $group)
            foreach($group['inputs'] as $label)
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


    // must be called before display.
    public function _autofill($sql,$value)//sql(DB method) to search for and value to input
	{
		if(!$sql || !$value)
			return FALSE;
		$result = $this->par->DB->{$sql}($value);
		if(!$result)
			return FALSE;
		else
        {  
            foreach($this->groups as $group_key => $group)
                foreach($group['inputs'] as $key => $label)
                {
                    if( isset($result[$label['name']]) AND $result[$label['name']] != "" AND ($label['value'] == "") AND ($label['name'] != "pass"))
                    {
                            $label['value'] = $result[$label['name']];
                            $this->groups[$group_key]['inputs'][$key] = $label;
                    }
                }
		}
	}


    /***** CREATE INPUTS  ******/
	//these create the inputs for each type of input to be displayed
	public function create_text($input)
	{
		//<div class="form-field"><label for="label_name">Label_Name</label>
		$label = '<div class="form-field controls"><label class="control-label" for="'.$input['name'].'">'.$input['label'].'</label>'."\n";
		unset($input['label']);//same reason ^^ but the label	
		
		
		$inputh = '<input class="" ';
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
		
		$inputh .=  '/>'."\n";
		
		
		echo $inputh;//output the label
	}
	
	public function create_submit($input)
	{
		$inputh = '<button class="btn"';
		
		unset($input['rules']);//so the rule arent added to the html form
		unset($input['label']);//same reason ^^ but the label
			
		foreach($input as $param => $value)
		{
			$inputh .= $param.'="'.$value.'" ';
		}
		
		$inputh .=  '/>'."\n";
		
		echo $inputh;//output the label
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

