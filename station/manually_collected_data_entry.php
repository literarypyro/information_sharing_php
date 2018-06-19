<?php
require("db.php");
?>
<?php
$db=retrieveDb();
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
<link rel="stylesheet" href="layout/styles.css" />
<link rel="stylesheet" href="layout/bodyEntry.css" />
<form action='manually_collected_data_entry.php' method='post'>
<table class="EntryTableCLC" width="40%" align="center"><tr><td>
<table class="miniHolderCLC">
<tr>
<th class="HeaderCLC" colspan=2>Enter Manually Collected Data</th>
</tr>
<tr>
<td># of Manually Collected Forms</td><td><input type=text name='mc_no' placeholder="# of Manually Collected Forms" /></td>
</tr>
<tr>
<td>Cash Assistant</td>

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
</table>
</td></tr>
<tr>
<td class="EntrySubmitCLC">
<input type=hidden value='<?php echo $manually_id; ?>' name='manually_id' />
<input type=submit value='Submit' />
</td>
</tr>
</table>
</form>