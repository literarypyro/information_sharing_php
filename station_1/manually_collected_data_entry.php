<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['manually_id'])){

	$manually_id=$_POST['manually_id'];

	$teemr_no=$_POST['mc_no'];
	$receiving_ca=$_POST['cash_assistant'];

	
	$update="update manually_collected_by_station set no_of_forms='".$teemr_no."',cash_assistant='".$receiving_ca."' where id='".$manually_id."'";
	$updateRS=$db->query($update);	
	
	
	echo "<script language='javascript'>";
	
	echo "window.opener.location.reload();";
	
	
	echo "</script>";		
	
}
?>
<?php
if(isset($_GET['manually_id'])){
	$manually_id=$_GET['manually_id'];
}
?>
<form action='manually_collected_data_entry.php' method='post'>
<table style='border:1px solid gray;'>
<tr>
<th colspan=2>Enter Manually Collected Data</th>
</tr>
<tr>
<th># of Manually Collected Forms</th><td><input type=text name='mc_no' size=50 /></td>
</tr>
<tr>
<th>Cash Assistant</th>

<td>
<select name='cash_assistant'>
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
<input type=hidden value='<?php echo $manually_id; ?>' name='manually_id' />
<input type=submit value='Submit' />
</th>
</tr>
</table>
</form>