
							<div class="menu">
				<ul><?php
					//$mItems = getMenu($this->user);
							//$locations = $this->DB->find_locations_by_admin($this->user->admin);
							//$locations = $this->DB->find_sublocations_by_admin($this->user->admin);
							$sql = "SELECT * FROM locations LEFT JOIN sublocations ON locations.id=sublocations.catID";
							$locations = $this->DB_FetchAll($sql);
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
										echo "				<li><a href=\"".$this->BASEPATH."/".str_replace(" ","_",$location['location'])."\">".$txt."</a>\n";
										echo '				<ul>';
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
					</ul>
			</div>
