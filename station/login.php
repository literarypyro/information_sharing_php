<?php
session_start();
?>
<?php
require("db.php");
?>
<?php
if(isset($_POST['username'])){
	$db=retrieveDb();
	
	$username=$_POST['username'];
	$password=$_POST['password'];
	
	
	
	$sql="select * from user where username='".$username."' and password='".$password."'";
	$rs=$db->query($sql);
		
	$nm=$rs->num_rows;

	if($nm>0){
		$row=$rs->fetch_assoc();
		
		$token=$row['token'];
		$user_station=$row['station_id'];
		
		$role=$row['role'];
		
		
		$_SESSION['token']=$token;
		$_SESSION['user_station']=$user_station;
		$_SESSION['user_role']=$role;
		
		
		
		header("Location: monitoring menu.php");
	}
	


}






?>
<form action='login.php' method='post'>
<table>
<tr><th colspan=2>Enter Login</th></tr>
<tr><td>Username</td><td><input type='text' name='username' /></tr>
<tr><td>Password</td><td><input type='password' name='password' /></tr>
<tr><td colspan=2 align=center><input type='submit' value='Submit' /></td></tr>
</table>
</form>





