<?php 

        $fb = $this->par->fb;
        echo '<br /><br /> <div class="row">'."\n <div class=\"span6\">";
            //$this->par->helper('includes/phpf/classes/form.class.php');
                //$fb = new form_class($this->par); 
            if($fb->display())
            {
                
                $params = $fb->get_inputs();
            }
        echo '</div></div>';

?>
