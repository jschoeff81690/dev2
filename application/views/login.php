<?php 
        $fb = $this->vars;
        $params = array(
            array(
                'label' => 'Email Address:',
                'name'  => 'user',
                'rules' => 'email'
            ),
            array(
                'label' => 'Password:',
                'type'  => 'password',
                'name'  => 'pw',
                'rules' => 'pass'
            )
        );

        $fb->add_group('','login-fields',$params); 
        echo '<div id="loginBlock">';
            //$this->par->helper('includes/phpf/classes/form.class.php');
                //$fb = new form_class($this->par); 
            if($fb->display() || !$this->login->LOGGED)
            {
                
                $params = $fb->get_inputs();
            }
        echo '</div>';

?>
