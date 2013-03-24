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



<form method="post" action="install.php?step=1">
	<p>Below you should enter your database connection details. If you&#8217;re not sure about these, contact your host.</p>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="dbname">Database Name</label></th>
			<td><input name="dbname" id="dbname" type="text" size="25" value="webdev" /></td>
			<td>The name of the database you want to run dev in.</td>
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
		<tr>
			<th scope="row"><label for="prefix">Table Prefix</label></th>
			<td><input name="prefix" id="prefix" type="text" value="dev_" size="25" /></td>
			<td>If you want to run multiple installations in a single database, change this.</td>
		</tr>
	</table>
		<p class="step"><input name="submit" type="submit" value="Submit" class="button button-large" /></p>
</form>

<?php 
	break;

	case 1:

		foreach ( array( 'dbname', 'uname', 'pwd', 'dbhost', 'prefix' ) as $key )
			$$key = trim( stripslashes( $_POST[ $key ] ) );


		$config = file("application/sys/config.php");

		foreach ($lines as $line_num => $line) {
	    	echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";

		}

		break;
	default: echo "ERROR";
endswitch;



sad?>

</body>
</html>
<?php 