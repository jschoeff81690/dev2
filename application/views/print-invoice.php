<div class="invoice">

	<? echo '<h2>Justin Schoeff\'s<br/> &nbsp;Web Development</h2>';?>
	<div id="info">
	<?php 
	echo 'Invoice ID: '.$this->invoice->id.'<br />';
	echo 'Date Of Invoice: '.$this->invoice->invoice_date.'<br />';
	echo 'Terms: 30 Days<br/>';
	echo 'Payment is Due: '.$this->invoice->due_date.'<br />';
	?></div>
	<div id="locations">
	<?php 
	if($this->par->user->admin == 1)
		{
			echo 'client name: '.$this->par->client->name()."";
			echo 'client address: '.$this->par->client->addr."";
		}
		else
		{
			echo '<p class="loud" style="margin:0px;">To:</p> <ul style="list-style:none;">';
			echo '<li>'.$this->user->name().'</li>';	
			echo '<li>'.$this->user->address.'</li>';
			echo '<li>'.$this->user->city.','.$this->user->state.'</li>';
			echo '<li>'.$this->user->zip.'</li>';
			echo '</ul><p class="loud" style="margin:0px;">From:</p><ul style="list-style:none;"> ';
			echo '<li>Justin Schoeff\'s Web Development</li>';
			echo '<li>3600 SW 19th Ave.</li> <li>Gainesville, FL</li><li> 33325</li>'."<br /></ul>";
			
		}
	?></div>
<?php	
		echo "<table border=\"1\" border-color=\"#000\">\n";
		$c = 0;
		echo '<tr class="even loud large">
		<td>Description</td>
		<td>Quantity</td>
		<td>Total</td>
		</tr>';
		foreach($this->invoice->services as $ser)
		{
			
			if($c%2 == 0)
				echo '<tr>';
			else
				echo '<tr class="even">';
			echo '<td>'.$ser['descript']."</td>";
			echo '<td>'.$ser['quantity']."</td>";
			echo '<td style="text-align:center;">$'.$this->formatMoney($ser['cost'],TRUE)."</td>";
			echo "</tr>\n";
			$c++;
		}
		echo '<tr class="even"><td></td><td></td><td></td></tr>';
		echo '<tr><td></td><td style="text-align:right;">Subtotal</td><td style="text-align:center;">$'.$this->formatMoney($this->invoice->subtotal).'</td></tr><br />';
		echo '<tr><td></td><td style="text-align:right;">Tax</td><td style="text-align:center;">$'.$this->invoice->tax.'</td></tr><br />';
		echo '<tr><td></td><td style="text-align:right;border-style:none;border-size:0px;">Total</td><td style="text-align:center;">$'.$this->formatMoney($this->invoice->total,TRUE). "</td></tr><br />\n";
		echo "</table>\n";
?>
</div>