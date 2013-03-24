<?php

if(!$this->locationID)
{
	if(!$this->action)	
	{
		?>
		<div id="content">
		<?php
		echo 'Projects: <br/>'."\n";
		echo '<a href="'.$this->BASEPATH.'/projects/view">View Projects</a><br/>'."\n";
		echo '<a href="'.$this->BASEPATH.'/projects/add">Add a Project</a><br/>'."\n";
		echo '<a href="'.$this->BASEPATH.'/projects/edit">Edit a Project</a><br/>'."\n";
		echo '<a href="'.$this->BASEPATH.'/projects/remove">Remove a Project</a><br/>'."\n";
		?>
		</div>
		<?php
	}
	else
	{

		$this->project->{$this->action}();
	}
}
if($this->locationID != FALSE)
{
	$this->load("project");
	$this->project->recreate($this->locationID);
	//$this->project = new project_class($this,$this->locationID);
	?>
	<div id="col1">
		<div id="col1-content">
			<?php echo "<h3>".$this->proper_case($this->project->name)."</h3>";?>
			Project info
				<ul style="list-style-type:none">
					<?php
						echo '<li><b>Project ID</b>: '.$this->project->id;
						echo '<li><b>Web address</b>: <a href="http://'.$this->project->domain.'" title="'.$this->project->domain.'">'.$this->project->domain.'</a></li>';
						echo '<li><b>Web host</b>: <a href="http://'.$this->project->host.'.com">'.$this->project->host.'</a></li>';
						echo '<li><b>Start date</b>: '.$this->get_date($this->project->start,1,1,1).'</li>';
						echo '<li><b>Estimated end</b>: '.$this->get_date($this->project->end,1,1,1).'</li>';
						
					?>
				</ul>
			<div class="line"></div>
			<?php
				echo "<br />Project Invoices: <br /><ul style=\"list-style-type:none\">";
				foreach($this->project->invoices as $invoice)
				{
					
					echo '<li>';
					$invoice->display_link();
					echo '</li>';
				}
			?>
			</ul>
			<div class="line"></div>
			<?php
				echo "<br />MYSQL info: <br /><ul style=\"list-style-type:none\">";
				$meta = $this->project->get_meta();
						echo '<li><b>Database Name</b>: '.$meta['Database Name'];
						echo '<li><b>Database User</b>: '.$meta['Database User'];
						echo '<li><b>Database Password</b>: '.$meta['Database Password'];
			?>
			</ul>
			<div class="line"></div>
			<?php
				echo "<br />FTP info: <br /><ul style=\"list-style-type:none\">";
				$meta = $this->project->get_meta();
						echo '<li><b>FTP Server</b>: '.$meta['FTP Server'];
						echo '<li><b>FTP User</b>: '.$meta['FTP Username'];
						echo '<li><b>FTP Password</b>: '.$meta['FTP Password'];
			?>
			</ul>
		</div>	
	</div>

	<div id="col2">
		<div id="col2-content">
			<?php $this->project->activity(); ?>
		</div>
	</div>
	<?
}
