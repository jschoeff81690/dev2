<div id="content-wrap">	
<?php 
	if($this->safe_location())
		$this->handler->{$this->location}();
	else
		$this->handler->index();

?>	
</div>
	<div class="spacer"></div>
