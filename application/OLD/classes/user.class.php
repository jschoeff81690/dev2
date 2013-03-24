<?php 
class user
{
	public $par     = '';
	public $id      = '';
	public $email   = '';
	public $first   = '';
	public $last    = '';
	public $address = '';
	public $city    = '';
	public $state   = '';
	public $zip     = '';
	public $admin   = '';
	public $phone   = '';

	
	public function _init($user = NULL)
	{
		if($user != NULL)
		{
			$this->set($user);
		}
		
	}

	public function set($email)
	{
		$data = $this->par->DB->find_users_by_email_limit($email);
		if(!$data)
			return FALSE;
		$this->id      = $data['id'];
		$this->email   = $data['email'];
		$this->first   = $data['first'];
		$this->last    = $data['last'];
		$this->address = $data['addr'];
		$this->city    = $data['city'];
		$this->state   = $data['state'];
		$this->zip     = $data['zip'];	
		$this->phone   = $data['phone'];
		$this->admin   = $data['admin'];
	}
	
	public function recreate($id)
	{
		$data = $this->par->DB->find_users_by_id_limit($id);
		if(!$data)
			return FALSE;
		$this->id      = $data['id'];
		$this->email   = $data['email'];
		$this->first   = $data['first'];
		$this->last    = $data['last'];
		$this->address = $data['addr'];
		$this->city    = $data['city'];
		$this->state   = $data['state'];
		$this->zip     = $data['zip'];	
		$this->phone   = $data['phone'];	
		$this->admin   = $data['admin'];
	}
	
	public function view()
	{
		if(! $this->par->locationID)
		{
			$clients = $this->par->DB->find_users_by_admin(0);
			foreach($clients as $client)
			{
				$id = $client['id'];
				$name = $client['first']." ".$client['last'];
				echo "<ul><li><a href=\"".$this->par->BASEPATH."/clients/$id/view/\">View $name</a><br ></li></ul>";
			}
		}
		else
        {   
            $this->par->client = $this;
			$client = $this->par->client;
			$projects = $this->par->DB->find_projects_by_userid($this->par->locationID);
			//$invoices = $this->par->DB->find_invoices_by_userid($this->par->actions[1]);
			echo "Client : ".$client->first." ".$client->last."<br />";
				
			echo "ID: ".$client->id."<br />";
			echo "Email: ".$client->email."<br />";
			echo "Address: ".$client->address."<br />";
			echo "City: ".$client->city."<br />";
			echo "State: ".$client->state."<br />";
			echo "Zip: ".$client->zip ."<br />";
			echo "Phone: ".$client->phone ."<br />";
			echo "Administrator: ".$client->admin."<br />";
			/*foreach($projects as $project)
			{
				echo "<a href=\"/dev/projects/".$project["id"]."/view/\">Project: ".$project['name']."</a>";
			}*/
			
			
		}
	}//eof view
	
	public function add()
	{
		$this->par->load('form',"fb");
		$fb = $this->par->fb; 
		unset($this->par->fb);
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
			),array(
				'label' => 'Last name',
				'name'  => 'last',
				'rules' => 'required'
			)
		);
		$fb->add_label($params);
		$fb->validate();
		if(count($_POST) < 1 || $this->par->fb->error == TRUE)
		{
			$fb->display_form();
		}
		else
		{
			$pass  = $this->par->login->_hash(array($_POST['email'],$_POST['pass']));
			$params = array(
			'first' => $_POST['first'],
			'last'  => $_POST['last'],
			'email' => $_POST['email'],
			'pass'  => $pass
			);
			$this->par->DB->insert_users($params);
			//$this->par->user->add($params);
			$this->view();
		}
	}
	
	public function update()
    {
        if(! $this->par->locationID)
		{
			$clients = $this->par->DB->find_users_by_admin(0);
			foreach($clients as $client)
			{
				$id = $client['id'];
				$name = $client['first']." ".$client['last'];
				echo "<ul><li><a href=\"".$this->par->BASEPATH."/clients/$id/update/\">Update $name</a><br ></li></ul>";
			}
		}
		else
		{
            $this->par->load("form3","fb");
            $fb = $this->par->fb;
            $fb->_init("Account Settings",false,TRUE);//title, action, validate

            $logininfo = array(
                array(
                    'label' => 'Email Address',
                    'name'  => 'email',
                    'rules' => 'email'
                ),
                array(
                    'label' => 'New Password',
                    'type'  => 'password',
                    'name'  => 'pass',
                    'value' => '',
                    'rules' => 'pass'
                ),
                array(
                    'label' => 'Confirm Password',
                    'type'  => 'password',
                    'name'  => 'passconf',
                    'value' => '',
                    'rules' => 'pass match=pass'
                ));
            $fb->add_group("Login Information","login-fields",$logininfo);
            $name = array(
                 array(
                    'label' => 'First',
                    'name'  => 'first',
                    'rules' => ''
                ),array(
                    'label' => 'Last',
                    'name'  => 'last',
                    'rules' => ''
                )   
            );
            $fb->add_group("Name","name-fields",$name);
            $address = array(
                array(
                    'label' => 'Address',
                    'name'  => 'addr',
                    'rules' => ''
                ),array(
                    'label' => 'City',
                    'name'  => 'city',
                    'rules' => ''
                ),array(
                    'label' => 'state',
                    'name'  => 'state',
                    'type'  => 'select',
                    'rules' => ''
                ),array(
                    'label' => 'Zip',
                    'name'  => 'zip',
                    'rules' => ''
                ),array(
                    'label' => 'Phone',
                    'name'  => 'phone',
                    'rules' => ''
                )
            );
            $fb->add_group("Contact Information","contact-fields",$address);

            $fb->set_select("state",$fb->get_states_array());

            $fb->_autofill("find_users_by_id_limit",$this->par->locationID);
            
            if($fb->display())
            {
                //we are good to go, means no error
                 echo "Success.";   
                $inputs = $fb->get_inputs();
                echo '<pre>';
                var_dump($inputs);
                echo '</pre>';
                if(isset($inputs['pass']))
                {   
                    $inputs['pass'] = $this->par->login->_hash(array($inputs['email'],$inputs['pass']));
                }
                $this->par->DB->update_users_by_id(array($inputs,$this->par->locationID));
                $this->recreate($this->par->locationID);
                $this->view();
            }
        }
		
	}
	
	public function remove()
	{
		if(!$this->par->locationID)
		{
			$clients = $this->par->DB->find_users_by_admin(0);
			foreach($clients as $client)
			{
				$id = $client['id'];
				$name = $client['first']." ".$client['last'];
				echo "<ul><li><a href=\"/dev/clients/$id/remove/\">Remove $name from clients</a><br ></li></ul>";
			}
		}
		else
		{
			$this->par->DB->delete_users_by_id($this->par->locationID);
		}
	}
	
	public function name()
	{
		return $this->first." ".$this->last;
	}
	
}
