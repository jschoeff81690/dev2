<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php /*echo $this->getTitle();*/?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link href="<?php echo $this->INCLUDES;?>/css/main.css" rel="stylesheet" type="text/css" />

</head>



<body id="bg">
<div id="wrapp">
<?php
	if(! $this->login->LOGGED)
	{
		$this->login->display();
	}
	else
	{	
		$this->view("header");
		$this->view("content");
		$this->view("footer");
		
	}
?>
</div>
</body>
</html>