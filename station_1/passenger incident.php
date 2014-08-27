<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
if(isset($_POST['description'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];	
	
	$passenger_name=$_POST['passenger'];
	$description=$_POST['description'];
	$action=$_POST['action'];
	$recommendation=$_POST['recommendation'];
	$details=$_POST['details'];
	$findings=$_POST['findings'];
	
	$personnel=$_POST['personnel'];
	$name_of_personnel=$_POST['name_of_personnel'];
	
	$hour=$_POST['hour'];
	$minute=$_POST['minute'];
	$amorpm=$_POST['amorpm'];
	if($amorpm=='pm'){
		$hour+=(12*1);
		if($hour>=24){
			$hour=0;
		}
	}
	else {
		$hour=$hour;
		
	}
	$complaint_date=$year."-".$month."-".$day." ".$hour.":".$minute;
	$station=$_POST['station'];
	
	
	$db=new mysqli("localhost","root","","station");
	$sql="insert into passenger_incident(passenger_name,date,description";
	$sql.=",action_taken,recommendation,station,type,findings,personnel,name_of_personnel)";
	$sql.=" values ('".$passenger_name."','".$complaint_date."','".$details."'";
	$sql.=",'".$action."','".$recommendation."','".$station."','".$description."','".$findings."','".$personnel."','".$name_of_personnel."')";
	$rs=$db->query($sql);
}


?>
<script language="javascript">
function incidentChange(option){
	if(option=="personnel"){
		document.getElementById('personnel').disabled=false;	
		document.getElementById('name_of_personnel').disabled=false;	

	}
	else {
		document.getElementById('personnel').disabled=true;	
		document.getElementById('name_of_personnel').disabled=true;	
	}

}
</script>
<a href='monitoring menu.php'>Go Back to Monitoring Menu</a>
<form action='passenger incident.php' method=post>
<table border=1>
<tr>
<th colspan=2>Passenger Incident/Complaint Report</th>
</tr>
<tr>
<td>Passenger Name</td>
<td><input type=text name='passenger' size=50 /></td>
</tr>
<tr>
<td>Description of Incident</td>
<td>
<select name='description' onchange='incidentChange(this.value)'>
	<option value='personnel'>Complaint Against Personnel</option>
	<option value='facility'>Complaint Against Facility/Equipment</option>
	<option value='incident'>Incident/Accident</option>
</select>
</td>
</tr>
<tr>
<td>Personnel Involved</td>
<td>
<select name='personnel' id='personnel'>
	<option value='ticket_seller'>Ticket Seller</option>
	<option value='guard'>Security Guard</option>
	<option value='cleaner'>Cleaner</option>
	<option value='train_driver'>Train Driver</option>
	<option value='tenant'>Tenant</option>
	<option value='other'>Other</option>
</select>
<input type=text name='name_of_personnel' id='name_of_personnel' size=50 />
</td>

</tr>

<tr>
<td>Station</td>
<td>
<select name='station'>
<?php
$db=new mysqli("localhost","root","","station");
$sql="select * from station";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<option value='<?php echo $row['id']; ?>'><?php echo $row['station_name']; ?></option>
<?php
}
?>
</select>
</td>
</tr>
<tr>
<td>Date and Time</td>
<td colspan=2>

<select name='month'>
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
<select name='day'>
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
<select name='year'>
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
<select name='hour'>
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
<select name='minute'>
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
<select name='amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>
</td>
</tr>
<tr><td>Details of Incident</td>
<td><textarea rows=5 cols=50 name='details' ></textarea></td>
</tr>
</tr>
<tr><td>Finding/Results of Investigation</td>
<td><textarea rows=5 cols=50 name='findings' ></textarea></td>
</tr>
</tr>

<tr><td>Action Taken</td>
<td><textarea rows=5 cols=50 name='action' ></textarea></td>
</tr>
</tr>
<tr><td>Recommendation</td>
<td><textarea rows=5 cols=50 name='recommendation' >Immediate technical investigation and prompt reply to the passenger</textarea></td>
</tr>
<tr>
<td colspan=2 align=center><input type=submit value='Submit' /></td>
</tr>
</table>
</form>
<?php
/*
 $z = 34;
$year = 2012;
$timestamp = mktime(0,0,0,1,$z, $year);
echo date("r", $timestamp);  
*/
?>