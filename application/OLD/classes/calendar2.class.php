<?php
class calendar2
{	
	public $date,$day,$month,$year,$first_day,$month_name;
	
	public function _init()
	{
		
		if(isset($this->par->URL[1]) && isset($this->par->URL[2]))
		{
			
			
			
			
			//need to check if safe!!!!!!!!
			
			
			
			$this->day = 00;
			$this->month = $this->par->URL[1]; 
			$this->year = $this->par->URL[2];
		}
		else
		{
			$this->date = time(); //today's date 
			$this->day = date('d'); 
			$this->month = date('m'); 
			$this->year = date('Y');
		}
	
		//first day of the month 
		$this->first_day = mktime(0,0,0,$this->month, 1, $this->year) ; 
		
		//month name 
		$this->month_name = date('F', $this->first_day) ; 
		
		$this->first_day = date('D', $this->first_day) ; 
		switch($this->first_day)
		{ 
			case "Sun": $blank = 0; break; 

			case "Mon": $blank = 1; break; 

			case "Tue": $blank = 2; break; 

			case "Wed": $blank = 3; break; 

			case "Thu": $blank = 4; break; 

			case "Fri": $blank = 5; break; 

			case "Sat": $blank = 6; break; 
		}
		$this->blank = $blank;
		
		$this->days_in_month = cal_days_in_month(0, $this->month, $this->year) ; 
	}
	
	public function display()
	{
		$this->get_events($this->month);
		if($this->month == 12)
			$next = "1/".($this->year+1)."/";
		else 
			$next = ($this->month+1)."/".($this->year)."/";
		
		if($this->month == 1)
			$prev = "12/".($this->year-1)."/";
		else 
			$prev = ($this->month-1)."/".($this->year)."/";
		
		?>
		<div id="calendar">
		<div id="title"><a href="<?php echo $this->BASEPATH.'/dev2/Calendar/'.$prev;?>" title="Previous Month"><img src="<?php echo $this->par->INCLUDES.'/images/prev_arrow.jpg'; ?> " /></a><p><?php echo $this->month_name." ".$this->year;?></p><a href="<?php echo $this->BASEPATH.'/dev2/Calendar/'.$next;?>" title="Next Month"><img src="<?php echo $this->par->INCLUDES.'/images/next_arrow.jpg'; ?> " /></a></div>
		<ul class="names">
			<li><p>Sun</p></li>
			<li><p>Mon</li>
			<li><p>Tue</p></li>
			<li><p>Wed</p></li>
			<li><p>Thu</p></li>
			<li><p>Fri</p></li>
			<li><p>Sat</p></li>
		</ul>
		<?php
		$count =0;
		
		echo '<ul>';
		for($b=0;$b<$this->blank;$b++)
		{			
			echo '<li class="dim"> </li>';
			$count++;
		}
	
		for($x=1;$x<=$this->days_in_month;$x++)
		{
			
			if($count == 7)	
			{
				echo '</ul><ul>';
				$count =0;
			}
			
			$link=FALSE;
			if(!!$this->events)
			{
				foreach($this->events as $event)
				{
				
					if($event['day'] == $x && $event['year'] == $this->year)
					{
						if($link == FALSE)
							echo '<a href="'.$this->BASEPATH.'/dev/Calendar/day/"><li class="day"><p>'.$x.'</p>';
							
							echo "<p>".$event['title'].'</p>';
						$link= TRUE;
					}
					 
				}
				if($link == FALSE)
					echo '<li class="day"><p>'.$x.'</p></li></a>';
				else
					echo '</li></a>';
			}
			else
			{
			
				echo '<li class="day"><p>'.$x.'</p></li></a>';
			
			}
			$count++;
		}
		echo '</ul></div>';
		
		
	}//eof display


	public function get_events($month)
	{
		$this->events = $this->par->DB->find_cevent_by_month_($month);
		
	}
}
?>
