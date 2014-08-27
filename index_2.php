<?php
session_start();
?>
<?php
session_destroy();
?>
<?php
//header("Location: monitoring menu.php");

?>
<?php
if(isset($_POST['password'])){
	if($_POST['password']=="123456"){
		
		if($_POST['unit']=="ssu"){
			header("Location: ssu/index.php");	

		}
		else if($_POST['unit']=="station"){
			header("Location: station/index.php");	
		
		
		
		}
		else if($_POST['unit']=="transport"){
			header("Location: transport/index.php");	
		
		
		
		}
		else if($_POST['unit']=="gm"){
			header("Location: monitoring/index.php");	
		
		
		
		}
	
	}
}
?>

<link rel="stylesheet" type="text/css" href="layout/login.css">
<table class='loginTable' align=center >
<tr>
<td valign=top align=center>
<br>
<br>
<br>
<br>
<br>
<br>
<form enctype="multipart/form-data" action='index.php' method='post'>
<table  align=center  >
<tr><th colspan=2>Log-In Here:</th></tr>
<tr>
	<td>Enter Division/Unit:</td>
	<td>
	<select name='unit'>
	<option value='station'>Station</option>
	<option value='transport'>Transport</option>
	<option value='ssu'>Safety and Security Unit</option>
	<option value='gm'>Office of the General Manager</option>
	</select>
	</td>
</tr>
<tr>
	<td>Enter Password:</td>
	<td><input type='password' name='password'  /></td>
</tr>
<tr>
	<td colspan=2 align=center><input type=submit value='Submit' /></td>
</tr>
</table>
</form>
</td>
</tr>
</table>

