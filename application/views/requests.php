<div id="content">
<?php 
	$this->helper('includes/phpf/classes/form.class.php');
	$fb = new form_class($this); 
	$params = array(
		array(
			'label' => 'Type of Request:',
			'type'  => 'text',
			'name'  => 'type',
			'rules' => ''
		),
		array(
			'label' => 'Description:',
			'type'  => 'textarea',
			'name'  => 'descript',
			'rules' => ''
		),
		array(
			'label' => '',
			'type'  => 'hidden',
			'name'  => 'userid',
			'value'  => $this->user->id,
			'rules' => ''
		)
		
	);
	$fb->add_label($params);
	$fb->validate();
	if($fb->error)
	{
		$fb->display_form();
	}
	else
	{
		$params = $fb->get_params();
		
		$this->DB->insert_requests($params);
		$this->view("projects");
	}
?>
</div>