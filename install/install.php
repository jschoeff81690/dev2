<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Setup Configuration File</title>
<link rel="stylesheet" href="css/install.css" type="text/css" />

</head>
<body>
<?php

$step = isset( $_GET['step'] ) ? (int) $_GET['step'] : 0;

switch($step):
	case 0: 
	echo " ";
?>


<h1> Step 1</h1>
<h3>Database information</h3>
<form method="post" action="install.php?step=1">
	<p>Below you should enter your database connection details. If you&#8217;re not sure about these, contact your host.</p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="dbname">Database Name</label></th>
			<td><input name="dbname" id="dbname" type="text" size="25" value="webdev" /></td>
			<td>The name of the database you want to create/use dev in.</td>
		</tr>
		<tr>
			<th scope="row"><label for="uname">User Name</label></th>
			<td><input name="uname" id="uname" type="text" size="25" value="username" /></td>
			<td>Your MySQL username</td>
		</tr>
		<tr>
			<th scope="row"><label for="pwd">Password</label></th>
			<td><input name="pwd" id="pwd" type="text" size="25" value="password" /></td>
			<td>&hellip;and your MySQL password.</td>
		</tr>
		<tr>
			<th scope="row"><label for="dbhost">Database Host</label></th>
			<td><input name="dbhost" id="dbhost" type="text" size="25" value="localhost" /></td>
			<td>You should be able to get this info from your web host, if <code>localhost</code> does not work.</td>
		</tr>
		
	</table>
		<p class="step"><input name="submit" type="submit" value="Submit" class="button button-large" /></p>
</form>

<?php 
	break;

	case 1:

		foreach ( array( 'dbname', 'uname', 'pwd', 'dbhost') as $key )
			$$key = trim( stripslashes( $_POST[ $key ] ) );


		$config = file("../application/sys/config.php");

		//set development location on server for BASEPATH and ASSETS
				$loc = $_SERVER["SCRIPT_NAME"];
		$loc = explode("/",$loc);
		$file = "";
		foreach($loc as $location) {
			if($location =="install")
				break;
			if($location != "")
			$file .= "/".$location;
		}

        $config[16] = "\t    'BASEPATH'     => 'http://".$_SERVER['SERVER_NAME']."".$file."',\n";
        $config[17] = "\t    'ASSETS'    => 'http://".$_SERVER['SERVER_NAME']."".$file."/application/assets'\n";
		$config[34] = "\t	   'DB_IP'         => '".$dbhost."',\n";
        $config[35] = "\t    'DB_USER'      => '".$uname."',\n";
        $config[36] = "\t    'DB_PASS'      => '".$pwd."',\n";
        $config[37] = "\t    'DB_NAME'      => '".$dbname."'\n";
		file_put_contents("../application/sys/config.php",$config);
		
?>
		<h1>Step 2</h1>
		<h3>Install the databse tables</h3>
		<p> Click "Install" to install database.</p>
		<form method="post" action="install.php?step=2">
			<input type="hidden" value="<?php echo $uname; ?>" name="uname">
			<input type="hidden" value="<?php echo $pwd; ?>" name="pwd">
			<input type="hidden" value="<?php echo $dbname; ?>" name="dbname">
			<input type="hidden" value="<?php echo $dbhost; ?>" name="dbhost">
 			<p class="step"><input name="submit" type="submit" value="Install" class="button button-large" /></p>
		</form>
<?php
		break;
	case 2:	
			echo '<h1>Last Part</h1>';

			foreach ( array( 'dbname', 'uname', 'pwd', 'dbhost' ) as $key )
			$$key = trim( stripslashes( $_POST[ $key ] ) );

			//create the actual database

			$con=mysqli_connect($dbhost,$uname,$pwd);
			// Check connection
			if (mysqli_connect_errno())
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			
			// Create database
			$sql="CREATE DATABASE ".$dbname;
			if (mysqli_query($con,$sql))
				echo "Database ".$dbname." created successfully";

				mysqli_select_db($con,$dbname);
	
			//tabless
			$sql = file("install.sql");
			$in=FALSE;
			$query = "";
			//extract sql and execute
			foreach($sql as $ln => $line) {
				if($in === FALSE) {
					if(strstr($line,"CREATE") !== FALSE) {
					 	$in = TRUE;
					 }
					 if(strstr($line,"INSERT") !== FALSE) {
					 	$in = TRUE;
					 }

				}

				if($in === TRUE) {
					$end = strstr($line,";",TRUE);
					if($end === FALSE) {
						$query .= $line;
					}
					else {
						$query .= $end.";";

						if (!mysqli_query($con,$query))
							printf("Error: %s\n", mysqli_error($con));
						$in = FALSE;
						$query =" ";
					}

				}
			}
?>
	<h3>All Finished</h3>
	<p>You can now use webdev with <br />Username: admin<br /> Password: admin <br /><br /> You can now close this window, Thank you.</p>
<?php
		break;
	default: echo "ERROR";
endswitch;
?>

</body>
</html>
<?php 