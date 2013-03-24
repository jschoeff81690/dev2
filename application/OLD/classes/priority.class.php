<?php
/**
 * Priorities are made to prioritize the day
 **/
class priority extends controller
{
    
   public function _init()
    {
        $this->create_model("priority");
    
    } 


    public function display($type)
    {
        $this->model->parse($this->model->get_priorities());
    }

    public function addRANDOM()
	{
		
		$this->par->load("form2","fb");
		$fb = $this->par->fb;
		$fb->_init(false," id=\"forms\"",false,true);//autofill=false, validate = true
		
		$params = array(
			array(
				'label' => 'Priority Name:',
				'name'  => 'name',
				'rules' => 'required'
			),
           array(
                'label' => 'repeat',
                'name'  => 'repeat'
            ),
            array(
                'label' => 'Length of time',
                'name'  => 'lot'
            ),
            array(
                'label' => 'Type of time',
				'type'  => 'select',
                'name'  => 'tot'
            ),
            array(
                'label' => 'Time of Day Limit(xx:xx)',
                'name'  => 'tod-limit'
            ),
            array(
                'label' => 'Priority Level',
                'name'  => 'priority-level',
                'rules' => 'required'
            )        
        );
		$fb->set_select("priority",array("1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7,"8"=>8,"9"=>9,"10"=>10));
        $fb->set_Select("tot",array("Minutes"=>"min","Hours"=>"hr"));
		$fb->set_inputs($params);
		if($fb->error == TRUE)
		{
			$fb->display();
		}
		else
		{
			$inputs = $fb->get_inputs();		
			foreach($inputs as $in=>$val)
				{
					if(strpos($in,"start")===false || strpos($in,"End")===false)
						{
							$ins[$in] = $val;
						}
						
				}

            $ins['time-limit'] = $inputs['lot'].$inputs['tot'];
            unset($ins['start_year'],$ins['start_month'],$ins['start_day'],$ins['End_year'],$ins['End_month'],$ins['End_day'],$ins['tot'],$ins['lot']);
                echo "<pre>";
                var_dump($ins);
                echo "</pre>";

			$this->model->create($ins);
			echo "Succesfully added a project.";
			//$this->view();
		} 	
	}

	public function addCONSTANT()
	{
		$this->par->load("form2","fb");
		$fb = $this->par->fb;
		$fb->_init(false," id=\"forms\"",false,true);//autofill=false, validate = true
		
		$params = array(
			array(
				'label' => 'Priority Name:',
				'name'  => 'name',
				'rules' => 'required'
			),
           array(
                'label' => 'repeat',
                'name'  => 'repeat'
            ),
            array(
                'label' => 'Length of time',
                'name'  => 'lot'
            ),
            array(
                'label' => 'Type of time',
				'type'  => 'select',
                'name'  => 'tot'
            ),
			array(
				'label' => 'Start Month',
				'type'  => 'select',
                'name'  => 'start_month'
            ),
			array(
				'label' => 'Start Day(e.g., 01)',
                'name'  => 'start_day',
            ),
			array(
				'label' => 'Start Year',
				'type'  => 'select',
                'name'  => 'start_year'
            ),
			array(
				'label' => 'End Month',
				'type'  => 'select',
                'name'  => 'End_month'
            ),
			array(
				'label' => 'End Day(e.g., 01)',
                'name'  => 'End_day'
            ),
			array(
				'label' => 'End Year',
				'type'  => 'select',
                'name'  => 'End_year'
            )
        );
		$fb->set_select("start_month",array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12));
		$year= date('Y');
		$fb->set_select("start_year",array(($year-1)=>$year-1,($year)=>$year,($year+1)=>$year+1,($year+2)=>$year+2,($year+3)=>$year+3,($year+4)=>$year+4));
		$fb->set_select("End_month",array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12));
		$year= date('Y');
        $fb->set_select("End_year",array(($year-1)=>$year-1,($year)=>$year,($year+1)=>$year+1,($year+2)=>$year+2,($year+3)=>$year+3,($year+4)=>$year+4));
        $fb->set_select("priority",array("1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7,"8"=>8,"9"=>9,"10"=>10));
        $fb->set_Select("tot",array("Minutes"=>"min","Hours"=>"hr"));
		$fb->set_inputs($params);
		if($fb->error == TRUE)
		{
			$fb->display();
		}
		else
		{
			$inputs = $fb->get_inputs();		
			foreach($inputs as $in=>$val)
				{
					if(strpos($in,"start")===false || strpos($in,"End")===false)
						{
							$ins[$in] = $val;
						}
						
				}
            $ins["start"] = $inputs['start_month']."-".$inputs['start_day']."-".$inputs['start_year'];
            $ins["end"] = $inputs['End_month']."-".$inputs['End_day']."-".$inputs['End_year'];
            if($ins['start']== "--") unset($ins['start']);
            if($ins['end']== "--") unset($ins['end']);

            $ins['time-limit'] = $inputs['lot'].$inputs['tot'];
            unset($ins['start_year'],$ins['start_month'],$ins['start_day'],$ins['End_year'],$ins['End_month'],$ins['End_day'],$ins['tot'],$ins['lot']);
                echo "<pre>";
                var_dump($ins);
                echo "</pre>";

			$this->model->create($ins);
			echo "Succesfully added a project.";
			//$this->view();
		} 	
	}

}
?>
