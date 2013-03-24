<?php

/**
 * Template is used for view and editing the views of the of the site
 **/
class template 
{
    public $CSS   = array();
    public $JS    = array();
    public $TITLE = '';
    public $BASEPATH = '';
    public $view = '';
    public $par;

    public function view($location,$VARS = '')
    {
        $this->vars = $VARS;
        $this->view = $location;
		//require_once("application/views/".$filename.".php");
	
    }

    public function _css($file)
    {
        $this->CSS[] = $file;
    }

    public  function _js($file)
    {
        $this->JS[] = $file;
    }

    public function title($title)
    {
        $this->TITLE = $title;
    }

    public function display()
    {
        $this->publish('template/head');
        $this->publish('template/content');
        $this->publish('template/footer'); 
    }

    public function publish($filename)
    {
        if(file_exists("application/views/".$filename.".php"))require_once("application/views/".$filename.".php");
        else return FALSE;
    }

    public function add_var($var_name,$value)
    {
        $this->{$var_name} = $value;
    }
}
?>
