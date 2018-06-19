<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
if(isset($_POST['incident'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];	
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
	$equipt_date=$year."-".$month."-".$day." ".$hour.":".$minute;
	
	$ayear=$_POST['repair_year'];
	$amonth=$_POST['repair_month'];
	$aday=$_POST['repair_day'];	
	$ahour=$_POST['repair_hour'];
	$aminute=$_POST['repair_minute'];
	$aamorpm=$_POST['repair_amorpm'];
	if($aamorpm=='pm'){
		$ahour+=(12*1);
		if($ahour>=24){
			$ahour=0;
		}
	}
	else {
		$ahour=$ahour;
		
	}		

	$repair_date=$ayear."-".$amonth."-".$aday." ".$ahour.":".$aminute;	
	$db=new mysqli("localhost","root","","station");
	$incident=$_POST['incident'];
	if($incident=='facility'){
		$equipment=$_POST['equipment'];	
	
	}
	else {
		$equipment="";
	
	}
	
	if($equipment=="others"){
		$others=$_POST['other_equipt'];
	}
	else {
		$others="";
	}
	$reference_id=$_POST['reference_id'];
	$incident_no=$_POST['incident_no'];
	
	$station=$_POST['station'];
	$details=$_POST['details'];
	$status=$_POST['status'];
	
	$sql="insert into equipment_incident";
	$sql.="(incident_type,equipt,other,reference_id,incident_number,";
	$sql.="station,date,details)";
	$sql.=" values ";
	$sql.="('".$incident."','".$equipment."','".$others."','".$reference_id."'";
	$sql.=",'".$incident_no."','".$station."','".$equipt_date."','".$details."')";
	$rs=$db->query($sql);
	$equipment_id=$db->insert_id;
	
	
	if($_POST['repairToggle']=="true"){
		$update="insert into equipment_repair(incident_id,date_repaired) values ";
		$update.="('".$equipment_id."','".$repair_date."')";
		$urs=$db->query($update);
	
	}
}	
?>
<script language='javascript'>
function equipmentEnable(equipt){
	var optionClause="";
	
	if(equipt=="facility"){
		document.getElementById('equipment').disabled=false;
	
		optionClause="<option value='ad'>A/D</option>";
		optionClause+="<option value='ag'>AG</option>";
		optionClause+="<option value='tim'>TIM</option>";
		optionClause+="<option value='scs'>SCS</option>";
		optionClause+="<option value='other'>other</option>";

		document.getElementById('equipment').innerHTML=optionClause;
	
	}
	else if(equipt=="ticket"){
		document.getElementById('equipment').disabled=true;
		
	}
	else if(equipt=="equipt"){
		document.getElementById('equipment').disabled=false;

		optionClause="<option value='escalator'>Escalator</option>";
		optionClause+="<option value='elevator'>Elevator</option>";
		optionClause+="<option value='cctv'>CCTV</option>";
		optionClause+="<option value='lighting'>Lighting</option>";
		optionClause+="<option value='tiles'>Tiles</option>";
		optionClause+="<option value='pa_system'>PA System</option>";
		optionClause+="<option value='acu'>ACU</option>";

		document.getElementById('equipment').innerHTML=optionClause;
	}

}
function otherEquipt(option){
	if(option=="other"){
		document.getElementById('other_equipt').disabled=false;	
	}
	else {
		document.getElementById('other_equipt').disabled=true;		
	}

}
function toggleRepair(check){
	if(check.checked==true){
		document.getElementById('repairToggle').value='true';
	}
	else {
		document.getElementById('repairToggle').value='false';
	
	}

}

</script>
<a href='monitoring menu.php'>Go Back to Monitoring Menu</a>
<form action='equipment incident.php' method='post'>
<table border=1>
<tr>
<th colspan=2>Equipment Incident Report</th>
</tr>
<tr>
<td> 
<select name='incident' id='incident' onchange='equipmentEnable(this.value)'>
<option value='facility'>Facility Problem</option>
<option value='equipt'>Equipment Problem</option>
<option value='ticket'>Ticket Encoding Error</option>
</select>
</td>
<td>
<select name='equipment' id='equipment' onchange='otherEquipt(this.value)'>
<option value='ad'>A/D</option>
<option value='ag'>AG</option>
<option value='tim'>TIM</option>
<option value='scs'>SCS</option>
<option value='other'>other</option>
</select>
<input type=text name='other_equipt' id='other_equipt' disabled />
</td>
<tr>
<td>Machine No. (leave<br> blank if none)</td>
<td>

<input type=text name='reference_id'  /></td>
</tr>

<tr>
<td>Incident Number</td>
<td>
<input type=text name='incident_no' />

<!--
<input type=text name='description' size=60 />
-->
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
for($n=0;$n<$nm;$n++){
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
<tr>
<td align=center colspan=2><input type=checkbox checked onclick='toggleRepair(this)' />Repaired<input type=hidden name='repairToggle' id='repairToggle' value='true' /></td>
</tr>
<tr>
<td>Date:</td><td>

<select name='repair_month'>
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
<select name='repair_day'>
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
<select name='repair_year'>
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
</td><tr>
<tr><td>Time: </td><td>
<select name='repair_hour'>
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
<select name='repair_minute'>
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
<select name='repair_amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>
</td>


</tr>
<tr>
<td colspan=2 align=center><input type=submit value='Submit' /></td>
</table>
</form>