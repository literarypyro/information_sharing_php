<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
require("db.php");
?>
<?php
$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");
?>
<?php
require("monitoring menu.php");
?>
<link href="layout/landbank/logbook style.css" rel="stylesheet" type="text/css"  id='stylesheet' />

<?php
$db=retrieveDb();if(isset($_POST['equipt_index'])){
	$element=$_POST['formElement'];	
	$equipt_id=$_POST['equipt_index'];
	$update="";
	$update.="update ";

	
	if(($element=="record")||($element=="incident_cdc")){
		$update.="issued_no ";	
	}
	else if(($element=="office")||($element=="personnel")){
		$update.="reported_to ";
	
	}
	else {
		$update.="equipment_incident ";
	}

	$update.=" set ";		
	
	if(($element=="time_ss")||($element=="time_cc")){
		
		$prefix="";

		if($element=="time_ss"){ $prefix="ss_"; }
		else if($element=="time_cc"){ $prefix="cc_"; }

		$year=$_POST[$prefix."year"];
		$month=$_POST[$prefix."month"];
		$day=$_POST[$prefix."day"];
	
		$hour=$_POST[$prefix.'hour'];
		$minute=$_POST[$prefix.'minute'];
		$amorpm=$_POST[$prefix.'amorpm'];		
			
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
			
		$availability_date=date("Y-m-d H:i",strtotime($year."-".$month."-".$day." ".$hour.":".$minute));
			
			
		$update.=$element."='".$availability_date."' ";
	
	}
	else {
		$update.=$element."='".$_POST[$element]."' ";
	}
	
	if(($element=="record")||($element=="incident_cdc")){
		$update.="where e_incident_id='".$equipt_id."' ";
	}
	else if(($element=="office")||($element=="personnel")){
		$update.="where e_incident_id='".$equipt_id."' ";
	
	}
	else {
		$update.="where id='".$equipt_id."' ";
	}
	


	$updateRS=$db->query($update);


	if(($element=="record")||($element=="incident_cdc")){
		$search="select * from issued_no where e_incident_id='".$equipt_id."' limit 1";
		$searchRS=$db->query($search);
		$searchNM=$searchRS->num_rows;
		
		$issued["record"]="";
		$issued["incident_cdc"]="";
		
		$issued[$element]=$_POST[$element];
		
		if($searchNM>0){
		}
		else {
			$update="insert into issued_no(record,incident_cdc,e_incident_id) values ('".$issued['record']."','".$issued['incident_cdc']."','".$equipt_id."')";
			$updateRS=$db->query($update);
		
		}
	}
	else if(($element=="office")||($element=="personnel")){
		$search="select * from reported_to where e_incident_id='".$equipt_id."' limit 1";
		$searchRS=$db->query($search);
		$searchNM=$searchRS->num_rows;
		
		$reported["office"]="";
		$reported["personnel"]="";
		
		$reported[$element]=$_POST[$element];
		
		if($searchNM>0){
		}
		else {
			$update="insert into reported_to(office,personnel,e_incident_id) values ('".$reported['office']."','".$reported['personnel']."','".$equipt_id."')";
			$updateRS=$db->query($update);
		
		}
	}
	
	
}
/*
$sql="select * from equipt";
$rs=$db->query($sql);

$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$equipt[$row['id']]=$row['equipment'];

}
*/



?>
<script language='javascript' src='ajax.js'></script>
<script language='javascript'>
function deleteRow(index,table){
	var check=confirm("Remove Record?");
	if(check){
		var check2=confirm("Deleting Record will also delete related Repair Entry.  Continue?");
		if(check2){
			makeajax("processing.php?removeRow="+index+"&tableType="+table,"reloadPage");	
		}
	}
}	

function reloadPage(ajaxHTML){
	self.location="incidents_and_defective_monitoring.php";
}

function getStation(){
	makeajax("processing.php?getStation=Y","fillStation");	
}

function fillStation(ajaxHTML){
	var driverHTML="";
	if(ajaxHTML=="None available"){
	}
	else {

		driverHTML="<select name='station' id='station'>";

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
	document.getElementById('stationFill').innerHTML=driverHTML;
}


function fillEdit(element,equipt_id,reference_id){
	var elementHTML="<br>";
	elementHTML+="<form action='incidents_and_defective_monitoring.php' method='post' >";
	elementHTML+="<table style='border:1px solid red'>";
	elementHTML+="<tr>";
	elementHTML+="<td>Reference ID</td>";
	elementHTML+="<td>"+reference_id+"</td>";
	
	elementHTML+="</tr>";		
	elementHTML+="<tr>";
	elementHTML+="<td>Enter "+element.toUpperCase()+"</td>";
	if((element=="time_ss")||(element=="time_cc")){
		
		
		var prefix="";
		
		if(element=="time_ss"){ prefix="ss_"; }
		else if(element=="time_cc"){ prefix="cc_"; }
		
		
		
		var d=new Date();
		
		var year=d.getFullYear();
		var mmonth=d.getMonth()*1+1;
		var day=d.getDate();
		
		var tentativehour=d.getHours();
		var minute=d.getMinutes();
		var hour=0;

		var amorpm="AM";
	
		if(tentativehour==0){
			hour=12;
			
			amorpm="AM";
		
		}
		else {
			if(tentativehour>12){
				hour=tentativehour-12;
				amorpm="PM";
			}
			else {
				hour=tentativehour;
				amorpm="AM";
			}
		
		}	
		
		elementHTML+="<td>";		
		elementHTML+="<select name='"+prefix+"month' id='"+prefix+"month'>";
		elementHTML+="<option></option>";
		for(var i=1;i<=12;i++){
			d=new Date(year+"-"+i+"-1");	
			var month="";

			switch(i){
				case 1: month='January'; break;
				case 2: month='February'; break;
				case 3: month='March'; break;
				case 4: month='April'; break;
				case 5: month='May'; break;
				case 6: month='June'; break;
				case 7: month='July'; break;
				case 8: month='August'; break;
				case 9: month='September'; break;
				case 10: month='October'; break;
				case 11: month='November'; break;
				case 12: month='December'; break;
			
			}
			
			elementHTML+="<option value='"+i+"' "; 
			
			if(mmonth==i){
			elementHTML+="selected";
			}
			elementHTML+=">";
			elementHTML+=month;
			elementHTML+="</option>";
			
		}
		elementHTML+="</select>";

		
		elementHTML+="<select name='"+prefix+"day' id='"+prefix+"day'>";
		elementHTML+="<option></option>";

		for(var i=1;i<=31;i++){
			elementHTML+="<option value='"+i+"' ";
			if(day==i){
			elementHTML+="selected";
			}
			elementHTML+=">"+i+"</option>";
		}
		
		elementHTML+="</select>";

		yearLimit=year*1+16;
		elementHTML+="<select name='"+prefix+"year' id='"+prefix+"year'>";
		elementHTML+="<option></option>";

		for(var i=1999;i<=yearLimit;i++){
			elementHTML+="<option value='"+i+"' ";
			if(year==i){
			elementHTML+="selected";
			}
			elementHTML+=">"+i+"</option>";
		}
		
		elementHTML+="</select>";
		
		
		
		elementHTML+="<select name='"+prefix+"hour'>";
		elementHTML+="<option></option>";
		
		
		for(var i=1;i<=12;i++){
			elementHTML+="<option value='"+i+"' ";
			if(hour==i){
				elementHTML+="selected";
			}
			elementHTML+=">"+i+"</option>";
		}
		elementHTML+="</select>";

		
		elementHTML+="<select name='"+prefix+"minute'>";
		elementHTML+="<option></option>";		
		var label="";
		for(var i=0;i<=59;i++){
			
			if(i<10){
				label="0"+i;			
			}
			else {
				label=i;
			}
			
			elementHTML+="<option value='"+i+"' ";
			if(minute==i){
			elementHTML+="selected";
			}
			elementHTML+=">"+label+"</option>";

		}
		elementHTML+="</select>";

		
		elementHTML+="<select name='"+prefix+"amorpm'>";	
		elementHTML+="<option></option>";
		elementHTML+="<option value='am' ";
		if(amorpm=="AM"){
			elementHTML+="selected";
		}
		elementHTML+=">AM</option>";

		elementHTML+="<option value='pm' ";
		if(amorpm=="PM"){
			elementHTML+="selected";
		}
		elementHTML+=">PM</option>";			
		
		elementHTML+="</select>";
		
		elementHTML+="</td>";
		
		
		
	}
	else if(element=="station"){
		
		elementHTML+="<td id='stationFill' name='stationFill'></td>";
		
	}	
	else {
		elementHTML+="<td><input type=text name='"+element+"' /></td>";
	}	
	
	
	
	elementHTML+="</tr>";		
	
	elementHTML+="<tr>";
	elementHTML+="<td colspan=2 align=center>";
	elementHTML+="<input type=hidden name='formElement' id='formElement' value='"+element+"' />";	
	elementHTML+="<input type='hidden' name='equipt_index' value='"+equipt_id+"' />";
	elementHTML+="<input type=submit value='Edit' />";
	elementHTML+="</td>"; 
	elementHTML+="</tr>";


	elementHTML+="</table>";
	elementHTML+="</form>";
	
	document.getElementById('fillIncident').innerHTML=elementHTML;	

	if(element=="station"){
		getStation();
	
	}


}
</script>

<link rel="stylesheet" href="layout/body.css" />
<link rel="stylesheet" href="layout/styles.css" />
<div class="PgTitle">
Defective Equipment/Facilities Monitoring
</div>
<form action='incidents_and_defective_monitoring.php' method='post'>
<ul class="SearchBar">
	<li>
<select name='month'>
<?php
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
	</li>
	<li>
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
	</li>
	<li>
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
	</li>

<?php
if(isset($_SESSION['month'])){
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];
	$year=$_SESSION['year'];

	$monitoring_date=date("Y-m-d",strtotime($year."-".$month."-".$day));	
	$monitoring_name=date("F d, Y",strtotime($year."-".$month."-".$day));

}

if(isset($_POST['month'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$monitoring_date=date("Y-m-d",strtotime($year."-".$month."-".$day));	
	$monitoring_name=date("F d, Y",strtotime($year."-".$month."-".$day));

	$_SESSION['month']=$_POST['month'];
	$_SESSION['day']=$_POST['day'];
	$_SESSION['year']=$_POST['year'];		
}	
?>	
	<li>
<input type=submit value='Get Records' />
	</li>
	<?php
	if($_SESSION['login_type']=="1"){
	?>
	<li style="float:right;">
		<input type="button" value="Add Defective Equipment/Facility" onclick="PasaCLC()" />
	</li>
	<?php
	}
	?>
	<li style="float:right;">
		<input type="button" value="Generate DR9" onclick="window.open('generate_dr9.php')" />
	</li>
</ul>
</form>
<hr class="PgLine"/>	
<?php	
if($year==""){
}
else {	
?>

<table class="TableCLC">
<tr>
	<th colspan="2" class="TableHeaderCLC">Date</th>
</tr>
<tr>
	<td class="col1CLC">Date</td>
	<td class="col2CLC"><?php echo $monitoring_name; ?></td>
</tr>
</table>


<table class="BigTableCLC">
<tr>
	<th rowspan=3>Reference ID</th>
	<th rowspan=3>Station</th>
	<th colspan=2>TIME</th>
	<th rowspan=2>DETAILS OF THE REPORT</th>
	<th colspan=2>Issued Numbers</th>
	<th colspan=2>Reported/Coordinated To:</th>
	<th rowspan=3>Remarks</th>
</tr>	
<tr>
	<th colspan=2>Reported/Coordinated</th>
	<th rowspan=2>Record</th>
	<th rowspan=2>Incident</th>
	
	<th rowspan=2>Office</th>
	<th rowspan=2>Personnel</th>
</tr>
<tr>
	<th>By SS</th>
	<th>By/To CC</th>
	<th>(Defective Equipment/Machine, Incident, Information)</th>
</tr>



<!--table width=100% border=1px style='border-collapse:collapse;' class='logbookTable'>
<tr>
	<th rowspan=3>Reference ID</th>
	<th rowspan=3>Station</th>
	<th colspan=2>TIME</th>
	<th rowspan=2>DETAILS OF THE REPORT</th>
	<th colspan=2>Issued Numbers</th>
	<th colspan=2>Reported/Coordinated To:</th>
	<th rowspan=3>Remarks</th>
</tr>	
<tr>
	<th colspan=2>Reported/Coordinated</th>
	<th rowspan=2>Record</th>
	<th rowspan=2>Incident</th>
	
	<th rowspan=2>Office</th>
	<th rowspan=2>Personnel</th>
</tr>
<tr>
	<th>By SS</th>
	<th>By/To CC</th>
	<th>(Defective Equipment/Machine, Incident, Information)</th>
</tr-->

<?php
$db=retrieveDb();
$sql="select * from equipment_incident where date like '".$monitoring_date."%%'";

$rs=$db->query($sql);
$nm=$rs->num_rows;
$reference_stamp=date("Ymd");
if($nm>0){
	for($i=0;$i<$nm;$i++){
		
		$reference_id=$reference_stamp."_".$i;			
		$row=$rs->fetch_assoc();
		$station=$row['station'];
		$details=$row['details'];
		$cc_date=$row['time_cc']; 
		$ss_date=$row['time_ss'];
		$remarks=$row['remarks'];
		
		if(($cc_date=="0000-00-00 00:00:00")||($cc_date=="")){
			$cc_time="";
		}
		else {
			$cc_time=date("Hi",strtotime($cc_date));
		}
		
		if(($ss_date=="0000-00-00 00:00:00")||($ss_date=="")){
			$ss_time="";
		}
		else {
			$ss_time=date("Hi",strtotime($ss_date));
		}
		
		$record="";
		$incident_no="";
		
		$sql2="select * from issued_no where e_incident_id='".$row['id']."' limit 1";
		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;
		
		if($nm2>0){
			$row2=$rs2->fetch_assoc();
			
			$record=$row2['record'];
			$incident_no=$row2['incident_cdc'];
		
		
		}

		$sql3="select * from reported_to where e_incident_id='".$row['id']."' limit 1";
		$rs3=$db->query($sql3);
		$nm3=$rs3->num_rows;
		
		if($nm3>0){
			$row3=$rs3->fetch_assoc();
			
			$reported_office=$row3['office'];
			$reported_personnel=$row3['personnel'];
		}

?>		
		<tr>	
			<td class="UnclickableCLC" align=center><?php echo $reference_id; ?></td>			
			<td class="ClickableCLC" <?php if($_SESSION['login_type']=="1"){ ?> onclick="fillEdit('station','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" <?php } ?> align=center><?php echo $station; ?></td>
			<td class="ClickableCLC" <?php if($_SESSION['login_type']=="1"){ ?>  onclick="fillEdit('time_ss','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" <?php } ?> align=center><?php echo $ss_time; ?></td>
			<td class="ClickableCLC" <?php if($_SESSION['login_type']=="3"){ ?>  onclick="fillEdit('time_cc','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" <?php } ?> align=center><?php echo $cc_time; ?></td>
			<td class="ClickableCLC" <?php if($_SESSION['login_type']=="1"){ ?>  onclick="fillEdit('details','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" <?php } ?> align=center><?php echo $details; ?></td>
			<td class="ClickableCLC" <?php if($_SESSION['login_type']=="3"){ ?>  onclick="fillEdit('record','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')"  <?php } ?> align=center><?php echo $record; ?></td>
			<td class="ClickableCLC" <?php if($_SESSION['login_type']=="3"){ ?>  onclick="fillEdit('incident_cdc','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')"  <?php } ?> align=center><?php echo $incident_no; ?></td>
			<td class="ClickableCLC" <?php if($_SESSION['login_type']=="1"){ ?>  onclick="fillEdit('office','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')"  <?php } ?>align=center><?php echo $reported_office; ?></td>
			<td class="ClickableCLC" <?php if($_SESSION['login_type']=="1"){ ?>  onclick="fillEdit('personnel','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" <?php } ?> align=center><?php echo $reported_personnel; ?></td>
			<td class="ClickableCLC" <?php if($_SESSION['login_type']=="1"){ ?>  onclick="fillEdit('remarks','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')"  <?php } ?>align=center><?php echo $remarks; ?></td>
			<td class="DeletableCLC"><a href='#'  <?php if($_SESSION['login_type']=="1"){ ?>  onclick="deleteRow('<?php echo $row['id']; ?>','defective_equipment')" <?php } ?>>X</a></td>
		</tr>
<?php		
	}
}
?>		
</table>
<br>
<div id='fillIncident' name='fillIncident'></div>

<?php
if($_SESSION['login_type']=="1"){
?>
<a href='#' style="display:none;" id="AddNewEntryCLC" onclick="window.open('incidents_and_defective_entry.php?dd=<?php echo date("Y-m-d",strtotime($monitoring_date)); ?>','_blank')">Add Defective Equipment/Facility</a>

<!--a href='#' onclick="window.open('generate_dr9.php')">Generate DR9</a-->
<?php
}
?>
<?php
}
?>