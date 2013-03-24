<?php
/**
 * 
 **/
class priority_model extends model
{
    public $db_table = "priorities";  
    
    function get_priorities()
    {
        return $this->read_ALL();
    }

    public function parse($data)
    {
        foreach($data as $i)
        {
            echo '<pre>';
            var_dump($i);
            echo '</pre>';
        }
    }
}

?>
