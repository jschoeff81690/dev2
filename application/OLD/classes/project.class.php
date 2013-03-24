<?php
//hold project info in an orderly fashion

class project extends controller
{
	public $invoices = array();
	public $name     = '';
	public $id       = '';
	public $userID   = '';
	public $start    = '';
	public $end      = '';
	public $tasks    = array();
	public $domain   = '';
	public $host	 = '';
	
	public function _init()
	{
		$this->create_model("project");		
		$this->model->_init($this->par->user->admin,$this->par->user->id);
	}
	
	public function view()
	{
		if($this->model == NULL)
			$this->_init();
		
		
		$projects = $this->model->get_projects();
		
		if(count($projects) == 1)
		{
			$this->par->locationID = $projects[0]['id'];
			$this->par->action = "view";
		}
		else if(count($projects) > 1)
		{	
			?>
			<div id="content">
			<?php
			echo '<h4>View Projects:</h4> <ul>';
			foreach($projects as $project)
			{
				echo '<li><a href="'.$this->par->BASEPATH.'/projects/'.$project['id'].'/view" title="'.$project['name'].'">'.$project['name']."</a></li><br />\n";
			}
			echo '</ul>';
			?>
			</div>
			<?php
		}
		else
		{
			?>
			<div id="content">
			<div class="error">There was an error while processing your request. Please try again.</div>
			</div>
			<?php
		}
	}
	
	public function edit()
	{
	
	//include project id in the params array
	//mnot finished
		echo '<div id="content">';
		$this->par->load("form2","fb");
		$fb = $this->par->fb;
		$fb->_init(false," id=\"forms\"",false,true);//autofill=false, validate = true
		
		$params = array(
			array(
				'label' => 'Project Name:',
				'name'  => 'name',
				'rules' => 'required'
			),
			array(
				'label' => 'User ID',
				'name'  => 'userID',
				'rules' => 'required'
			),
			array(
				'label' => 'Start Month',
				'type'  => 'select',
				'name'  => 'start_month',
				'rules' => 'required'
			),
			array(
				'label' => 'Start Day(e.g., 01)',
				'name'  => 'start_day',
				'rules' => 'required'
			),
			array(
				'label' => 'Start Year',
				'type'  => 'select',
				'name'  => 'start_year',
				'rules' => 'required'
			),
			array(
				'label' => 'End Month',
				'type'  => 'select',
				'name'  => 'End_month',
				'rules' => 'required'
			),
			array(
				'label' => 'End Day(e.g., 01)',
				'name'  => 'End_day',
				'rules' => 'required'
			),
			array(
				'label' => 'End Year',
				'type'  => 'select',
				'name'  => 'End_year',
				'rules' => 'required'
			),
			array(
				'label' => 'Possible Domain:',
				'name'  => 'domain'
			),array(
				'label' => 'Possible Host:',
				'name'  => 'host'
			)
		);
		
		$fb->set_select("start_month",array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12));
		$year= date('Y');
		$fb->set_select("start_year",array(($year-1)=>$year-1,($year)=>$year,($year+1)=>$year+1,($year+2)=>$year+2,($year+3)=>$year+3,($year+4)=>$year+4));
		$fb->set_select("End_month",array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12));
		$year= date('Y');
		$fb->set_select("End_year",array(($year-1)=>$year-1,($year)=>$year,($year+1)=>$year+1,($year+2)=>$year+2,($year+3)=>$year+3,($year+4)=>$year+4));
		
		$fb->set_inputs($params);
		if($fb->error == TRUE)
		{
			$fb->display();
		}
		else
		{	
            $inputs = $fb->get_inputs();
            var_dump($inputs);
			$this->par->DB->update_projects($inputs);
			$this->view();
		}	
		echo '</div>';
	}
	
	public function add()
    {
        echo '<div id="content">';
        $this->par->load("form3","fb");
        $fb = $this->par->fb;
        $fb->_init("Add a New Project",false,TRUE);//title, action, validate

        $form = array(
            array(
            'label' => 'Project Name:',
            'name'  => 'name',
            'rules' => 'required'
        ),
        array(
            'label' => 'User ID',
            'name'  => 'userID',
            'rules' => 'required'
        ),
        array(
            'label' => 'Start Month',
            'type'  => 'select',
            'name'  => 'start_month',
            'rules' => 'required'
        ),
        array(
            'label' => 'Start Day(e.g., 01)',
            'name'  => 'start_day',
            'rules' => 'required'
        ),
        array(
            'label' => 'Start Year',
            'type'  => 'select',
            'name'  => 'start_year',
            'rules' => 'required'
        ),
        array(
            'label' => 'End Month',
            'type'  => 'select',
            'name'  => 'End_month',
            'rules' => 'required'
        ),
        array(
            'label' => 'End Day(e.g., 01)',
            'name'  => 'End_day',
            'rules' => 'required'
        ),
        array(
            'label' => 'End Year',
            'type'  => 'select',
            'name'  => 'End_year',
            'rules' => 'required'
        ),
        array(
            'label' => 'Possible Domain:',
            'name'  => 'domain'
        ),array(
            'label' => 'Possible Host:',
            'name'  => 'host'
        )
    );
        $fb->add_group("","add-pr-fields",$form);//add form fields to form class

        //set selects
        $fb->set_select("start_month",array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12));
        $year= date('Y');
        $fb->set_select("start_year",array(($year-1)=>$year-1,($year)=>$year,($year+1)=>$year+1,($year+2)=>$year+2,($year+3)=>$year+3,($year+4)=>$year+4));
        $fb->set_select("End_month",array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12));
        $year= date('Y');
        $fb->set_select("End_year",array(($year-1)=>$year-1,($year)=>$year,($year+1)=>$year+1,($year+2)=>$year+2,($year+3)=>$year+3,($year+4)=>$year+4));
        
        if($fb->display())
        {
            //we are good to go, means no error
             echo "Success.";   
            $inputs = $fb->get_inputs();
            echo '<pre>';
            var_dump($inputs);
            echo '</pre>';
            foreach($inputs as $in=>$val)
            {
                if(strpos($in,"start")===false || strpos($in,"End")===false)
                    {
                        $ins[$in] = $val;
                    }
                    
            }
            $ins["start"] = $inputs['start_year']."-".$inputs['start_month']."-".$inputs['start_day'];
            $ins["end"] = $inputs['End_year']."-".$inputs['End_month']."-".$inputs['End_day'];
            
            unset($ins['start_year'],$ins['start_month'],$ins['start_day'],$ins['End_year'],$ins['End_month'],$ins['End_day']);
            
            $this->model->create($ins);

            $this->recreate($this->par->locationID);
            $this->view();
        }



        //*************** OLD   *****************//

        
        echo '</div>';
	}
	
	public function activity()
	{
		echo "Recent activity for this Project: <br /><br />";
		
		foreach($this->tasks as $task )
		{
			
         echo '<div class="table_row';
			if($task['completed'])echo ' success';
			echo '">'; 
			echo '<p ><b>'.$task['descript'].'</b><br /> &nbsp;&nbsp;&nbsp;&nbsp;<a href="/dev2/projects/'.$project['id'].'/view/" title="view project">'.$project['name'].'</a> | <a href="/dev2/tasks/'.$task['ID'].'/delete/" title="Delete task">Delete this Task</a> | <a href="/dev2/tasks/'.$task['ID'].'/completed/" title="Completed task?">Complete this Task</a></p>';
			echo '</div>';
        } 
	}//eof view
	
	public function display_info()
	{
		
		echo "project invoices: <br />";
		foreach($this->invoices as $invoice)
		{
			$invoice->display_link();
		}
	}
	public function user()
	{
		$clients = $this->par->DB->find_users_by_admin(0);
			foreach($clients as $client)
			{
				$id = $client['id'];
				$name = $client['first'].",".$client['last'];
				echo "<ul><li><a href=\"/dev/clients/$id/view\">View $name</a><br ></li></ul>";
			}
	}
	
	public function remove()
	{
		if(!$this->par->locationID)
		{
			$projects = $this->par->DB->find_projects_ALL();
			if($projects != FALSE)
			{
				foreach($projects as $project)
				{
					$id = $project['id'];
					$name = $project['name'];
					$userid = $project['userid'];
					echo "<ul><li><a href=\"/dev/projects/$id/remove\">remove $name</a><br ></li></ul>";
					
				}
			}
		}
		else
		{
			$this->par->DB->delete_projects_by_id($this->par->locationID);
		}
	}
	
	public function set_invoices($id)
	{
		$ins = $this->par->DB->find_invoices_by_projectid($id);
		if(!$ins)
			return FALSE;
		foreach($ins as $inv)
		{
			$this->par->load("invoice");
			$this->par->invoice->recreate($inv['id']);
			//$new = new invoice_class($this->par,$inv['id']);
			array_push($this->invoices, $this->par->invoice);
			
		}
		
	}
	
	public function set_tasks($id)
	{
		$tasks = $this->par->DB->find_events_by_projectID($id);
		if(!$tasks)
			return FALSE;
		foreach($tasks as $task)
		{
			$task['descript'] = strtoupper(substr($task['descript'],0,1))."".substr($task['descript'],1);
			$task['date'] = $this->par->get_date($task['date']);
			$new = $task;
			array_push($this->tasks, $new);
			
		}
	}
	
	public function recreate($id)
	{
		$data =  $this->par->DB->find_projects_by_id_limit($id);
		$this->name   = $data['name'];
		$this->id     = $id;
		$this->userID = $data['userID'];
		$this->start  = $data['start'];	
		$this->end    = $data['end'];
		if($data['domain'] != FALSE)
		$this->domain = $data['domain'];
		if($data['host'] != FALSE)
		$this->host = $data['host'];
		$this->set_tasks($id);
		$this->set_invoices($id);
		
	}
	
	public function get_meta()
	{
		$new = array();
		$project_meta = $this->par->DB->find_projectMeta_by_projectID($this->id);
		for($x=0;$x<count($project_meta);$x++)
		{
			$new[$project_meta[$x]['key']] = $project_meta[$x]['value'];
		}
		return $new;
	}
	
}
