<?php
require("db.php");
?>
<?php
if(isset($_POST['username'])){
	$db=retrieveDb();
	$sql="select * from station where id='".$_POST['station']."'";
	$rs=$db->query($sql);
	
	$row=$rs->fetch_assoc();
	
	$token=$row['token'];
	$station=$_POST['station'];
	
		
	if(($_POST['password']==$_POST['repassword'])&&($token==$_POST['token'])){
		$insertSQL="insert into user(username,password,station,firstName,lastName,role)";
		$insertSQL.=" values ";
		$insertSQL.="('".$_POST['username']."','".$_POST['password']."','".$_POST['station']."','".$_POST['firstName']."','".$_POST['lastName']."','".$_POST['role']."')";
		$insertRS=$db->query($insertSQL);
		echo "<font color=red>User has been added.</font>";
	}
}

?>
<form action='registration.php' method='post'>
<table>
<tr>
<th colspan=2>Enter User Details

</th>
</tr>
<tr>
<td>Username</td>
<td><input type='text' name='username' /></td>
</tr>
<tr>
<td>Password</td>
<td><input type='password' name='password' /></td>
</tr>
<tr>
<td>Reenter Password</td>
<td><input type='password' name='repassword' /></td>
</tr>


<tr>
<th colspan=2>Enter Profile
</th>
</tr>
<tr>
<td>First Name</td>
<td><input type='text' name='firstName' /></td>
</tr>
<tr>
<td>Last Name</td>
<td><input type='text' name='lastName' /></td>
</tr>
<tr>
<tr>
<td>Station</td>
<td>
	<select name='station'>
	<?php
	$db=retrieveDb();
	$sql="select * from station";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;		
		
	for($i=0;$i<$nm;$i++){	
	?>
		<option value='<?php echo $row['id']; ?>'><?php echo $row['name']; ?></option>
	<?php
	}
	?>
	
	</select>
</td>
</tr>
<tr>
<td>Enter Station Token</td>
<td><input type='text' name='token' /></td>

</tr>
<tr>
<td>User Role</td>
<td>
	<select>
		<option value='1'>User</option>
		<option value='2'>Guest</option>
		<option value='3'>Administrator</option>
	</select>
</td>	
<tr>
<th colspan=2><input type=submit value='Submit' /></th>
</tr>
</table>
</form>