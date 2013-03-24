<div id="content">
<?php
		if(! $this->action)
		{
			echo "<ul><li><a href=\"".$this->BASEPATH."/clients/view\">View all clients</a><br ></li>
		<li><a href=\"".$this->BASEPATH."/clients/add\">Add a client</a><br ></li>
		<li><a href=\"".$this->BASEPATH."/clients/update\">update a client</a><br ></li>
		<li><a href=\"".$this->BASEPATH."/clients/remove\">remove a client</a><br ></li></ul>";
		} 
		else
		{
			if($this->locationID != FALSE)
				$this->client->recreate($this->locationID);
			$this->client->{$this->action}();
		} 
?>
</div>
