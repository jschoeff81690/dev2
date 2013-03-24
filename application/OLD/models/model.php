<?php

abstract class model
{	
	public function get_id($attr,$value)
	{
		$result = $this->par->DB->{"find_".$this->db_table."_by_".$attr."_limit"}($value);
		if(!$result)
			return FALSE;
		else
			return $result['id'];
	}

	//by db id
	public function create($params)
	{
		return $this->par->DB->{"insert_".$this->db_table}($params);
	}
	
	public function read($id)
	{
		return $this->par->DB->{"find_".$this->db_table."_by_id_limit"}($id);
	}
	
	public function read_ALL()
	{
		return $this->par->DB->{"find_".$this->db_table."_ALL"}();	
	}
	
	public function update($params)
	{
		return $this->par->DB->{"update_".$this->db_table}($params);
	}
	
	public function delete($id)
	{
		return $this->par->DB->{"delete_".$this->db_table."_by_id"}($id);	
	}
}
