<?php
//aved- add view edit delete
#model functions#
//-get_id(attribute,value)
//-create(params)
//-read(id)
//-read_ALL()
//-update(params)
//-delete(id)
class project_model extends model
{
	public $db_table = 'projects';
	private $admin,$id;

	public function _init($admin,$id)
	{
		$this->admin = $admin;
		$this->id = $id;
	}	
	
	public function get_projects()
	{
		if(!$this->admin)
			$info = $this->read($this->id);
		else
			$info = $this->read_ALL();
		return $info;
	}
	
	public function parse($info)
	{
		echo '<pre>';
		var_dump($info);
		echo '</pre>';
		
	}
	
	
}
