<?php
class handler
{
	public $location='';
    public $par;
    
    public function __construct()
    {
        
       $this->par = master::get_instance();
       // $par = func_get_args();
        //$this->par =  $par[0];
        $this->par->template->_css('bootstrap');
        $this->par->template->_css('bootstrap-responsive');
        $this->par->template->title($this->par->TITLE);
        $this->par->template->_js('jquery');
        $this->par->template->_js('bootstrap-collapse');
        $this->par->template->_js('bootstrap-dropdown');

    }

    public function index()
    {
        if(!$this->par->login->check_logged())
            redirect('/auth');
        $user = new db_object();
        $user->_init('users','email',$this->par->login->user);
        echo '<pre>';
        var_dump($user);
        echo '</pre>';
        if(method_exists($this,$this->par->location))
           $this->{$this->par->location}();
           
//         //$this->par->load("DB_object",'obj');
//         $this->par->helper('application/classes/db_object.class.php');
//         // require_once('includes/phpf/classes/db_object.class.php');
//         $user = new DB_object();
//         
//         $user->_init("users","id","1");
//         $user->city = 'key west';
//         $user->save();
//         echo '<pre>';
//         var_dump($user);
//         echo '</pre>';
// 
//         $bob = new DB_object();
//         $bob->table = 'users';
//         $bob->email = "justin@gmail.com";
//         $bob->admin = 1;
//         $bob->create();
    }
	
	public function tasks()
	{
		$this->par->view('tasks');
	}
    public function priorities()
    {
        $this->par->view('priorities');
    }
	public function calendar()
	{
		$this->par->load('form2',"fb");
		$fb = $this->par->fb;
		$fb->_init(false," id=\"forms\"",false,true);//autofill=false, validate = true
		$params = array(
			array(
				'label' => 'Title (50 characters or less)',
				'name'  => 'title',
				'rules' => 'required'
			),
			array(
				'label' => 'Description',
				'type'  => 'textarea',
				'name'  => 'desc',
				'rules' => 'required'
			),
			array(
				'label' => 'Month',
				'type'  => 'select',
				'name'  => 'month',
				'rules' => 'required'
			),
			array(
				'label' => 'Day(e.g., 01)',
				'name'  => 'day',
				'rules' => 'required'
			),
			array(
				'label' => 'Year',
				'type'  => 'select',
				'name'  => 'year',
				'rules' => 'required'
			),
		);
		
		$fb->set_select("month",array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12));
		$year= date('Y');
		$fb->set_select("year",array(($year)=>$year,($year+1)=>$year+1,($year+2)=>$year+2,($year+3)=>$year+3,($year+4)=>$year+4));
		$fb->set_inputs($params);
		if($fb->error == TRUE)
		{
			$fb->display();
		}
		else
		{
			$inputs = $fb->get_inputs();
			$this->par->DB->insert_cevent($inputs);
		}
	
		//begin cal display
		$this->par->load("calendar2","cal");
		$this->par->cal->_init();
		$this->par->cal->display();
		//$this->par->view("calendar");
	}
	
	public function uploads()
	{
		if(!isset($_FILES['uploadedfile']['name']))
		{
			?><br /><form enctype="multipart/form-data" action="" method="POST">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
			Choose a file to upload: <input name="uploadedfile" type="file" /><br />
			<input type="submit" value="Upload File" />
			</form><?
		}
		else
		{
			echo '<pre>';
			var_dump($_FILES);

			echo '</pre>';
			$target = 'includes/images/'. $this->par->file_rename(basename( $_FILES['uploadedfile']['name'])); 
			if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target))
			{
				echo "The file ".  $this->par->file_rename(basename( $_FILES['uploadedfile']['name'])). " has been uploaded";
			} 
			else
			{
				echo "There was an error uploading the file, please try again!";
			}

		}
	}
	
	public function clients()
	{
		$this->par->view('clients');
	}

	
	public function projects()
	{
		
		$this->par->view('project');
	}
	public function invoices()
	{
		if(!$this->par->action)
		{
			echo "<ul><li><a href=\"".$this->par->BASEPATH."/invoices/view\">View all invoices</a><br ></li>
		<li><a href=\"".$this->par->BASEPATH."/invoices/user\">view by user</a><br ></li>
		<li><a href=\"".$this->par->BASEPATH."/invoices/create\">create a invoice</a><br ></li>
		<li><a href=\"".$this->par->BASEPATH."/invoices/update\">update a invoice</a><br ></li>
		<li><a href=\"".$this->par->BASEPATH."/invoices/remove\">delete a invoice</a><br ></li></ul>";
		}
		else
		{	if($this->par->locationID != FALSE)
				$this->par->invoice->recreate($this->par->locationID);
			
			$this->par->invoice->{$this->par->action}();
		}
	}
	
	public function resources()
	{
		$this->par->load(array("project","invoice"));
		$this->par->project->_init();
		$this->par->load("user","client");//user class named client
	}
	
	public function account_settings()
	{
		
        echo '<div id="content">';
		$this->par->locationID = $this->par->user->id;
        $this->par->user->update();
        echo '</div>';
    }
	
	public function form2()
	{
		$this->par->load('form2',"fb");
		$fb = $this->par->fb;
		$fb->_init(false," id=\"forms\"",false,true);//autofill=false, validate = true
		$params = array(
			array(
				'label' => 'Email Address',
				'name'  => 'email',
				'rules' => 'required email'
			),
			array(
				'label' => 'Password',
				'type'  => 'password',
				'name'  => 'pass',
				'rules' => 'required pass'
			),
			array(
				'label' => 'Confirm Password',
				'type'  => 'password',
				'name'  => 'passconf',
				'rules' => 'required pass match=pass'
			),
			array(
				'label' => 'First Name',
				'name'  => 'first',
				'rules' => 'required'
			),
			array(
				'label' => 'Last name',
				'name'  => 'last',
				'rules' => 'required'
			),
			array(
				'label' => 'State',
				'name'  => 'state',
				'type'  => 'select',
				'rules' => 'required'
			),
			array(
				'label' => 'Administrator',
				'name'  => 'admin',
				'type'  => 'select',
				'rules' => 'required'
			)
		);
		
		$fb->set_select("admin",array("Yes"=>"1","No"=>"0"));
		$fb->set_select("state",$fb->get_states_array());
		$fb->set_inputs($params);
		if($fb->error == TRUE)
		{
			$fb->display();
		}
		else
		{
			$inputs = $fb->get_inputs();
			var_dump($inputs);
			
			/*$pass  = $this->par->login->_hash(array($_POST['email'],$_POST['pass']));
			$params = array(
			'first' => $_POST['first'],
			'last'  => $_POST['last'],
			'email' => $_POST['email'],
			'pass'  => $pass
			);
			$this->par->DB->insert_users($params);
			//$this->par->user->add($params);
			$this->view();*/
			
		}
	}
	
	public function database_functions()
	{
		if($this->par->action != FALSE)
		{
			switch($this->par->action)
			{
			case "add Database":
			
			break;
			
			case "add_table":
				if(!isset($_POST['table']))//then create a form set up to build a table
				{
					$this->par->load('form2',"fb");
					$fb = $this->par->fb;
					$fb->_init(false," id=\"forms\"",false,true);//autofill=false, validate = true
					$params = array(
						array(
							'label' => 'Table Name',
							'name'  => 'table',
							'rules' => 'required'
						),
						array(
							'label' => 'Number of Rows',
							'type'  => 'select',
							'name'  => 'rows',
							'rules' => 'required'
						)		
					);
				
					$fb->set_select("rows",array("1","2","3","4","5","6","7","8","9",));
					$fb->set_inputs($params);
					if($fb->error == TRUE)
					{
						$fb->display();
					}
				}
				else
				{
				
					echo 'unfinished..';
					$this->par->load('form2',"fb");
					$fb = $this->par->fb;
					$fb->_init(false," id=\"forms\"",false,true);//autofill=false, validate = true
					$params = array(
						array(
							'label' => 'Table Name',
							'name'  => 'table',
							'type'  => 'hidden',
							'value' => $_POST['table'],
							'rules' => 'required'
						),
						array(
							'label' => 'Number of Rows',
							'type'  => 'select',
							'name'  => 'rows',
							'type'  => 'hidden',
							'value' => $_POST['rows'],
							'rules' => 'required'
						)		
					);
				
					$fb->set_select("rows",array("1","2","3","4","5","6","7","8","9",));
					$fb->set_inputs($params);
					if($fb->error == TRUE)
					{
						$fb->display();
					}
					else
					{
						var_dump($_POST);
					
		
					}
				}
				
			break;
			
			case "add row":
			//not in effect
			break;
			
			}
		}//if action isnt false
		else
		{
			
			echo '<a href="'.$this->BASEPATH.'/database_functions/add_table">Add Table</a>';
	
		
		}	
	
    } 
    


	public function memory_scripture()
	{
		$this->par->load("scripture");
		$this->par->scripture->_init($this->par->action);
    }

    private function _logged()
    {
        if(!isset($this->user))
            return false;
        else return TRUE;
    }
}

?>
