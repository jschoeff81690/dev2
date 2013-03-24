<?php
$f=FALSE;
if($f)
{
?>
							<!--<div class="menu"> -->
             <!-- nav menu -->
<div class="navbar">
  <div class="navbar-inner">
    <div class="container-fluid">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="icon-user"></i>Justin Schoeff 
                <span class="caret"></span>
            </a>
        <ul class="dropdown-menu">
        <li><a href="<?php echo $this->BASEPATH; ?>/user/settings">Settings</a></li>
          <li class="divider"></li>
          <li><a href="<?php echo $this->BASEPATH; ?>/user/logout">Logout</a></li>
        </ul>
      </div>
      <div class="nav-collapse">
      <ul class="nav">
                
          

              <?php
					//$mItems = getMenu($this->user);
							//$locations = $this->DB->find_locations_by_admin($this->user->admin);
							//$locations = $this->DB->find_sublocations_by_admin($this->user->admin);
							// $sql = "SELECT * FROM locations LEFT JOIN sublocations ON locations.id=sublocations.catID";
                            // $locations = $this->DB_FetchAll($sql);
                            
                            $sql = "SELECT * FROM locations LEFT JOIN sublocations ON locations.id=sublocations.catID";
                            $locations = $this->par->DB->DB_FetchAll($sql);
                            $list = FALSE;//means closed
							$cur = "______";
							
							foreach($locations as $location)
							{
								if($location['location'] != $cur && $cur != "______" && $list == true)//means new list item
									{
										$cur =$location['location'];
										$list=false;
										echo '</ul></li>';
									}
								if($location['catID'] == null)
                                {   
                                    $this->user->admin = false;
									if($this->user->admin == $location['admin'])
									{	
										$txt = str_replace("_"," ",$location['location']);
										echo "					<li><a href=\"".$this->BASEPATH."/".str_replace(" ","_",$location['location'])."\">".$txt."</a></li>\n";
									}
								}
								else
								{
									
									
									if(!$list)
									{
										$txt = str_replace("_"," ",$location['location']);
										echo "				<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"".$this->BASEPATH."/".str_replace(" ","_",$location['location'])."\">".$txt."<b class=\"caret\"></b></a>\n";
										echo '				<ul class="dropdown-menu">';
											$txt2 = str_replace("_"," ",$location['sublocation']);
										echo "				<li><a href=\"".$this->BASEPATH."/".str_replace(" ","_",$location['sublocation'])."\">".$txt2."</a><li>\n";
										$cur =$location['location'];
										
										$list = true;
									}
									else
									{
										$txt = str_replace("_"," ",$location['sublocation']);
										echo "				<li><a href=\"".$this->BASEPATH."/".$txt."\">".$txt."</a><li>\n";
									}
										
									
								}
							}
					?>
                    </ul><!-- nav-->
                </div><!-- eof nav collapse -->
        </div>
      </div>
    </div>
<?php
    }
?>
