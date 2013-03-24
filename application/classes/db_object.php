<?php
/**
 * DB_object
 * creates an object of a mysql query
 * fields are variables
 * functions:
 *    _init($table,$key,$value)//fills the object from db
 *       save()//requires init to be called first  
 *    delete($table,$key,$value)//deletes the object from db
 *    create() //requires table to be set
 *    _fill
 **/
class DB_object
{
    //$table = db table to use
    //$conditions =  array('key'=>key,'value' => value)
    function _init($table,$key,$value)
    {
        $this->table = $table;
        $this->conditions = array(
            'key'   => $key,
            'value' => $value
        );      
        $sql = "SELECT * FROM `".$table."` WHERE `".$key."` = '".$value."' LIMIT 1;";
       
        $result = $this->DB_Fetch1($sql);
        if(count($result) > 0 ){
            
            foreach($result as $key => $value)
                $this->{$key} = $value;
            unset($this->table);unset($this->conditions); 
            return $this;
        } 
        else
            return FALSE;
    }

    public function save()//requires _init to be called
    {
        $table  = $this->table;
        $key1   = $this->conditions['key'];
        $value1 = $this->conditions['value'];
        unset($this->table);
        unset($this->conditions);
        $updates = '';
       foreach($this  as $key => $value)
		{
			$updates .= "`$key` = '$value',";
		}
		$updates = substr($updates,0,-1);
        $sql = "UPDATE `$table` SET $updates WHERE `$key1` = $value1;";
		$this->DB_Q($sql);

    }

    public function create($array = FALSE)//requires table
    {
        if(!$array){
            $table = $this->table;
            unset($this->table);
            if(isset($this->conditions)) unset($this->conditions);

            $object =& $this;
        }
        else{
            $table = $array['table'];
            unset($array['table']);
            $object =& $array;
        }   

        foreach($object  as $key => $value)
        {
            $keys .= "`$key`, ";
            $values .= "'$value', ";
        }
    
        $keys = substr($keys,0,-2);
        $values = substr($values,0,-2);	
        $sql = "INSERT INTO $table (".$keys.") VALUES ($values);";
        $this->DB_Q($sql);
    }
    
    public function delete($table = NULL,$key = NULL,$value = NULL) // if theyre null, then they must be set already
    {
        if($table == NULL)
        {
            $table = $this->table;
            $key = $this->conditions['key'];
            $value = $this->conditions['value'];
        }
        $sql = "DELETE FROM $table WHERE `$key` = '$value';";
		$this->DB_Query($sql); 
    }

    public function _fill($array)//sets the oject via an array
    {
        foreach($array as $key => $value)
            $this->{$key} = $value;
        return $this;
    }
    
    //***** DB misc function *****//	
    public function DB_Q($sql)
	{
	    $result = mysql_query($sql) or die(mysql_error());
	    
	    return $result;
	}
	
	public function DB_Query_Connection($sql,$connection)/// sometimes needed
	{
	    $result = mysql_query($sql,$connection) or die(mysql_error());
	    
	    return $result;
	}

	public function DB_FetchAssoc($result)
	{
	    return mysql_fetch_assoc($result);
	}

	public function DB_NumRows($result)
	{
	    return mysql_num_rows($result);
	}

	
	public function DB_Fetch1($sql)
	{
		$result = $this->DB_Q($sql);
	    	if ($this->DB_NumRows($result) != 1)
	    	{
		    	return false;
	    	}
	    	else
	    	{
					
				return $this->DB_FetchAssoc($result);
			}//eof else
		return false;
	}//eof db fetch
	
	public function DB_FetchALL($sql) //returns array of all results or false
	{
		$arr = array();
		$count=0;
		$result = $this->DB_Q($sql);
	    while($row = $this->DB_FetchAssoc($result))
		{
			$arr[$count] = $row;
			$count++;
		}//eof while
		
		return count($arr)>0 ? $arr : false;
	}//eof db fetch   
   
}
?>
