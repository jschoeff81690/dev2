<?php
/**
 * AUTH Class used to log users in and out n such
 **/
class auth
{
    
    public function index()
    {
       $this->par->login->LOGGED = $this->par->login->check_logged();
        
        if(!$this->par->login->LOGGED){
            $this->par->login->set_forms();
            $this->par->login->LOGGED = $this->par->login->check_post();
        }       
        if(!$this->par->login->LOGGED) {
            $this->par->template->_css('bootstrap');
            $this->par->template->_css('bootstrap-responsive');
            $this->par->template->title($this->par->TITLE);
            $this->par->template->_js('jquery');
            $this->par->template->_js('bootstrap-collapse');
            $this->par->template->_js('bootstrap-dropdown');
            $this->par->template->view('auth/login');
            $this->par->template->display();
        }
        else
            redirect('/');
    }

    public function logout()
    {
        $this->par->login->logout();
        redirect('/auth');
    }
}
?>
