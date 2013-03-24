<div id="content">
<?php
//new Task


	$this->load('form',"fb");
	$fb = $this->fb; 
	unset($this->fb);
	$params = array(
		array(
			'label' => 'Task:',
			'name'  => 'descript',
			'type'  => 'textarea',
			'maxlength' => '200',
			'size'  => '50',
			'rules' => 'required'
		),
		array(
			'label' => 'project ID:',
			'name'  => 'prid',
			'rules' => 'required'
		)
	);
	$fb->add_label($params);
	$fb->validate();
	if(count($_POST) < 1 || $this->fb->error == TRUE)
	{
		$fb->display_form();
	}
	else
	{
		
		$params = array(
		'descript'  => $_POST['descript'],
		'projectID' => $_POST['prid'],
		'date'		=> date("Y-m-d"),
		);
		$this->DB->insert_events($params);
		//$this->user->add($params);
		$this->action = false;
		$this->controller->tasks();
	}
?>
</div>
