<?php
/** Contains DB.class and DB_object.class
 * DB is used for searching, DB_object returns a _object.


/*
functions
find
	-options: where,limit,and,or
insert
update
delete
 */
require_once 'db_object.php';
class DB extends db_functions
{
	public $name = array();	
    public $params = array();
    public $key;
    public $par = "";
    public $con;
	public function __call($name, $params) 
	{
        // Note: value of $name is case sensitive.
       // echo "Calling object method '$name'"
        $this->name = explode("_",strtolower($name));
            if(is_array($params[0]))
            {
                if(isset($params[0][1]) &&  isset($params[0][0]) )
                {
                    $this->params = $params[0][0];
                    $this->key = $params[0][1];
                } 
                else
                   $this->params = $params[0];
            } 
            else
                $this->params = $params[0];
            
            switch($this->name[0])
			{
				case "find":
					return $this->find();
					break;
                case "update":
                    return $this->update();
					break;
				case "delete":
					return $this->delete();
					break;
                case "insert":					
                    return $this->insert();
					break;
			}//eof switch
    }
	
	public function find()
	{	
        $table = $this->name[1];
        $db_object = new DB_object();
        
		if(in_array("and",$this->name) || in_array("or",$this->name)  	) 
		{
			$key = $this->name[3];
			$value = $this->params;
			$key1 = $this->name[5];
			$value1 = $this->key;
			$conditions = "WHERE `$key` = '$value'".strtoupper($this->name[4])." `$key1` = '$value1'";
		}
		else if(in_array("all",$this->name))
		{
			$conditions = "";
		}
		else
		{
			$key = $this->name[3];
			$value = $this->params;
			$conditions = "WHERE `$key` = '$value'";
		}
		
		if(in_array("limit",$this->name))
        {
            
			$sql = "SELECT * FROM `$table` $conditions LIMIT 1;";
            $result = $this->DB_Fetch1($sql);
            if(count($result) > 0 && !!$result)
                $result = $db_object->_fill($result);
            else
                return FALSE;

        }
		else
		{
			$sql = "SELECT * FROM `$table` $conditions;";
            $result = $this->DB_FetchALL($sql);
            if(count($result) > 0  && !!$result){
                foreach($result as $key => $value)
                {
                    $result[$key] = $db_object->_fill($value);
                }
   
            }
            else
                return FALSE;
        }
		
		return $result;
	}
	
	public function update()
	{
        $table = $this->name[1];
		$key1 = $this->name[3];
		$value1 =$this->key;		
        $updates ="";
        foreach($this->params  as $key => $value)
		{
			$updates .= "`$key` = '$value',";
		}
		$updates = substr($updates,0,-1);
        $sql = "UPDATE `$table` SET $updates WHERE `$key1` = $value1;";
		$this->DB_Q($sql);
	}
	
	public function insert()
	{
		$table = $this->name[1];
		foreach($this->params  as $key => $value)
		{
			$keys .= "`$key`, ";
			$values .= "'$value', ";
		}
		$keys = substr($keys,0,-2);
		$values = substr($values,0,-2);	
        $sql = "INSERT INTO $table (".$keys.") VALUES ($values);";
		$this->DB_Q($sql);
	}
	
	public function delete()
	{
		$table = $this->name[1];
		$key = $this->name[3];
		$value = $this->params;
		$sql = "DELETE FROM $table WHERE `$key` = '$value';";
		$this->DB_Q($sql); 
	}
    
}

/**
 * DB_functions
 * misc functions for the db
 * 
 * **/
class db_functions
{
    
    
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
