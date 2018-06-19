<?php
session_start();
?>
<?php
require("monitoring menu.php");
?>
<link href="layout/landbank/logbook style.css" rel="stylesheet" type="text/css"  id='stylesheet' />
<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['teemr_index'])){
	$element=$_POST['formElement'];
	
	$update="";
	$update.="update ";
	
//	if(($element=="record")||($element=="incident_no")){
//		$update.="issued_no ";
//	}
//	else {
		$update.="ticket_error ";	
	
//	}
	
	$update.=" set ";
	
	if($element=="error_time"){
		$prefix="error_";

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
			
			
		$update.=$element."='".$availability_date."'";

	}
//	else if($element=="incident_no"){
//		$update.="incident_cdc='".$_POST[$element]."' ";
	
	
//	}
	else {
		$update.=$element."='".$_POST[$element]."' ";
	
	
	}
/*	
	if(($element=="record")||($element=="incident_no")){

		$sql="select * from ticket_to_monitoring where ticket_error_id='".$_POST['teemr_index']."' limit 1";

		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		$equipt_id=$row['monitoring_id'];
		
		
		$update.="where e_incident_id='".$equipt_id."' ";
	}
	else {
*/
	$update.="where id='".$_POST['teemr_index']."'";	
	
//	}

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
	self.location="ticket_encoding_monitoring.php";


}
function getTicketSeller(){
	makeajax("processing.php?ticket_seller=Y","fillTicketSeller");	


}
function getReason(){
	makeajax("processing.php?reason_error=Y","fillReason");	


}

function fillReason(ajaxHTML){
	var driverHTML="";
	if(ajaxHTML=="None available"){
	}
	else {

		driverHTML="<select name='error_code' id='error_code'>";

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
	document.getElementById('reasonFill').innerHTML=driverHTML;

}


function fillEdit(element,teemr_id,reference_id){
	var elementHTML="<br>";
	elementHTML+="<form action='ticket_encoding_monitoring.php' method='post' >";
	elementHTML+="<table style='border:1px solid red'>";
	elementHTML+="<tr>";
	elementHTML+="<td>Reference ID</td>";
	elementHTML+="<td>"+reference_id+"</td>";
	
	elementHTML+="</tr>";		
	elementHTML+="<tr>";
	elementHTML+="<td>Enter "+element.toUpperCase()+"</td>";


	if(element=="ticket_seller"){
		
		elementHTML+="<td id='ticket_seller_fill' name='ticket_seller_fill'></td>";
		
	}
	else if(element=="error_code"){
		
		elementHTML+="<td id='reasonFill' name='reasonFill'></td>";
		
	}	
	else if(element=="error_time"){
		
		
		var prefix="error_";
		
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
	else if(element=="error_category"){
		elementHTML+="<td><select name='"+element+"' >";
		elementHTML+="<option value='1'>Human Error</option>";
		elementHTML+="<option value='2'>System Error</option>";
		
		elementHTML+="</select></td>";
	}

	else {
		elementHTML+="<td><input type=text name='"+element+"' /></td>";
	}


	elementHTML+="</tr>";		
	
	elementHTML+="<tr>";
	elementHTML+="<td colspan=2 align=center>";
	elementHTML+="<input type=hidden name='formElement' id='formElement' value='"+element+"' />";	
	elementHTML+="<input type='hidden' name='teemr_index' value='"+teemr_id+"' />";
	elementHTML+="<input type=submit value='Edit' />";
	elementHTML+="</td>"; 
	elementHTML+="</tr>";


	elementHTML+="</table>";
	elementHTML+="</form>";
	
	document.getElementById('fillTicketEncoding').innerHTML=elementHTML;

	if(element=="ticket_seller"){
		getTicketSeller();
	
	
	}
	else if(element=="error_code"){
		getReason();
	}	
	


}
function fillTicketSeller(ajaxHTML){
	var driverHTML="";
	if(ajaxHTML=="None available"){
	}
	else {
		driverHTML="<select name='ticket_seller' id='ticket_seller'>";

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
	document.getElementById('ticket_seller_fill').innerHTML=driverHTML;	

}
</script>
<link rel="stylesheet" href="layout/body.css" />
<link rel="stylesheet" href="layout/styles.css" />
<div class="PgTitle">
Ticket Encoding Error Monitoring
</div>
<?php
$db=new mysqli("localhost","root","","station");
?>
<form action='ticket_encoding_monitoring.php' method='post'>
<?php
$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");
?>

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
	<li>
<select name='station'>
<?php


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
	</li>
	<li>
<input type=submit value='Get Records' />
	</li>
	<li style="float:right;">
	<input type="button" value="Add New Entry" onclick="PasaCLC()" />
	</li>
	<li style="float:right;">
	<input type="button" value="Generate DR2A" onclick="window.open('generate_dr2a.php')">
	</li>

</ul>
</form>
<hr class="PgLine"/>
<?php
if(isset($_SESSION['month'])){
	
	$daily_id="";
	

	$station=$_SESSION['station'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];
	$year=$_SESSION['year'];

	
	$sql="select * from station where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];
	
	
	$daily_date=date("Y-m-d",strtotime($year."-".$month."-".$day));
	$daily_name=date("F d, Y",strtotime($daily_date));
	
	$id_sql="select * from ticket_error_daily where date='".$daily_date."' and station='".$station."'";
	$id_rs=$db->query($id_sql);
	
	$id_nm=$id_rs->num_rows;
	
	if($id_nm>0){
		$id_row=$id_rs->fetch_assoc();
		$teemr_no=$id_row['no_of_teemr'];
		
		
		$ca_sql="select * from cash_assistant where username='".$id_row['receiving_ca']."' limit 1";
		$ca_rs=$db->query($ca_sql);
		$ca_nm=$ca_rs->num_rows;
		
		if($ca_nm>0){
			$ca_row=$ca_rs->fetch_assoc();
			$receiving_ca=$ca_row['lastName'].", ".$ca_row['firstName'];	
		}
		$daily_id=$id_row['id'];
	}
}

if(isset($_POST['month'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$station=$_POST['station'];
	
	$daily_date=$year."-".$month."-".$day;
	$daily_name=date("F d, Y",strtotime($daily_date));
	
	$sql="select * from station where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];
	
	$daily_id="";
	
	
	
	$id_sql="select * from ticket_error_daily where date='".$daily_date."' and station='".$station."'";
	$id_rs=$db->query($id_sql);
	
	$id_nm=$id_rs->num_rows;
	
	if($id_nm>0){
		$id_row=$id_rs->fetch_assoc();
		$teemr_no=$id_row['no_of_teemr'];
		
		
		$ca_sql="select * from cash_assistant where username='".$id_row['receiving_ca']."' limit 1";
		$ca_rs=$db->query($ca_sql);
		$ca_nm=$ca_rs->num_rows;
		
		if($ca_nm>0){
			$ca_row=$ca_rs->fetch_assoc();
			$receiving_ca=$ca_row['lastName'].", ".$ca_row['firstName'];	
			
		}

		$daily_id=$id_row['id'];
	}
	else {
		$update="insert into ticket_error_daily(date,station) values ('".$daily_date."','".$station."')";
		$updateRS=$db->query($update);
		$daily_id=$db->insert_id;	
	}
	
	$_SESSION['month']=$_POST['month'];
	$_SESSION['day']=$_POST['day'];
	$_SESSION['year']=$_POST['year'];	
	
	$_SESSION['station']=$station;
	
}	
?>
<table class="TableHolderCLC">
<tr>
	<td style="width:50%;">
	<table class="TableCLC">
		<th colspan="2" class="TableHeaderCLC">Station & Date</th>
	<tr>
		<td class="col1CLC">Station</td>
		<td class="col2CLC"><?php echo $station_name; ?></td>
	</tr>
	<tr>
		<td class="col1CLC">Date</td>
		<td class="col2CLC"><?php echo $daily_name; ?></td>
	</tr>
	</table>
	</td>
	<td style="width:50%;">
<table class="TableCLC">
<tr>
	<th colspan="2"># of TEEMR / Receiving CA
		<?php
		if($daily_id==""){
		}
		else {
		?>
		<a href='#' onclick="window.open('teemr_data_entry.php?daily_id=<?php echo $daily_id; ?>','_blank')" class="editBCLC">Edit</a>
		<?php
		}
		?>
	</th>
</tr>
<tr>
	<td style="min-width:100px;"># of TEEMR</td>
	<td style="min-width:200px;"><?php echo $teemr_no; ?></td>
</tr>
<tr>
	<td>Receiving CA</td>
	<td><?php echo $receiving_ca; ?></td>
</tr>
</table>
</td>
</tr>
</table>


<table class="BigTableCLC">
<tr>
<th rowspan=2>Reference ID</th>
<th rowspan=2>SS In-Charge</th>
<th>Ticket Seller Involved</th>
<th rowspan=2>ID Code</th>
<th rowspan=2>Machine Number</th>
<th>Code</th>
<th>Report Number</th>
<th rowspan=2>Remarks</th>
</tr>
<tr>
<th>Name</th>
<th>MTC/Judge</th>
<th>RN</th>
</tr>



<!--table width=100% border=1px style='border-collapse:collapse;' class='logbookTable'>
<tr>
<th rowspan=2>Reference ID</th>
<th rowspan=2>SS In-Charge</th>
<th>Ticket Seller Involved</th>
<th rowspan=2>ID Code</th>
<th rowspan=2>Machine Number</th>
<th>Code</th>
/* <!--
<th rowspan=2>Type of Error</th>
-->
<!--
<th rowspan=2>Time of Occurrence</th>
<th>IN</th>
-->
<!--th>Report Number</th>
<th rowspan=2>Remarks</th>
</tr>
<tr>
<th>Name</th>
<th>MTC/Judge</th>
<th>RN</th>
</tr-->

<?php
	$sql="select * from ticket_error where ticket_daily_id='".$daily_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$reference_stamp=date("Ymd");	
	for($i=0;$i<$nm;$i++){

		$reference_id=$reference_stamp."_".$i;		
		$row=$rs->fetch_assoc();
		$error_cat=$row['error_category'];
		
		$ticket_seller_sql="select * from ticket_seller where id='".$row['ticket_seller']."' limit 1";
		$ticket_seller_rs=$db->query($ticket_seller_sql);
		$ticket_seller_row=$ticket_seller_rs->fetch_assoc();
		
		$ticket_seller_id=$row['ticket_seller'];
		$ticket_seller_name=$ticket_seller_row['last_name'].", ".$ticket_seller_row['first_name'];
		
		$error_category="&nbsp;";
		if($error_cat==""){
		
		}
		else {
			$error_c_sql="select * from teemr_error_list where id='".$error_cat."' limit 1";
			$error_c_rs=$db->query($error_c_sql);
			
			$error_c_row=$error_c_rs->fetch_assoc();
			$error_category=$error_c_row['error'];
		}
		
		$machine_no=$row['machine_type']." #".$row['machine_no'];
		
		
		$error_e_sql="select * from teemr_error_entry where ticket_error_id='".$row['id']."' and ticket_daily_id='".$daily_id."'";
		$error_e_rs=$db->query($error_e_sql);
		
		$error_e_nm=$error_e_rs->num_rows;
		
		$error_code="";			
		for($n=0;$n<$error_e_nm;$n++){
			$error_e_row=$error_e_rs->fetch_assoc();
			
			if($n==0){
				$error_code.=$error_e_row['error_code'].": ".$error_e_row['quantity'];
			}
			else {
				$error_code.="<br>".$error_e_row['error_code'].": ".$error_e_row['quantity'];
			}
		
		}
		
//		$error_code=$row['error_code'];

		$record_no=$row['record_no'];
		$incident_no=$row['incident_no'];
		
		$ss=$row['ss_name'];
		$time=date("Hi",strtotime($row['error_time']));
		$remarks=$row['remarks'];
	?>
		<tr>
			<td class="UnclickableCLC" align=center><?php echo $reference_id; ?></td>
			<td class="ClickableCLC" onclick="fillEdit('ss_name','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $ss; ?></td>
			<td class="ClickableCLC" onclick="fillEdit('ticket_seller','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $ticket_seller_name; ?></td>
			<td class="ClickableCLC" onclick="fillEdit('ticket_seller','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $ticket_seller_id; ?></td>
			<td class="ClickableCLC" onclick="fillEdit('machine_no','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $machine_no; ?></td>
			<td align=center><?php echo $error_code; ?> <a href='#' onclick='window.open("teemr_error_entry_1.php?ticket_error_id=<?php echo $row['id']; ?>&ticket_daily_id=<?php echo $daily_id; ?>","_blank")' >Add/Fill Errors</a></td>
			<td class="ClickableCLC" onclick="fillEdit('record_no','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $record_no; ?></td>
			<td class="ClickableCLC" onclick="fillEdit('remarks','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $remarks; ?></td>
			<td class="DeletableCLC"><a href='#' onclick="deleteRow('<?php echo $row['id']; ?>','ticket_encoding')">X</a></td>
		</tr>
	<?php
	}

	/*
	<td align=center><?php echo $time; ?> <a href='#' onclick="fillEdit('error_time','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $error_category; ?> <a href='#' onclick="fillEdit('error_category','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $incident_no; ?> <a href='#' onclick="fillEdit('incident_no','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>

	*/
	?>
</table>
<div id='fillTicketEncoding' name='fillTicketEncoding'></div>
<br>
<br>
<?php
if($daily_id==""){
}
else {

?>
	<a href='#' style="display:none;" id="AddNewEntryCLC" onclick="window.open('ticket_encoding_entry.php?daily_id=<?php echo $daily_id; ?>','_blank')">Add New Entry</a>
	<!--a href='#' onclick="window.open('generate_dr2a.php')">Generate DR2A</a-->
<?php
}
?>