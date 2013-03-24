<?php
class calendar
{	
	//these are set on instantiation
	//dow == day of week
	//dim ==days in month
	//dimL = days in month(last) 
	//dowN = day of week #
	public $day,$month,$year,$dow,$dowN,$time,$dim,$dimL;
	
	//set after getEvents() has been called
	public $events,$eToday;
	public function _init()
	{
		$this->table = 'events';
		$this->day = date("d");
		$this->dow = date("l");
		$this->dowN = date("N")+1; //plus 1 because "N" gives 1for mon but we want 1 for sun so add 1
		$this->month = date("M");
		$this->year = date("Y");
		$this->dim = date("t");
		$this->dimL = date("t",mktime(0,0,0,date("m")-1,0,$this->year));
		echo $this->month."/".$this->day."/".$this->year;
		echo "<br />".$this->dow."<br /><br />";
		
	}//eof construct
	//MiniCal echos a small calendar 
	// -- note -- add usability like events and such, maybe size, height
	public function miniCal()
	{//display days of week
		?>
		<table>
		<tr>
		<td>Sun</td>
		<td>Mon</td>
		<td>Tue</td>
		<td>Wed</td>
		<td>Thu</td>
		<td>Fri</td>
		<td>Sat</td>
		</tr>
		
		<?php
		//these for loops create the mini calendar
		// this count days from month prior to current that will show on this cal
		//dowN = day of week # for current day, so it counts down from that number 
		//to determine days from other month
		
		$count =0;//used to count 7 days a week and then drop to next week
		for ($x=$this->dowN;$x>0;$x--)
		{
			if($count === 0)
			{
				echo "<tr>";
			}
			echo "<td>".($this->dimL-$x)."</td>";
			$count++;
			if($count === 7)
			{
				$count = 0;
				echo "</tr>";
			}
		}//eoffor x=dowN
		// echos all days of this month
		// puts them in tables
		for ($x=1;$x<=$this->dim;$x++)
		{
			if($count === 0)
			{
				echo "<tr>";
			}
			echo "<td>$x</td>";
			$count++;
			if($count === 7)
			{
				$count = 0;
				echo "</tr>";
			}
		}
		//if the last day of this month isnt a Saturday, then we finish with the next month days
		$num = 7-$count;
		for ($x=1;$x<=$num;$x++)
		{
			if($count === 0)
			{
				echo "<tr>";
			}
			echo "<td>$x</td>";
			$count++;
			if($count === 7)
			{
				$count = 0;
				echo "</tr>";
			}//eof count == 7
		}//eof for 7- coutn
		echo "</table>";	
	}//eof miniCal
	public function addEvent($projID,$start,$end,$descript)
	{
		$res = $this->DB_Query("INSERT INTO `events` (  `ID` ,  `projectID` ,  `descript` , `start` ,  `end` ) 
		VALUES (NULL ,  '$projID',  '$descript', '$start',  '$end');"); 
		return $res;
	}//eof addEvent
	public function removeEvent($id)
	{
		$res = $this->DB_Query("DELETE FROM `events` WHERE `ID` = $id LIMIT 1;");
		return $res;
	}//eof removeEvent
	public function updateEvent($id,$start,$end,$descript,$compl)
	{
		//basically write what is updated/ update everything from PARAMS
		$res = $this->DB_Query("UPDATE  `events` SET `descript`='$descript', `start` =  '$start',`end` =  '$end', `completed`='$compl' WHERE  `ID` =$id LIMIT 1;");
		return $res;
	}//eof updateEvent
	public function largeCal()
	{
		
	}//eof largeCal
//--all events are sorted into chronological order ---\\
	// - returns a multi-D array, each slot is an array for an event.
	
	//gets all events within a set time period
	public function getEvents($dayS,$monthS,$yearS,$dayE,$monthE,$yearE)
	{
		return $this->DB_Query("SELECT FROM `events` WHERE ");
	}//eof getEvents
	
	//get all events
	// **admin only** \\
	public function getEventsALL()
	{
		//var_dump($this->find_by_all("","events"));
	
	
		//$res =  $this->DB_FetchALL("SELECT * FROM `events`;");

// $array = $res;
// $on = "start";

// for($x=0;$x<count($array);$x++){
// $array2[$x] = str_replace("-","0",$array[$x]['start']);
// $num[$x] = $x;}

// print_r($array2)."<br />";

 // for($x=0;$x<count($array2)-1;$x++){
// if((int)$array2[$x] > (int)$array2[$x+1]){
// $temp = $array2[$x];
// $array2[$x] =$array2[$x+1];
// $array2[$x+1] = $temp;
// $temp = $num[$x];
// $num[$x] =$num[$x+1];
// $num[$x+1] = $temp;}
// }
// echo "<br/ >";
// print_r($array2);

// return $res;
	}//eof get events all
	
	//gets all events for a project
	//  - more admin, admin can set events for a project that user doesnt see. 
	public function getEventsP()
	{
	}//eof get events project
	//get all events for that user 
	//        - used more admin not clients
	public function getEventsU()
	{
	}//eof getEvents user
	//get events by projects and user
	public function getEventsPU()
	{
	}//eof get event by project and user
}//eof of calendar
?>