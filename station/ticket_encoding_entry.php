<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['ticket_seller'])){
	$ss_incharge=$_POST['ss_incharge'];
	$ticket_seller=$_POST['ticket_seller'];
	$machine_no=$_POST['machine_no'];
	$machine_type=$_POST['machine_type'];
	
	$error_category=$_POST['error_category'];
	$error_code=$_POST['error'];
	$daily_id=$_POST['daily_id'];
	$remarks=$_POST['remarks'];
	$record_no=$_POST['record_no'];
	$incident_no=$_POST['incident_no'];
	$shift=$_POST['shift'];
	
	$year=$_POST['general_year'];
	$month=$_POST['general_month'];
	$day=$_POST['general_day'];
	
	$hour=$_POST['general_hour'];
	$minute=$_POST['general_minute'];
	$amorpm=$_POST['general_amorpm'];

	if($amorpm=="pm"){
		if($hour<12){
			$hour+=12;
			
		}
		else {
		}
	}
	else {
		if($hour=="12"){
			$hour=0;
			
		}
	
	}

	$incident_date=date("Y-m-d H:i",strtotime($year."-".$month."-".$day." ".$hour.":".$minute));	
	
	$update="insert into ticket_error(ticket_daily_id,ss_name,ss_shift,ticket_seller,machine_no,machine_type,remarks,error_code,error_time,record_no,incident_no,error_category)";
	$update.=" values ";
	$update.="('".$daily_id."','".$ss_incharge."','".$shift."','".$ticket_seller."','".$machine_no."','".$machine_type."',\"".$remarks."\",'".$error_code."','".$incident_date."','".$record_no."','".$incident_no."','".$error_category."')";

	$updateRS=$db->query($update);
	
	$ticket_error_id=$db->insert_id;
	
	
	
	$daily_sql="select * from ticket_error_daily where id='".$daily_id."' limit 1";
	$daily_rs=$db->query($daily_sql);
	$daily_row=$daily_rs->fetch_assoc();
	
	$station=$daily_row['station'];
	$ticket_date=$daily_row['date'];
	
	$machine_label=$machine_type." ".$machine_no;
	
/*	
	$incident_update="insert into equipment_incident(incident_type,details,station,date) values ('ticket',\"".$machine_label.", ".$error_code.", ".$remarks."\",'".$station."','".date("Y-m-d",strtotime($ticket_date))."')";
	$incident_update_rs=$db->query($incident_update);
	$monitoring_id=$db->insert_id;		
	
	$issued_update="insert into issued_no(record,incident_cdc,e_incident_id) values ('".$record_no."','".$incident_no."','".$monitoring_id."')";
	$issued_update_rs=$db->query($issued_update);

	$union_update="insert into ticket_to_monitoring(ticket_error_id,monitoring_id) values ('".$ticket_error_id."','".$monitoring_id."')";
	$union_update_rs-$db->query($union_update);
*/		
	echo "<script language='javascript'>";
	
	echo "window.opener.location='ticket_encoding_monitoring.php';";
	
	echo "</script>";	
}
?>
<link rel="stylesheet" href="layout/styles.css" />
<link rel="stylesheet" href="layout/bodyEntry.css" />

<form action='ticket_encoding_entry.php' method='post'>
<table class="EntryTableCLC" width="50%" align="center"><tr><td>
<table class="miniHolderCLC">
<tr>
	<th class="HeaderCLC" colspan="2">Add - Ticket Encoding Error</th>
</tr>
<tr>
<td>SS In-Charge</td>
<td><input type=text name='ss_incharge' placeholder="SS In-Charge" /></td>
</tr>
<tr>
<td>Shift</td>
<td>
<select name='shift'>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
</select>
</td>
</tr>
<tr>
<td>Ticket Seller</td>
<td>

<select name='ticket_seller'>
<?php
$db=new mysqli("localhost","root","","station");

$sql="select * from ticket_seller order by last_name";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();

?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['last_name'].", ".$row['first_name']; ?></option>
<?php
}


?>

</select>
</td>
</tr>
<tr>
<td>Machine No.</td>
<td>
<select name='machine_type'>
<option value='AD'>A/D</option>
<option value='TIM'>TIM</option>
</select>

<input type=text name='machine_no' placeholder="Machine No." style="width:70%;"/>
</td>
</tr>
<?php
/*

<tr>
<th>Error (MTC/Judge)</th>
<td>
<select name='error'>
<?php
$sql="select * from teemr_error_list"; 
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<option value='<?php echo $row['code']; ?>'><?php echo $row['name']; ?></option>
<?php
}
?>
</select>
<!--
<input type=text name='error' size='5' />
-->
</td>
</tr>

*/
?>
<!--
<tr>
<th>Type of Error</th>
<td>
<select name='error_category'>

	<option value='1'>Human Error</option>
	<option value='2'>System Error</option>

</select>
<input type=text name='error' size='5' />
</td>
</tr>
-->

<tr>
<td>Time of Occurence</td>
<td>
<select name='general_month'>
<?php
$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");

for($i=1;$i<13;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$mm){
		echo "selected";
	}
	?>
	>
	<?php
	echo date("F",strtotime(date("Y")."-".$i."-01"));
	?>
	</option>
<?php
}
?>
</select>
<select name='general_day'>
<?php
for($i=1;$i<=31;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$dd){
		echo "selected";
	}
	?>		
	>
	<?php
	
	echo $i;
	?>
	</option>
<?php
}
?>
</select>
<select name='general_year'>
<?php
$dateRecent=date("Y")*1+16;
for($i=1999;$i<=$dateRecent;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$yy){
		echo "selected";
	}
	?>		
	>
	<?php
	echo $i;
	?>
	</option>
<?php
}
?>
</select>
<select name='general_hour'>
<?php
for($i=1;$i<=12;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i*1==$hh*1){
		echo "selected";
	}
	?>		
	>
	<?php
	echo $i;
	?>
	</option>
<?php
}
?>
</select>
<select name='general_minute'>
<?php
for($i=0;$i<=59;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i*1==$min*1){
		echo "selected";
	}
	?>		
	>
	<?php
	if($i<10){
	echo "0".$i;
	}
	else {
	echo $i;
	}
	?>	
	</option>
<?php
}
?>
</select>
<select name='general_amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>
</td>
</tr>

<tr>
<th colspan="2" class="HeaderCLC">Report Number</th>
<!--
<th colspan=2>Report Number</th>
-->
</tr>
<tr>
<td>Record No.</td>
<td><input type=text name='record_no' placeholder="Record No." /></td>
</tr>
<!--
<tr>
<td>Incident No. (CC)</td>
<td><input type=text name='incident_no' /></td>
</tr>
-->
<tr>
<td>Remarks</td>
<td><textarea name='remarks' cols=40 rows=4 placeholder="Remarks"></textarea></td>
</tr>
</table>
</td></tr>
<br>
<?php
if(isset($_GET['daily_id'])){
	$daily_id=$_GET['daily_id'];
}



if($daily_id==""){
}
else {
?>
<tr>
<td class="EntrySubmitCLC"><input type=hidden name='daily_id' value='<?php echo $daily_id; ?>' /><input type='submit' value='Submit' /></td>
</tr>

<?php
}
?>
</table>
</form>