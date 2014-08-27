<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['daily_id'])){

	$daily_id=$_POST['daily_id'];

	$teemr_no=$_POST['teemr_no'];
	$receiving_ca=$_POST['receiving_ca'];

	
	$update="update ticket_error_daily set no_of_teemr='".$teemr_no."',receiving_ca='".$receiving_ca."' where id='".$daily_id."'";
	$updateRS=$db->query($update);	
	
	
	echo "<script language='javascript'>";
	
	echo "window.opener.location='ticket_encoding_monitoring.php';";
	
	
	echo "</script>";		
	
}
?>
<?php
if(isset($_GET['daily_id'])){
	$daily_id=$_GET['daily_id'];
}
?>
<form action='teemr_data_entry.php' method='post'>
<table style='border:1px solid gray;'>
<tr>
<th colspan=2>Enter TEEMR Data</th>
</tr>
<tr>
<th># of TEEMR</th><td><input type=text name='teemr_no' size=50 /></td>
</tr>
<tr>
<th>Receiving CA</th>

<td>
<select name='receiving_ca'>
<?php
	$sql="select * from cash_assistant order by lastName";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
?>		
	<option value='<?php echo $row['username']; ?>'><?php echo $row['lastName'].", ".$row['firstName']; ?></option>	

<?php		
	}

?>
</select>
</td>
</tr>
<tr>
<th colspan=2>
<input type=hidden value='<?php echo $daily_id; ?>' name='daily_id' />
<input type=submit value='Submit' />
</th>
</tr>
</table>
</form>