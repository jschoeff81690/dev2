<?php
class invoice
{
	public $id;
	public $user_id;
	public $project_id;
	public $due_date;
	public $invoice_date;
	public $status = "";// will be paid,limbo(sent but not paid yet), AHH(overdue), ready (to be sent)
	public $country;//if us, this will not display
	public $services = array();//arrayof array(keys = descript,cost)
	public $subtotal;
	public $tax = 1.06;
	public $total;
	
	public function view()
	{
		if(!$this->par->locationID)
		{
			if($this->par->DB->find_invoices_ALL() != FALSE)
			{
				$invoices = $this->par->DB->find_invoices_ALL();
				
				echo 'invoices ordered by status';
				$sorted = $this->sort($invoices);
				foreach($sorted as $invoice)
				{
					$id = $invoice['id'];
					$userid = $invoice['userid'];
					echo "<ul><li><a href=\"/dev/invoices/$id/view\">View invoice id: $id</a><br ></li></ul>";
					
				}
			}
		}
		else
		{
			echo 'status: '.$this->status.'<br />';// will be paid,limbo(sent but not paid yet), AHH(overdue), ready (to be sent)
		
			$this->par->view("invoice");
		}
	}
	
	public function user()
	{
		if(!$this->par->locationID)
		{
			if($this->par->DB->find_users_ALL() != FALSE)
			{
				$invoices = $this->par->DB->find_users_ALL();
				foreach($invoices as $invoice)
				{
					$id = $invoice['id'];
					$name = $invoice['firs']." ".$invoice['last'];
					echo "<ul><li><a href=\"/dev/invoices/$id/view\">View $name</a><br ></li></ul>";
				}
			}
		}
		else
		{
			$this->par->view("invoice");
		}
	}
		
	public function sub()
	{
		$sub=0;
		foreach($this->services as $service)
			$sub += $service['cost'];
		return $sub;
	}
		
	public function total()
	{
		return $this->subtotal;
	}
	
	public function link($str = "")
	{
		return '<a href="/dev/invoices/'.$this->id.'/view">'.$str.'</a>';
	}
	
	public function display_link()
	{
		echo "\n".'Date Of Invoice: '.$this->link($this->par->get_date($this->invoice_date,TRUE,TRUE,TRUE)).'<br />'."\n";
	}
	
	public function recreate($id)
	{
		$data = $this->par->DB->find_invoices_by_id_limit($id);
		$this->id      	      = $id;
		$this->name           = $data['name'];
		$this->user_id	      = $data['userID'];
		$this->due_date       = $data['due_date'];
		$this->invoice_date   = $data['cur_date'];
		$this->status         = $data['status'];
		$this->country        = NULL;
		$service = $this->par->DB->find_service_by_invoiceID($id);
		$this->services = array();
		if($service != FALSE)
		{
			foreach($service as $ser)
			{
				$vals = array(
				'descript'=> $ser['service'],
				'quantity'=>$ser['quantity'],
				'cost'=>$ser['cost']
				);
				array_push($this->services,$vals);
			}
			$this->subtotal       = $this->sub();
			$this->total          = $this->total();
			if($this->par->client != NULL)
				$this->par->client->recreate($this->user_id);
		}
		
	}
	
	public function sort($arr)
	{
	// will be paid,limbo(sent but not paid yet), AHH(overdue), ready (to be sent)
		$groups = array(array(),array(),array(),array());
		for($x=0;$x < count($arr);$x++)
		{
			switch($arr[$x]['status'])
			{
				case 'AHH':
				array_push($groups[0],$arr[$x]);
				break;
				
				case 'limbo':
				array_push($groups[1],$arr[$x]);
				break;
				
				case 'paid':
				array_push($groups[2],$arr[$x]);
				break;
				
				case 'ready':
				array_push($groups[3],$arr[$x]);
				break;
			}
		}
		$arra = array();
		$arra = array_merge($arra,$groups[0],$groups[1],$groups[2],$groups[3]);
		return $arra;
	}
	public function createForm()
	{
		//wait for paypal set upto know what forms to need
	}
	public function remove()
	{
		if(!$this->par->locationID)
		{
			$invoices = $this->par->DB->find_invoices_ALL();
				
				echo 'invoices ordered by status';
				$sorted = $this->sort($invoices);
				foreach($sorted as $invoice)
				{
					$id = $invoice['id'];
					$userid = $invoice['userid'];
					echo "<ul><li><a href=\"/dev/invoices/$id/remove\">remove invoice id: $id</a><br ></li></ul>";
				}
		}
		else
		{
			$this->par->DB->delete_invoices_by_id($this->par->locationID);
		}
	}
	
	public function pay()
	{
		if(!$this->par->locationID)
		{
			$this->view();
		}
		else
		{
			$this->par->helper('includes/phpf/classes/form.class.php');
			$fb = new form_class($this->par); 
			$params = array(
				array(
					'label' => '',
					'type'  => 'hidden',
					'name'  => 'business',
					'value' => 'justnjust816@gmail.com',
					'rules' => ''
				),
				array(
					'label' => '',
					'type'  => 'hidden',
					'name'  => 'cmd',
					'value' => '_cart',
					'rules' => ''
				),
				array(
					'label' => '',
					'type'  => 'hidden',
					'name'  => 'upload',
					'value' => '1',
					'rules' => ''
				),
				array(
					'label' => '',
					'type'  => 'hidden',
					'name'  => 'invoice',
					'value' => $this->id,
					'rules' => ''
				),
				array(
					'label' => '',
					'type'  => 'hidden',
					'name'  => 'currency_code',
					'value' => 'USD',
					'rules' => ''
				)
			);
			
			echo "<div style=\"background-color:#FFF\"><table border=\"1\" border-color=\"#000\">\n";
			$c = 0;
			echo '<tr class="even loud large">
			<td>Description</td>
			<td>Quantity</td>
			<td>Total</td>
			</tr>';
			foreach($this->services as $ser)
			{
				
				if($c%2 == 0)
					echo '<tr>';
				else
					echo '<tr class="even">';
				echo '<td>'.$ser['descript']."</td>";
				echo '<td>'.$ser['quantity']."</td>";
				echo '<td style="text-align:center;">$'.$this->par->formatMoney($ser['cost'],TRUE)."</td>";
				echo "</tr>\n";
				$c++;
				$form = array(
					'label' => '',
					'type'  => 'hidden',
					'name'  => 'item_name_'.($c),
					'value' => $ser['descript'],
					'rules' => ''
				);
				array_push($params,$form);
				$cost = $this->par->formatMoney($ser['cost'],TRUE);
				$form1 = array(
					'label' => '',
					'type'  => 'hidden',
					'name'  => 'amount_'.($c),
					'value' => $cost,
					'rules' => ''
				);
				array_push($params,$form1);
				if($ser['quantity'] != "N/A")
				{
					$q = substr($ser['quantity'],0,-6);
					$form2 = array(
						'label' => '',
						'type'  => 'hidden',
						'name'  => 'on_'.($c-1),
						'value' => 'Time',
						'rules' => ''
					);
					array_push($params,$form2);
					$form3 = array(
						'label' => '',
						'type'  => 'hidden',
						'name'  => 'os_'.($c-1),
						'value' => $q,
						'rules' => ''
					);
					array_push($params,$form3);
				}
			}
			echo '<tr class="even"><td></td><td></td><td></td></tr>';
			echo '<tr><td></td><td style="text-align:right;border-style:none;border-size:0px;">Total</td><td style="text-align:center;">$'.$this->par->formatMoney($this->total,TRUE). "</td></tr>\n";
			echo '</table></div>';
			$fb->add_label($params);
			$fb->setAction("https://www.paypal.com/cgi-bin/webscr");
			$fb->display_form(FALSE);
		}
	}
	
}