<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['incident_index'])){
	$element=$_POST['formElement'];

	if(($_POST['formElement']=="reported_cc")||($_POST['formElement']=="repair_time")){
		$prefix="";
		if($element=="reported_cc"){ $prefix="cc_"; } else  { $prefix="repair_"; }
		
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
			
			$update="update repair set ".$element."='".$availability_date."' where id='".$_POST['incident_index']."'";
			$updateRS=$db->query($update);	
		
			
		
		
		
	}	
	else if($element=="e_incident_id"){
		$update="update repair set ".$element."='".$_POST["equipt"]."' where id='".$_POST['incident_index']."'";
		$updateRS=$db->query($update);	
	
	}
	else {
		$update="update repair set ".$element."='".$_POST[$element]."' where id='".$_POST['incident_index']."'";
		$updateRS=$db->query($update);	
	
	
	}
	

}


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

<script language='javascript' src='ajax.js'></script>
<script language='javascript'>

function deleteRow(index,table){
	var check=confirm("Remove Record?");
	if(check){
		makeajax("processing.php?removeRow="+index+"&tableType="+table,"reloadPage");	
	}
}

function reloadPage(ajaxHTML){
	self.location="repair_facilities_monitoring.php";
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
function fillEdit(element,incident_id,reference_id){
	var elementHTML="<br>";
	elementHTML+="<form action='repair_facilities_monitoring.php' method='post' >";
	elementHTML+="<table style='border:1px solid red'>";
	elementHTML+="<tr>";
	elementHTML+="<td>Reference ID</td>";
	elementHTML+="<td>"+reference_id+"</td>";
	
	elementHTML+="</tr>";		
	elementHTML+="<tr>";
	elementHTML+="<td>Enter "+element.toUpperCase()+"</td>";
	if((element=="reported_cc")||(element=="repair_time")){
		
		var prefix="";
		if(element=="reported_cc"){ prefix="cc_"; }
		else if(element=="repair_time"){ prefix="repair_"; }
		
		
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
	else if(element=="e_incident_id"){
		elementHTML+="<td>&nbsp;</td>";
		
	}
	else if(element=="station"){
		
		elementHTML+="<td id='stationFill' name='stationFill'></td>";
		
	}		
	
	else {
		elementHTML+="<td><input type=text name='"+element+"' /></td>";	
	}
	elementHTML+="</tr>";

	
	if(element=="e_incident_id"){
	
		elementHTML+="<tr>";
		elementHTML+="<th>Record No.</th><td id='record_no' name='record_no' ></td>";
		elementHTML+="</tr>";
		elementHTML+="<tr>";
		elementHTML+="<th>Incident No.</th><td id='incident_no' name='incident_no'></td>";
		elementHTML+="</tr>";
		elementHTML+="<tr>";
		elementHTML+="<th>Details</th><td id='problem_details' name='problem_details'></td>";
		elementHTML+="</tr>";	
		elementHTML+="<tr>";
		elementHTML+="<td align=center colspan=2>";
		elementHTML+="<a href='#' onclick=\"window.open('search_facility.php','_blank')\">Link Defective Facility/Equipment</a>";

		elementHTML+="<input type=hidden name='equipt' id='equipt' />";
		elementHTML+="</td>";
		elementHTML+="</tr>";
		
	
	}
	
	
	elementHTML+="<tr>";
	elementHTML+="<td colspan=2 align=center>";
	elementHTML+="<input type=hidden name='formElement' id='formElement' value='"+element+"' />";	
	elementHTML+="<input type='hidden' name='incident_index' value='"+incident_id+"' />";
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



<br>
<br>
<form action='repair_facilities_monitoring.php' method='post'>
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
<input type=submit value='Get Records' />
</form>
<br>
<br>
<?php
if(isset($_SESSION['month'])){
	$year=$_SESSION['year'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];

	$monitoring_date=date("Y-m-d",strtotime($year."-".$month."-".$day));	

	$monitoring_name=date("F d, Y",strtotime($year."-".$month."-".$day));

}
if(isset($_POST['month'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$monitoring_date=date("Y-m-d",strtotime($year."-".$month."-".$day));	

	$_SESSION['year']=$year;
	$_SESSION['month']=$month;
	$_SESSION['day']=$day;
	
	$monitoring_name=date("F d, Y",strtotime($year."-".$month."-".$day));

	
}

if($year==""){
}
else {


	
?>
<table width=100% style='border:1px solid gray' id='menu'>
<tr id='selectLogbook'>
<td width=50%>
Date: 
<?php
echo $monitoring_name;
?>
</td>
</tr>
</table>
<br>
<table width=100% border=1px style='border-collapse:collapse;' class='logbookTable'>
<tr>
	<th rowspan=2>Reference ID</th>

	<th rowspan=2>Station</th>
	<th rowspan=2>SS</th>
	<th colspan=3>PROBLEMS ENCOUNTERED</th>
	<th colspan=2>Reported to CC</th>
	<th colspan=2>Repaired as of</th>
	<th rowspan=2>Repair No.</th>
	<th rowspan=2>Remarks</th>
</tr>	
<tr>
	<th>Record No.</th>
	<th>Incident No.</th>
	<th>Details</th>
	
	<th>Date</th>
	<th>Time</th>

	<th>Date</th>
	<th>Time</th>

</tr>
<?php
$db=new mysqli("localhost","root","","station");

$sql="select * from repair where date like '".$monitoring_date."%%'";

$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$reference_stamp=date("Ymd");
	
	for($i=0;$i<$nm;$i++){
		$reference_id=$reference_stamp."_".$i;		

	
		$row=$rs->fetch_assoc();
		$station=$row['station'];
		$ss=$row['ss'];
		$reported_cc=$row['reported_cc']; 
		$repair_time=$row['repair_time'];
		$repair_no=$row['repair_no'];

		$remarks=$row['remarks_repair'];
		
		if($reported_cc=="0000-00-00 00:00:00"){
			$cc_timestamp="";
			$cc_datestamp="";
		}
		else {
			$cc_timestamp=date("Hi",strtotime($reported_cc));
			$cc_datestamp=date("Y-m-d",strtotime($reported_cc));

		}
		
		if($repair_time=="0000-00-00 00:00:00"){
			$repair_timestamp="";
			$repair_datestamp="";

		}
		else {
			$repair_timestamp=date("Hi",strtotime($repair_time));
			$repair_datestamp=date("Y-m-d",strtotime($repair_time));

		}
	
		$equipment_incident_id=$row['e_incident_id'];

	
		$record="";
		$incident_no="";
		
		$incident_details="";

		
		$sql2="select * from issued_no where e_incident_id='".$equipment_incident_id."' limit 1";

		$rs2=$db->query($sql2);
		$nm2=$rs2->num_rows;
		
		if($nm2>0){
			$row2=$rs2->fetch_assoc();
			
			$record=$row2['record'];
			$incident_no=$row2['incident_cdc'];
		
		
		}

		$sql3="select * from equipment_incident where id='".$equipment_incident_id."' limit 1";
		$rs3=$db->query($sql3);
		$nm3=$rs3->num_rows;
		
		if($nm3>0){
			$row3=$rs3->fetch_assoc();
			
			$incident_details=$row3['details'];	

		
		
		}


		
?>		
		<tr>		
			<td align=center><?php echo $reference_id; ?></td>

			<td align=center><?php echo $station; ?> <a href='#' onclick="fillEdit('station','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $ss; ?> <a href='#' onclick="fillEdit('ss','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $record; ?> <a href='#' onclick="fillEdit('e_incident_id','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $incident_no; ?> <a href='#' onclick="fillEdit('e_incident_id','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $incident_details; ?> <a href='#' onclick="fillEdit('e_incident_id','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			
			
			<td align=center><?php echo $cc_datestamp; ?> <a href='#' onclick="fillEdit('reported_cc','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $cc_timestamp; ?> <a href='#' onclick="fillEdit('reported_cc','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			
			<td align=center><?php echo $repair_datestamp; ?> <a href='#' onclick="fillEdit('repair_time','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $repair_timestamp; ?> <a href='#' onclick="fillEdit('repair_time','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>

			<td align=center><?php echo $repair_no; ?> <a href='#' onclick="fillEdit('repair_no','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $remarks; ?> <a href='#' onclick="fillEdit('remarks_repair','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a> </td>
			<td><a href='#' onclick="deleteRow('<?php echo $row['id']; ?>','repair_equipment')">X</a></td>
		</tr>
<?php		
	}
}
?>		
</table>
<div id='fillIncident' name='fillIncident'></div>
<br>
<br>
<a href='#' onclick="window.open('repair_facilities_entry.php?dd=<?php echo date("Y-m-d",strtotime($monitoring_date)); ?>','_blank')">Add Repair Entry</a>
<?php
}
?>


