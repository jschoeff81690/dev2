<?php

class scripture
{

public function _init($function_name)
{
	if($function_name != FALSE && method_exists($this,$function_name))
	{
		$this->{$function_name}();
	}
	else
	{
		echo '<a href="'.$this->par->BASEPATH.'/memory_scripture/view">View a Scripture</a><br />
		<a href="'.$this->par->BASEPATH.'/memory_scripture/add">Add a Scripture</a><br />
		<a href="'.$this->par->BASEPATH.'/memory_scripture/edit">Update</a><br />
		<a href="'.$this->par->BASEPATH.'/memory_scripture/delete">Delete</a><br />
		 ';
	}
}
	

public function add()
{
	# code...
	echo 'add';
}

public function view()
{
	echo 'view';
	# code...
}

public function edit()
{
	echo 'update';
	# code...
}

public function delete()
{
	echo 'del';
	# code...
}


}
