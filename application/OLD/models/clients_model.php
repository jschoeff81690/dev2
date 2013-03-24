<?php
class clients
{
	public $data = array();
	public function __construct(&$master)
	{
		$this->par = $master;
		if(isset($this->par->actions[1]) && $this->par->actions[0] == "clients")
		{
			$this->data = $this->par->DB->find_users_by_id_limit($this->par->actions[1]);
		}
		else if(isset($this->par->actions[3]) && $this->par->actions[2] == "clients")
		{
			$this->data = $this->par->DB->find_users_by_id_limit($this->par->actions[3]);
		}
	}
	
	public function view()
	{
		if(! isset($this->par->actions[1]))
		{
			$clients = $this->par->DB->find_users_by_admin(0);
			foreach($clients as $client)
			{
				$id = $client['id'];
				$name = $client['first'].",".$client['last'];
				echo "<ul><li><a href=\"/dev/clients/view/$id\">View $name</a><br ></li></ul>";
			}
		}
		else
		{
			$client = $this->par->client;
			$projects = $this->par->DB->find_projects_by_userid($this->par->actions[1]);
			//$invoices = $this->par->DB->find_invoices_by_userid($this->par->actions[1]);
			echo "Client : ".$client->first." ".$client->last."<br />";
			foreach($projects as $project)
			{
				echo "<a href=\"/dev/projects/view/".$project["id"]."\">Project: ".$project['name']."</a>";
			}
			
			
		}
	}//eof view

	public function add()
	{
		if( isset($_POST['email']) && isset($_POST['pass']) )
		{
			$first = $_POST['first'];
			$last  = $_POST['last'];
			$email = $_POST['email'];
			if(!$_POST['pass'] == $_POST['passconf'])
			{
				$error =TRUE;
				$errorMessage = "passwords dont match";
				
			}
			if($this->par->DB->find_users_by_email($email) != FALSE)
			{
				$error =TRUE;
				$errorMessage = "email already in use";
				
			}
		}
		else
		{
			$error = TRUE;
			$errorMessage = "";
		}
		
		if($error)
		{
			?>	
			<h2><?php echo $errorMessage ?></h2>
			<form action="" method="post">
										
				<label for="email">Email address:
				<input type="text" name="email" id="email" value=""size="20" maxlength="32" /></label>

				<label for="pass">Password:
				<input type="password" name="pass" id="pass" value=""size="20" maxlength="32" /></label>

				<label for="passconf">Password Confirm:
				<input type="password" name="passconf" id="passconf" value=""size="20" maxlength="32" /></label>
				
				<label for="first">frist name:
				<input type="text" name="first" id="first" value=""size="20" maxlength="32" /></label>
				
				<label for="last">last name:
				<input type="text" name="last" id="lasst" value=""size="20" maxlength="32" /></label>


				<div><input type="submit" value="Submit" /></div>
			</form>
			<?php	
			
		}
		else
		{
			
			$pass  = $this->par->user->_hash(strlen($email),$_POST['pass']);
			$params = array(
			'first' => $first,
			'last'  => $last,
			'email' => $email,
			'pass'  => $pass
			);
			$this->par->user->add($params);
			$this->viewclient();
		}
	}//eof addclients
	
	public function update()
	{
		if(isset($_POST['email']) && isset($_POST['pass']) )
		{
			$first = $_POST['first'];
			$last  = $_POST['last'];
			$email = $_POST['email'];
			$pass  = $this->par->user->_hash(strlen($email),$_POST['pass']);
			
			$params = array(
			'first' => $first,
			'last'  => $last,
			'email' => $email,
			'pass'  => $pass
			);
			$this->par->user->update($params);
		}
		else
		{
			?>	
			<form action="add/" method="post">
										
				<label for="email">Email address:
				<input type="text" name="email" id="email" value=""size="20" maxlength="32" /></label>

				<label for="pass">Password:
				<input type="pass" name="pass" id="pass" value=""size="20" maxlength="32" /></label>

				<label for="passconf">Password Confirm:
				<input type="password" name="passconf" id="passconf" value=""size="20" maxlength="32" /></label>
				
				<label for="first">frist name:
				<input type="text" name="first" id="first" value=""size="20" maxlength="32" /></label>
				
				<label for="last">last name:
				<input type="text" name="last" id="lasst" value=""size="20" maxlength="32" /></label>
				
				<div><input type="submit" value="Submit" /></div>
			</form>
			<?php	
			
		}
	}

}
