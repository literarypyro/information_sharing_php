<?php
session_start();
?>
<?php
$db=new mysqli("localhost","root","","station");
?>
<link href="layout/landbank/logbook style.css" rel="stylesheet" type="text/css"  id='stylesheet' />

<?php
require("monitoring menu.php");
?>

<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['incident_index'])){
	$element=$_POST['formElement'];

	if($_POST['formElement']=="incident_time"){
			$prefix="";
			$prefix="incident_";
			
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
			
			$update="update incident set ".$element."='".$availability_date."' where id='".$_POST['incident_index']."'";
		$updateRS=$db->query($update);	
		
	}
	else {
		$update="update incident set ".$element."='".$_POST[$element]."' where id='".$_POST['incident_index']."'";
		$updateRS=$db->query($update);	
	
		if($element=="nature_incident"){
			if($_POST[$element]=="7"){
				$update="update incident set alt_nature='".$_POST['alt_nature']."' where id='".$_POST['incident_index']."'";
				$updateRS=$db->query($update);	
				
			}
		}	
	
	
	}
	
	
	
	
	
	
}


$sql="select * from nature_incident";
$rs=$db->query($sql);

$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$nature[$row['id']]=$row['description'];

}
?>
<script language='javascript' src='ajax.js'></script>
<script language='javascript'>

function deleteRow(index,table){
	var check=confirm("Remove Record?");
	if(check){
		makeajax("processing.php?removeRow="+index+"&tableType="+table,"reloadPage");	
	}
}

function reloadPage(ajaxHTML){
	self.location="incident_summary.php";


}

function getNature(){
	makeajax("processing.php?nature_incident=Y","fillNature");	
}

function checkOthers(){
	var nature=document.getElementById('nature_incident').value;
	
	document.getElementById('othersFill').innerHTML="";
	
	if(nature=="7"){
		document.getElementById('othersFill').innerHTML="<input type='text' name='alt_nature' id='alt_nature' />";
	
	}
}
function fillNature(ajaxHTML){
	var driverHTML="";
	if(ajaxHTML=="None available"){
	}
	else {

		driverHTML="<select name='nature_incident' id='nature_incident' onchange='checkOthers()'>";

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
	
	driverHTML+=" <span name='othersFill' id='othersFill'></span>";
	
	
	
	document.getElementById('natureFill').innerHTML=driverHTML;
}



function fillEdit(element,incident_id,reference_id){
	var elementHTML="<br>";
	elementHTML+="<form action='incident_summary.php' method='post' >";
	elementHTML+="<table style='border:1px solid red'>";
	elementHTML+="<tr>";
	elementHTML+="<td>Reference ID</td>";
	elementHTML+="<td>"+reference_id+"</td>";
	
	elementHTML+="</tr>";		
	elementHTML+="<tr>";
	elementHTML+="<td>Enter "+element.toUpperCase()+"</td>";
	
	if(element=="incident_time"){
		
		var prefix="incident_";
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
	else if(element=="nature_incident"){
		elementHTML+="<td name='natureFill' id='natureFill'></td>";	
	}	
	else {
		elementHTML+="<td><input type=text name='"+element+"' /></td>";	
	}
	elementHTML+="</tr>";

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
	getNature();
}

</script>


<br>
<br>
<form action='incident_summary.php' method='post'>
<?php
$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");
?>


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
<input type=submit value='Get Records' />
</form>
<br>
<br>
<?php
if(isset($_SESSION['month'])){
	
	$station=$_SESSION['station'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];
	$year=$_SESSION['year'];

	
	
	$daily_date=date("Y-m-d",strtotime($year."-".$month."-".$day));
	$daily_name=date("F d, Y",strtotime($daily_date));
	
	$sql="select * from station where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];	
}


?>
<?php

if(isset($_POST['year'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$station=$_POST['station'];
	
	$daily_date=date("Y-m-d",strtotime($year."-".$month."-".$day));
	$daily_name=date("F d, Y",strtotime($daily_date));
	
	$_SESSION['station']=$station;
	$_SESSION['month']=$_POST['month'];
	$_SESSION['day']=$_POST['day'];
	$_SESSION['year']=$_POST['year'];	
	
}

?>
<table width=100% style='border:1px solid gray' id='menu'>
<tr id='selectLogbook'>
<td width=50%>
Station:
<?php
echo $station_name;
?>
</td>
<td width=50%>
Date: 
<?php
echo $daily_name;
?>
</td>
</tr>
</table>
<br>
<table width=100% border=1px style='border-collapse:collapse;' class='logbookTable'>
<tr>
	<th>Reference ID</th>
	<th>Time</th>
	<th>Location of Incident</th>
	<th>Person/s Involved</th>
	<th>Nature of Incident(general)</th>
	<th>Circumstances of Incident</th>
	<th>Findings of CCTV Footage</th>

</tr>
<?php
if($year==""){
}
else {
	$search="select * from incident inner join report on incident.id=incident_id where station='".$station."' and report_time like '".$daily_date."%%'";

	$searchRS=$db->query($search);
	$searchNM=$searchRS->num_rows;
	$reference_stamp=date("Ymd");
	
	for($i=0;$i<$searchNM;$i++){
		$reference_id=$reference_stamp."_".$i;		

		$searchRow=$searchRS->fetch_assoc();
		$incident_time=date("Hi",strtotime($searchRow['incident_time']));
		$incident_location=$searchRow['incident_location'];
		$persons=$searchRow['persons'];
		if($searchRow['nature_incident']=="7"){
			$nature_incident=$searchRow['alt_nature'];
			
		}
		else {
			$nature_incident=$nature[$searchRow['nature_incident']];
		}

		$circumstances=$searchRow['circumstances'];
		
		$cctv=$searchRow['cctv'];
		
	?>
	<tr>
		<td><?php echo $reference_id; ?></td>
		<td align=center><?php echo $incident_time; ?> <a href='#' onclick="fillEdit('incident_time','<?php echo $searchRow['incident_id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $incident_location; ?> <a href='#' onclick="fillEdit('incident_location','<?php echo $searchRow['incident_id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $persons; ?> <a href='#' onclick="fillEdit('persons','<?php echo $searchRow['incident_id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $nature_incident; ?> <a href='#' onclick="fillEdit('nature_incident','<?php echo $searchRow['incident_id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $circumstances; ?> <a href='#' onclick="fillEdit('circumstances','<?php echo $searchRow['incident_id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $cctv; ?> <a href='#' onclick="fillEdit('cctv','<?php echo $searchRow['incident_id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>

		<td><a href='#' onclick="deleteRow('<?php echo $searchRow['incident_id']; ?>','incident_report')" >X</a></td>
		
	</tr>
<?php
	}
}
?>
</table>
<div id='fillIncident' name='fillIncident'></div>
<br>
<br>
<?php
if($year==""){
}
else {
?>
<a href='#' onclick="window.open('incident_report.php','_blank')">Add New Incident</a>
<?php
}
?>