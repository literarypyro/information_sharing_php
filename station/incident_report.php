<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['incident_month'])){
	$year=$_POST['incident_year'];
	$month=$_POST['incident_month'];
	$day=$_POST['incident_day'];
	
	$hour=$_POST['incident_hour'];
	$minute=$_POST['incident_minute'];
	$amorpm=$_POST['incident_amorpm'];

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
	$location=$_POST['location'];
	$persons=$_POST['persons'];
	$nature=$_POST['nature'];
	$circumstances=$_POST['circumstances'];
	$endorsed_office=$_POST['endorsed_office'];
	$endorsed_person=$_POST['endorsed_person'];
	$cctv=$_POST['cctv'];
	$contact_no=$_POST['contact_no'];
	
	$update="insert into incident(incident_time,incident_location,persons,nature_incident,circumstances,endorsed_office,endorsed_person,cctv,contact_no) values ";
	$update.="('".$incident_date."','".$location."','".$persons."','".$nature."',\"".$circumstances."\",'".$endorsed_office."','".$endorsed_person."',\"".$cctv."\",'".$contact_no."')";
	
	$update_rs=$db->query($update);
	
	$incident_id=$db->insert_id;	

	if($nature=="7"){
		$update="update incident set alt_nature='".$_POST['alt_nature']."' where id='".$incident_id."'";
		$update_rs=$db->query($update);
	}	
		
	$general_year=$_POST['general_year'];
	$general_month=$_POST['general_month'];
	$general_day=$_POST['general_day'];
	
	$general_hour=$_POST['general_hour'];
	$general_minute=$_POST['general_minute'];
	$general_amorpm=$_POST['general_amorpm'];

	if($general_amorpm=="pm"){
		if($general_hour<12){
			$general_hour+=12;
		}
		else {
		}
	}
	else {
		if($general_hour=="12"){
			$general_hour=0;
		}
	}	
	
	$general_date=date("Y-m-d H:i",strtotime($general_year."-".$general_month."-".$general_day." ".$general_hour.":".$general_minute));
	$supervisor=$_POST['supervisor'];
	$station=$_POST['station'];
	
	$update="insert into report(supervisor,station,report_time,incident_id) values ('".$supervisor."','".$station."','".$general_date."','".$incident_id."')";
	$updateRS=$db->query($update);
	
	$action_supervisor=$_POST['action_supervisor'];
	$action_base=$_POST['action_base'];

	$recom_supervisor=$_POST['recom_supervisor'];
	$recom_base=$_POST['recom_base'];
	
	$update="insert into action_taken(supervisor,base,recom_supervisor,recom_base,incident_id) values ('".$action_supervisor."','".$action_base."','".$recom_supervisor."','".$recom_base."','".$incident_id."')";
	$updateRS=$db->query($update);

	//$incident_update="insert into equipment_incident(incident_type,details,station,date) values ('persons',\"".$details."\",'".$station."','".$general_date."')";
	//$incident_update_rs=$db->query($incident_update);
	//$monitoring_id=$db->insert_id;

	//$union_update="insert into incident_to_monitoring(incident_id,monitoring_id) values ('".$incident_id."','".$monitoring_id."')";
	//$union_update_rs-$db->query($union_update);

	echo "<script language='javascript'>";
	echo "window.opener.location.reload();";
	echo "</script>";
}
?>
<script language='javascript'>
function checkOthers(){
	var nature=document.getElementById('nature').value;

	document.getElementById('othersFill').innerHTML="";

	if(nature=="7"){
		document.getElementById('othersFill').innerHTML="<input type='text' name='alt_nature' id='alt_nature' />";
	}
}
</script>
<link href="layout/landbank/control slip.css" rel="stylesheet" type="text/css"  id='stylesheet' />

<form action='incident_report.php' method='post'>
<table style='border:1px solid gray' class='controlTable'>
<tr>
<td>Station Supervisor</td>
<td><input type=text name='supervisor' size=50 /></td>
</tr>
<tr>
<td>Station Name</td>
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
<td>Date/Time:</td><td>
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
</table>
<br>
<table style='border:1px solid gray' width='570' class='controlTable'>
<tr>
<td>Time of Incident</td>
<td>
<select name='incident_month'>
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
<select name='incident_day'>
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
<select name='incident_year'>
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
<select name='incident_hour'>
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
<select name='incident_minute'>
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
<select name='incident_amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>


</td>
</tr>
<tr>
<td>Location of Incident</td>
<td><input type=text name='location' size=50 /></td>
</tr>
<tr>
<td>Contact Number</td>
<td><input type=text name='contact_no' /></td>
</tr>

<tr>
<td>Person/s Involved</td>
<td><textarea name='persons' rows=5 cols=50  ></textarea></td>
</tr>
<tr>
<td>Nature of Incident</td>
<td>
<select name='nature' id='nature' onchange='checkOthers()'>

<?php
$db=new mysqli("localhost","root","","station");

$sql="select * from nature_incident";
$rs=$db->query($sql);

$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<option value='<?php echo $row['id']; ?>'><?php echo $row['description']; ?></option>

<?php
}
?>
</select> 
<span id='othersFill' name='othersFill'></span>
</td>
</tr>
<tr>
<td>Circumstances</td>
<td><textarea name='circumstances' rows=5 cols=50  ></textarea></td>
</tr>
<tr>
<td>Observation on the CCTV Footage</td>
<td><textarea name='cctv' rows=5 cols=50  ></textarea></td>
</tr>

<tr>
<th colspan=2>Endorsed To:</th>
</tr>
<tr>
<td>Office</td>
<td><input type=text name='endorsed_office' size=50 /></td>
</tr>
<tr>
<td>Person</td>
<td><input type=text name='endorsed_person' size=50 /></td>
</tr>
</table>
<br>
<table style='border:1px solid gray' width=570 class='controlTable'>
<tr><th colspan=2>Action Taken</th></tr>
<tr>
<td>Action Taken by Supervisor</td>
<td><textarea name='action_supervisor' rows=5 cols=50  ></textarea></td>
</tr>
<tr>
<td>Action Taken by Station Base</td>
<td><textarea name='action_base' rows=5 cols=50  ></textarea></td>
</tr>
<tr>
<td>Recommendation of Supervisor</td>
<td><textarea name='recom_supervisor' rows=5 cols=50  ></textarea></td>
</tr>
<tr>
<td>Recommendation of Station Base</td>
<td><textarea name='recom_base' rows=5 cols=50  ></textarea></td>
</tr>
</table>
<table width=570>
<tr>
<td align=center><input type=submit value='Submit' /></td>

</table>
</form>
