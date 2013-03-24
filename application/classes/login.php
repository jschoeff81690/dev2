<?php
class login
{	
    public $user   = '';
	public $pass   = '';
	public $par    = '';//parent
	public $ref    = '';//redirect reference
	public $LOGGED = FALSE;



	//requires $this->user and pass to be set already!
	public function usr_login()
	{
		if(! $this->compare())
			return FALSE;
		$this->set_logged();	
		return TRUE;
		
	}
	public function set_logged()
	{
	
		$_SESSION['user'] = $this->user;
		$db = $this->par->DB->find_users_by_email_limit($this->user);
		$hashed = $this->_hash(array($this->user,$db->id));
		$_SESSION['uki'] = $hashed;
	}

	public function check_post()
	{
		
		if( !isset($_POST['user']) || !isset($_POST['pw']))
            return FALSE;
		$this->user = $_POST['user'];
		$this->pass = $_POST['pw'];
		return $this->usr_login();
	}
	
	public function check_logged()
	{
		if(!isset($_SESSION['user']) || !isset($_SESSION['uki']))
			return FALSE;
		$this->user = $_SESSION['user'];
		$user_key_info = $_SESSION['uki'];
		$db = $this->par->DB->find_users_by_email_limit($this->user);
		if(!$db)
			return FALSE;
		$hashed = $this->_hash(array($this->user,$db->id));
		if($hashed != $user_key_info)
			return FALSE;
		
		return TRUE;
	}
	
	public function compare()//requires this->user
	{
		$db = $this->par->DB->find_users_by_email_limit($this->user);
		if(!$db) 
		{
			$this->par->fb->error=TRUE;
			$this->par->fb->message = 'An incorrect Username or Password was entered.';
			return FALSE;
		}
		$hashit = $this->_hash(array($this->user,$this->pass));
		if($db->pass != $hashit)
		{
			$this->par->fb->error=TRUE;
			$this->par->fb->message = 'An incorrect Username or Password was entered.';
			return FALSE;
		}
		return TRUE;
	}
	
	public function set_forms()
    {
        $fb =& $this->par->fb;
        $fb->_init('Login','auth',TRUE);
        $params = array(
            array(
                'label' => 'Email Address:',
                'name'  => 'user',
                'rules' => 'email'
            ),
            array(
                'label' => 'Password:',
                'type'  => 'password',
                'name'  => 'pw',
                'rules' => 'pass'
            )
        );

        $fb->add_group('','login-fields',$params); 

    }
	
	public function _hash($arr)
	{
		$len = strlen($arr[0]);
		return md5($len."".$arr[1]);
	}
	
	public function setRef($url)
	{
		if($url != NULL)
		$_SESSION['ref'] = $url;
		
	}
	
	public function logout()
	{
		session_destroy();
		header("Location: ".$this->par->BASEPATH);
		$this->par->action = FALSE;
		$this->par->location = FALSE;
		$this->par->locationID=FALSE;
		$this->LOGGED = FALSE;
		
	}
}
