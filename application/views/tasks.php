<div id="content">
<?php
//Tasks

	if(!$this->action)
	{
		?>
		<a href="/dev2/tasks/new/" title="add a task"> + Task</a> 
		<?php
		$tasks = $this->DB->find_events_ALL();
		echo '<div class="table">
			<div class=theader">Tasks</div>';
		
		$row = TRUE;
		$c = 0;
		foreach($tasks as $task)
		{
			$project = $this->DB->find_projects_by_id_limit($task['projectID']);
			
			echo '<div class="table_row';
			if($task['completed'])echo ' success';
			echo '">'; 
			echo '<p ><b>'.$task['descript'].'</b><br /> &nbsp;&nbsp;&nbsp;&nbsp;<a href="/dev2/projects/'.$project['id'].'/view/" title="view project">'.$project['name'].'</a> | <a href="/dev2/tasks/'.$task['ID'].'/delete/" title="Delete task">Delete this Task</a> | <a href="/dev2/tasks/'.$task['ID'].'/completed/" title="Completed task?">Complete this Task</a></p>';
			echo '</div>';
		}
	}
	else // add a task
	{
		if($this->action == "new")
			$this->view("newtask");
	}
	
	// action = delete taks
	if($this->action == "delete")
	{
		$this->DB->delete_events_by_id($this->locationID);
		$this->action = FALSE; 
	}
	
	//complete task
	if($this->action == "delete")
	{
		$this->DB->delete_events_by_id($this->locationID);
		$this->action = FALSE; 
	}
?>
</div>
