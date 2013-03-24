<?php
if(!$this->action)
		{
			echo '<div id="content">'."<ul><li><a href=\"".$this->BASEPATH."/priorities/view\">View Priorities </a><br ></li>
		<li><a href=\"".$this->BASEPATH."/priorities/Constant\">Add a Constant</a><br ></li>
		<li><a href=\"".$this->BASEPATH."/priorities/newpr\">Add a priority</a><br ></li></ul></div>";
		}
		else
        {
			echo '<div id="content">'."<ul><li><a href=\"".$this->BASEPATH."/priorities/view\">View Priorities </a><br ></li><li><a href=\"".$this->BASEPATH."/priorities/Constant\">Add a Constant</a><br ></li>
		<li><a href=\"".$this->BASEPATH."/priorities/newpr\">Add a priority</a><br ></li></ul>";
 
            $this->load("priority","pr");
            $this->pr->_init();
            switch($this->action)
            {
            case "view":$this->pr->display(null);break;
            case "Constant":$this->pr->addCONSTANT();break;
            case "newpr":$this->pr->addRANDOM();break;

            }
            echo '</div>';
       }

?>
