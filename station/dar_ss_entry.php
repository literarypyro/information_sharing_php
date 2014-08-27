<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['daily_id'])){
	$daily_id=$_POST['daily_id'];
	$shift1=$_POST['s1'];
	$shift2=$_POST['s2'];
	$shift3=$_POST['s3'];
	
	$supervisor_sql="select * from dar_supervisor where daily_id='".$daily_id."'";
	$supervisor_rs=$db->query($supervisor_sql);
	$supervisor_nm=$supervisor_rs->num_rows;
	if($supervisor_nm>0){
		$supervisor_row=$supervisor_rs->fetch_assoc();
		
		$supervisor_update="update dar_supervisor set s1='".$shift1."',s2='".$shift2."',s3='".$shift3."' where id='".$supervisor_row['id']."'";
		$supervisor_update_rs=$db->query($supervisor_update);
	}
	else {
		
		$supervisor_update="insert into dar_supervisor(s1,s2,s3,daily_id) values ('".$shift1."','".$shift2."','".$shift3."','".$daily_id."')";
		$supervisor_update_rs=$db->query($supervisor_update);
	}	
	
	echo "<script language='javascript'>";
	
	echo "window.opener.location.reload();";
	
	
	echo "</script>";		
	
}
?>
<?php
if(isset($_GET['daily_id'])){
	$daily_id=$_GET['daily_id'];
}
?>
<form action='dar_ss_entry.php' method='post'>
<table style='border:1px solid gray;'>
<tr>
<th colspan=2>Station Supervisors</th>
</tr>
<tr>
<th>Shift 1</th><td><input type=text name='s1' size=50 /></td>
</tr>
<tr>
<th>Shift 2</th><td><input type=text name='s2' size=50 /></td>
</tr>
<tr>
<th>Shift 3</th><td><input type=text name='s3' size=50 /></td>
</tr>
<tr>
<th colspan=2>
<input type=hidden value='<?php echo $daily_id; ?>' name='daily_id' />
<input type=submit value='Submit' />
</th>
</tr>
</table>
</form>