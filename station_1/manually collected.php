<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","station");
?>
<?php
require("monitoring menu.php");
?>
<?php
	if(isset($_POST['index_id'])){
		$update="update manually_collected set ";
		
		if(($_POST['formElement']=="first_use")||($_POST['formElement']=="last_use")){
			$prefix="";
			if($_POST['formElement']=="first_use"){
				$prefix="first_";
			}
			else if($_POST['formElement']=="last_use"){
				$prefix="last_";
			}
		
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
			
			$element=$prefix."use";
			
			$update.=$element."='".$availability_date."'";

		}
		else {
			$update.=" ".$_POST['formElement']."='".$_POST[$_POST['formElement']]."'";
		
		
		}
			
		$update.=" where id='".$_POST['index_id']."' "; 

		$updateRS=$db->query($update);

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
	self.location="manually collected.php";


}
function fillEdit(element,collected_id,reference_id){

	var elementHTML="<br>";
	elementHTML+="<form action='manually collected.php' method='post' >";
	elementHTML+="<table style='border:1px solid gray'>";
	
	if(element=="cash_assistant"){
		elementHTML+="<tr>";
		elementHTML+="<td>Enter "+element.toUpperCase()+"</td>";
		elementHTML+="<td name='cash_assist_fill' id='cash_assist_fill'>";
		elementHTML+="</td>";
		elementHTML+="</tr>";
			
			
	}
	else if(element=="type"){
		elementHTML+="<tr>";
		elementHTML+="<td>Enter "+element.toUpperCase()+"</td>";
		elementHTML+="<td>";
		elementHTML+="<select name='"+element+"'>";
		elementHTML+="<option value='sjt'>SJT</option>";
		elementHTML+="<option value='sjd'>DSJT</option>";
		elementHTML+="<option value='svt'>SVT</option>";
		elementHTML+="<option value='svd'>DSVT</option>";
		elementHTML+="</select>";
		elementHTML+="</td>";
		elementHTML+="</tr>";
			
			
	}
	else if((element=="entry")||(element=="exit")){
		elementHTML+="<tr>";
		elementHTML+="<td colspan=2>Enter TICKET_COLLECTED_ON</td>";
		elementHTML+="</tr>";
		elementHTML+="<tr><td>Select</td>";
		elementHTML+="<td>";	
		elementHTML+="<select name='collected_on'>";
		elementHTML+="<option value='entry'>Entry</option>";
		elementHTML+="<option value='exit'>Exit</option>";
	
		elementHTML+="</select>";
		
		elementHTML+="</td>";	
		elementHTML+="<tr>";
		element="collected_on";
		
	}

	else if((element=="first_use")||(element=="last_use")){
		
		elementHTML+="<tr>";
		elementHTML+="<td>Enter "+element.toUpperCase()+"</td>";
		
		var prefix="";
		if(element=="first_use"){ prefix="first_"; }
		else if(element=="last_use"){ prefix="last_"; }
		
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
	else {
		elementHTML+="<tr>";
		elementHTML+="<td>Enter "+element.toUpperCase()+"</td>";
		elementHTML+="<td><input type=text name='"+element+"' /></td>";
		elementHTML+="</tr>";
	
	}	

	elementHTML+="<tr>";
	elementHTML+="<td>Reference ID</td>";
	elementHTML+="<td>"+reference_id+"</td>";
	
	elementHTML+="</tr>";	
	elementHTML+="<tr>";
	elementHTML+="<td colspan=2 align=center>";
	elementHTML+="<input type=hidden name='formElement' id='formElement' value='"+element+"' />";	
	elementHTML+="<input type='hidden' name='index_id' value='"+collected_id+"' />";
	elementHTML+="<input type=submit value='Edit' />";
	elementHTML+="</td>"; 
	elementHTML+="</tr>";

	elementHTML+="</table>";
	elementHTML+="</form>";
	
	document.getElementById('fillEdit').innerHTML=elementHTML;
	
	if(element=="cash_assistant"){
		makeajax("processing.php?cash_assist","fillCashAssist");	
	
	}
	
	
}
function fillCashAssist(ajaxHTML){
	var driverHTML="";
	if(ajaxHTML=="None available"){
	}
	else {

		driverHTML="<select name='cash_assistant' id='cash_assistant'>";

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
	document.getElementById('cash_assist_fill').innerHTML=driverHTML;	

}



</script>
<br>
<br>



<form action='manually collected.php' method='post'>
<?php
$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");
?>

<table style='border:1px solid gray'>
<tr>
<td>Date</td>
<td>
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
</td>
</tr>
<tr>
<td>Station
</td>
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
<td>Shift</td>
<td>
<select name='shift'>
<option>1</option>
<option>2</option>
<option>3</option>
</select>
</td>
</tr>
<tr>
<td>Supervisor</td>
<td>
<input type=text name='supervisor' size=30 />
</td>
</tr>
<tr>
<th colspan=2><input type=submit value='Get Records' />
</th>
</tr>
</table>
</form>
<br>
<br>
<?php
$daily_id="XXX";

if(isset($_POST['edit_supervisor'])){
	$daily_id=$_POST['daily_id'];
	$update="update collected_daily set supervisor='".$_POST['edit_supervisor']."' where id='".$daily_id."'";
	$updateRS=$db->query($update);
	
}


if(isset($_SESSION['month'])){
	
	$daily_id="";
	
	$daily_id=$_SESSION['manually_id'];
	$station=$_SESSION['station'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];
	$year=$_SESSION['year'];
	$shift=$_SESSION['manually_shift'];
	$daily_date=$year."-".$month."-".$day;
	$daily_name=date("F d, Y",strtotime($daily_date));
	
	$sql="select * from station where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];	

	$id_sql="select * from collected_daily where id='".$daily_id."'";
	$id_rs=$db->query($id_sql);
	
	$id_nm=$id_rs->num_rows;
	
	if($id_nm>0){
		$id_row=$id_rs->fetch_assoc();
		$supervisor=$id_row['supervisor'];
		
	}

}
if(isset($_POST['month'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$station=$_POST['station'];
	$supervisor=$_POST['supervisor'];
	$shift=$_POST['shift'];
	
	$daily_date=$year."-".$month."-".$day;
	$daily_name=date("F d, Y",strtotime($daily_date));
	
	$sql="select * from station where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];
	
	
	$daily_id="";
	
	
	
	$id_sql="select * from collected_daily where date='".$daily_date."' and station='".$station."' and shift='".$shift."'";
	$id_rs=$db->query($id_sql);
	
	$id_nm=$id_rs->num_rows;
	
	if($id_nm>0){
		$id_row=$id_rs->fetch_assoc();
		$daily_id=$id_row['id'];
		
		$supervisor=$id_row['supervisor'];
		
	}
	else {
		$update="insert into collected_daily(date,station,supervisor,shift) values ('".$daily_date."','".$station."',\"".$supervisor."\",'".$shift."')";
		$updateRS=$db->query($update);
		$daily_id=$db->insert_id;	

		
		
	}
	
	
	$general_sql="select * from manually_collected_by_station where collected_date='".$daily_date."' and station='".$station."' limit 1";
	
	$general_rs=$db->query($general_sql);
	
	$general_nm=$general_rs->num_rows;
	
	if($general_nm>0){
		$general_row=$general_rs->fetch_assoc();
		$manually_collected_id=$general_row['id'];
		
		$manually_forms_no=$general_row['no_of_forms'];
		
		
		$ca_sql="select * from cash_assistant where username='".$general_row['cash_assistant']."' limit 1";
		$ca_rs=$db->query($ca_sql);
		$ca_nm=$ca_rs->num_rows;
		
		if($ca_nm>0){
			$ca_row=$ca_rs->fetch_assoc();
			$receiving_ca=$ca_row['lastName'].", ".$ca_row['firstName'];	
			
		}
		
		
		

	}
	else {
		$update="insert into manually_collected_by_station(collected_date,station) values ('".$daily_date."','".$station."')";
		$updateRS=$db->query($update);
		$manually_collected_id=$db->insert_id;	
		
		
		
	}
	
	
	
	
	
	
	
	$_SESSION['manually_id']=$daily_id;
	$_SESSION['station']=$station;
	$_SESSION['month']=$_POST['month'];
	$_SESSION['day']=$_POST['day'];
	$_SESSION['year']=$_POST['year'];	
	$_SESSION['manually_shift']=$shift;
	
	
}


?>
<table style='border:1px solid gray' width=100%>
<tr>
<td><b>Supervisor: <?php echo $supervisor; ?></b> <a href='#' onclick="document.getElementById('supervisor_manual').style.visibility='visible'; ">Edit</a></td>
<td><b>Date: <?php echo $daily_name; ?></b></td>
</tr>
<tr>
<td><b>Station: <?php echo $station_name; ?></b></td>
<td><b>Shift: <?php echo $shift; ?></b></td>
</tr>


</table>
<form action='manually collected.php' method='post'>
<table name='supervisor_manual' id='supervisor_manual' style='visibility:hidden;'>
<tr><th>Enter Supervisor</th><td><input type=text name='edit_supervisor' size=30 /></td><td><input type='hidden' name='daily_id' value='<?php echo $daily_id; ?>' /><input type='submit' value='Submit' /></td></tr>
</table>
</form>
<br>
<table style='border:1px solid gray' width=100%>
<tr>
<td><b># of Forms: <?php echo $manually_forms_no; ?></b></td>
<td><b>Cash Assistant: <?php echo $receiving_ca; ?></b></td>
</tr>

</table>
<?php
if($daily_id==""){
}
else {
?>
<a href='#' onclick="window.open('manually_collected_data_entry.php?manually_id=<?php echo $manually_collected_id; ?>','_blank')">Edit</a>
<?php
}
?>
<br>
<br>
<table border=1 style='border-collapse:collapse' width=100%>
<tr>
<th rowspan=2>Reference ID</th>
<th rowspan=2>Ticket Type</th>
<th rowspan=2>Serial Number</th>
<th rowspan=2>Ticket Status</th>
<th rowspan=2>Remaining Value</th>
<th rowspan=2>First Use</th>
<th rowspan=2>Last Use</th>
<th rowspan=2>EESP #</th>
<th colspan=2>Ticket Collected Upon</th>
<th rowspan=2>Receiving Cash Assistant</th>
<th rowspan=2>Remarks</th>
</tr>
<tr>
<th>Entry</th>
<th>Exit</th>
</tr>
<?php

$manually_sql="select * from manually_collected where c_daily_id='".$daily_id."'";
$manually_rs=$db->query($manually_sql);
$manually_nm=$manually_rs->num_rows;
for($k=0;$k<$manually_nm;$k++){
	$reference_stamp=date("Ymd");
	$reference_id=$reference_stamp."_".$k;

	$cash_assist="";
	$entry="";
	$exit="";
	$manually_row=$manually_rs->fetch_assoc();
	
	$ca_sql="select * from cash_assistant where username='".$manually_row['cash_assistant']."' limit 1";
	$ca_rs=$db->query($ca_sql);
	$ca_nm=$ca_rs->num_rows;
	if($ca_nm>0){
		$ca_row=$ca_rs->fetch_assoc();
		$cash_assist=$ca_row['lastName'].", ".$ca_row['firstName'];
	}
	
	$first_use=date("Y-m-d H:i",strtotime($manually_row['first_use']));
	$last_use=date("Y-m-d H:i",strtotime($manually_row['last_use']));

	if($manually_row['collected_on']=="entry"){
		$entry="X";
	
	}
	else if($manually_row['collected_on']=="exit"){
		$exit="X";
	
	}
	$remaining_value=$manually_row['remaining_value'];
	$status=$manually_row['status'];
	$eesp_no=$manually_row['eesp_no'];
	$remarks=$manually_row['remarks'];
	$serial_no=$manually_row['serial_no'];
	$type=strtoupper($manually_row['type']);
?>	
	<tr>
		<td><?php echo $reference_id; ?></td>

		<td><?php echo $type; ?> <a href='#' onclick="fillEdit('type','<?php echo $manually_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $serial_no; ?> <a href='#' onclick="fillEdit('serial_no','<?php echo $manually_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $status; ?> <a href='#' onclick="fillEdit('status','<?php echo $manually_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $remaining_value; ?> <a href='#' onclick="fillEdit('remaining_value','<?php echo $manually_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $first_use; ?> <a href='#' onclick="fillEdit('first_use','<?php echo $manually_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $last_use; ?> <a href='#' onclick="fillEdit('last_use','<?php echo $manually_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $eesp_no; ?> <a href='#' onclick="fillEdit('eesp_no','<?php echo $manually_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<th><?php echo $entry; ?> <a href='#' onclick="fillEdit('entry','<?php echo $manually_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></th>
		<th><?php echo $exit; ?> <a href='#' onclick="fillEdit('exit','<?php echo $manually_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></th>
		<td><?php echo $cash_assist; ?> <a href='#' onclick="fillEdit('cash_assistant','<?php echo $manually_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td><?php echo $remarks; ?> <a href='#' onclick="fillEdit('remarks','<?php echo $manually_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
		<td>
		<a href='#' onclick="deleteRow('<?php echo $manually_row['id']; ?>','manually_collected')">X</a></td>
	</tr>
<?php
}
?>
</table>
<div id='fillEdit' name='fillEdit'>



</div>
<br>

<?php 
if($daily_id==""){
}
else {
?>
	<a href='#' onclick="window.open('manually collected entry.php?daily_id=<?php echo $daily_id; ?>')">Add New Entry</a>
	<br>
	<br>
	<br>
	<a href='#' onclick="window.open('generate_dr2c.php')">Generate DR2C</a>

<?php
}
?>