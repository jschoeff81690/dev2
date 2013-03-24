<?php
//aved- add view edit delete
class controller
{
	public function create_model($name)
	{
		$name = $name."_model";
		require_once("includes/phpf/models/".$name.".php");
		$this->model = new $name();
		$this->model->par =& $this->par;
    }
    public function delete($id)
    {
        /*
         * if(!$this->par->locationID)
            {
                $prects = $this->par->DB->find_projects_ALL();
                if($projects != FALSE)
                {
                    foreach($projects as $project)
                    {
                        $id = $project['id'];
                        $name = $project['name'];
                        $userid = $project['userid'];
                        echo "<ul><li><a href=\"/dev/projects/$id/remove\">remove $name</a><br ></li></ul>";
                        
                    }
                }
            }
            else
            {
                $this->par->DB->delete_projects_by_id($this->par->locationID);
            }

         */
       $this->model->delete($id); 
    }

}
