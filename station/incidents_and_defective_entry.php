<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
require("db.php");
?>
<?php
$db=retrieveDb();
if(isset($_POST['category'])){
	$category=$_POST['category'];
	$station=$_POST['station'];
	$details=$_POST['details'];
		
	$record_no=$_POST['record'];	
	$incident_no=$_POST['incident'];	
	
	$remarks=$_POST['remarks'];
	
	$reported_office=$_POST['reported_office'];
	$reported_personnel=$_POST['reported_personnel'];
	
	$first_year=$_POST['ss_year'];
	$first_month=$_POST['ss_month'];
	$first_day=$_POST['ss_day'];
	
	$first_hour=$_POST['ss_hour'];
	$first_minute=$_POST['ss_minute'];
	$first_amorpm=$_POST['ss_amorpm'];

	if($first_amorpm=="pm"){
		if($first_hour<12){
			$first_hour+=12;
			
		}
		else {
		}
	}
	else {
		if($first_hour=="12"){
			$first_hour=0;
			
		}
	
	}
	
	$ss_date=date("Y-m-d H:i",strtotime($first_year."-".$first_month."-".$first_day." ".$first_hour.":".$first_minute));
	
	$first_year=$_POST['cc_year'];
	$first_month=$_POST['cc_month'];
	$first_day=$_POST['cc_day'];
	
	$first_hour=$_POST['cc_hour'];
	$first_minute=$_POST['cc_minute'];
	$first_amorpm=$_POST['cc_amorpm'];

	if($first_amorpm=="pm"){
		if($first_hour<12){
			$first_hour+=12;
		}
		else {
		}
	}
	else {
		if($first_hour=="12"){
			$first_hour=0;
		}
	}
	
	$cc_date=date("Y-m-d H:i",strtotime($first_year."-".$first_month."-".$first_day." ".$first_hour.":".$first_minute));

	$equipt=$_POST['equipt'];	
	$monitoring_date=$_POST['monitoring_date'];
	
	$update="insert into equipment_incident(incident_type,details,equipt,remarks,station,time_ss,time_cc,date) ";
	$update.=" values ";
	$update.="('".$category."',\"".$details."\",'".$equipt."',\"".$remarks."\",'".$station."','".$ss_date."','".$cc_date."','".$monitoring_date."')";
	
	$updateRS=$db->query($update);

	$incident_id=$db->insert_id;

	if($equipt=="33"){
		$update="update equipment_incident set alt_equipt='".$_POST['alt_equipt']."' where id='".$incident_id."'";
		$updateRS=$db->query($update);
	}
	
	$update="insert into issued_no(record,incident_cdc,e_incident_id) values ('".$record_no."','".$incident_no."','".$incident_id."')";
	$updateRS=$db->query($update);
	
	$update="insert into reported_to(office,personnel,e_incident_id) values ('".$reported_office."','".$reported_personnel."','".$incident_id."')";
	$updateRS=$db->query($update);

	echo "<script language='javascript'>";
	echo "window.opener.location='incidents_and_defective_monitoring.php';";
	echo "</script>";
}

if(isset($_GET['dd'])){
	$monitoring_date=$_GET['dd'];
}
?>
<script language='javascript' src='ajax.js'></script>
<script language='javascript'>
function calculateMachineNo(){
	var station_id="";
	var equipt="";
	
	station_id=document.getElementById('station').value;
	equipt=document.getElementById('equipt').value;

	document.getElementById('othersFill').innerHTML="";	

	
	if((equipt=="1")||(equipt=="2")||(equipt=="3")||(equipt=="8")||(equipt=="9")||(equipt=="10")){
		makeajax("processing.php?equipt_machine=Y&station_id="+station_id+"&equipt_id="+equipt,"getMachineNo");
	}
	else if((equipt=="6")||(equipt=="7")){
	
		makeajax("processing.php?equipt_machine_b=Y&station_id="+station_id+"&equipt_id="+equipt,"getMachineNo");
	
	}
	else if(equipt=="4"){
		var eHTML="<select name='machine_no'>";
		eHTML+="<option value='"+station_id+"'>"+station_id+"</option>";
		eHTML+="</select>";
		
		document.getElementById('machineFill').innerHTML=eHTML;	
	}
	else {
		//document.getElementById('machineFill').innerHTML="<input type=text name='machine_no' size=5 />";	
		if(equipt=="33"){
			document.getElementById('othersFill').innerHTML="<input type='text' name='alt_equipt' id='alt_equipt' />";
		}
		else {
		}
		document.getElementById('machineFill').innerHTML="";	
	
	}

}

function getMachineNo(ajaxHTML){
	var driverHTML="";
	if(ajaxHTML=="None available"){
	}
	else {
		driverHTML="<select name='machine_no' id='machine_no'>";

		var driverTerms=ajaxHTML.split("==>");
		var count=(driverTerms.length)*1-1;
		
		for(var n=0;n<count;n++){
			var parts=driverTerms[n].split(";");
			driverHTML+="<option value='"+parts[0]+"'>";
			driverHTML+=parts[1];
			driverHTML+="</option>";
		}
		driverHTML+="</select>";
	}
	document.getElementById('machineFill').innerHTML=driverHTML;	
	
}

</script>
<link rel="stylesheet" href="layout/styles.css" />
<link rel="stylesheet" href="layout/bodyEntry.css" />

<form action='incidents_and_defective_entry.php' method='post'>
<table class="EntryTableCLC" align="center" width="40%" ><tr><td>
<table class="miniHolderCLC">
<tr>
	<th class="HeaderCLC" colspan="2">Add - Repair Entry</th>
</tr>

<tr>
<td>Category</td>
<td>
<select name='category'>
<!--
<option value='persons'>Person Incident</option>
<option value='ticket'>Ticket Encoding Error</option>

//Person Incident encoded from incident report
//Ticket Encoding Error encoded from TEEMR
-->
<option value='facilities'>Defective Facilities</option>
<option value='equipment'>Defective Equipment</option>
</select>
</td>
</tr>
<tr>
<td>Station</td>
<td>
<select name='station' id='station' onchange='calculateMachineNo()'>
<option></option>
<?php
$db=retrieveDb();
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
<td>Equipment/Facility</td>
<td>
<select name='equipt' id='equipt' onchange='calculateMachineNo()'>
<?php
$sql="select * from equipt";
$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<option value='<?php echo $row['id']; ?>'><?php echo $row['equipment']; ?></option>


<?php
}
?>



</select> 
 <span id='othersFill' name='othersFill'></span>
 <span id='machineFill' name='machineFill'></span>


<!--
<input type=text name='equipt' />
-->
</td>
</tr>

<tr>
<th class="HeaderCLC" colspan="2">Time</th>
</tr>

<tr>
<td>By SS</td>
<td>
<select name='ss_month'>
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
<select name='ss_day'>
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
<select name='ss_year'>
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
<select name='ss_hour'>
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
<select name='ss_minute'>
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
<select name='ss_amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>
</td>
</tr>
<tr>
<td>By/To CC</td>
<td>
<select name='cc_month'>
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
<select name='cc_day'>
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
<select name='cc_year'>
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
<select name='cc_hour'>
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
<select name='cc_minute'>
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
<select name='cc_amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>
</td>
</tr>
<tr>
<td>Details of the Report</td>
<td><textarea rows=5 cols=50 name='details' placeholder="Details of the Report"></textarea></td>
</tr>
<tr>
<th class="HeaderCLC" colspan="2">Issued Numbers:</th>
</tr>
<tr>
<td>Record No.</td>
<td><input type=text name='record' placeholder="Record No."/></td>
</tr>
<tr>
<td>Incident No.</td>
<td><input type=text name='incident' placeholder="Incident No."/></td>
</tr>
<tr>
<th class="HeaderCLC" colspan="2">Reported/Coordinated To:</th>
</tr>
<tr>
<td>Office</td>
<td><input type=text name='reported_office' placeholder="Office"/></td>
</tr>
<tr>
<td>Personnel</td>
<td><input type=text name='reported_personnel' placeholder="Personnel"/></td>
</tr>

<tr>
<td>Remarks</td>
<td>
<textarea rows=5 cols=50 name='remarks' placeholder="Remarks"></textarea>
</td>
</tr>
</table>
<?php
if($monitoring_date==""){ }
else {
?>
<tr>
	<td class="EntrySubmitCLC" align=center>
		<input type=submit value='Submit' />
		<input type=hidden name='monitoring_date' value='<?php echo $monitoring_date; ?>' />
	</td>
</tr>
<?php
}
?>
</table>
</form>

