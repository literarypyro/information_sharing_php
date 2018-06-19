<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php 
require("monitoring menu.php");
?>
<link href="layout/landbank/logbook style.css" rel="stylesheet" type="text/css"  id='stylesheet' />
<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['refund_index'])){
	$refund_index=$_POST['refund_index'];
	
	$formElement=$_POST['formElement'];
	
	$update="update refund_ticket_seller set ";

	if($formElement=="refund_time"){
		$prefix="refund_";

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
			
			
		$update.=$formElement."='".$availability_date."' ";

	}
	else {
		$update.=$formElement."='".$_POST[$formElement]."' ";
	}
	$update.=" where id='".$refund_index."'";
	
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
	self.location="refund daily.php";


}

function getTicketSeller(){
	makeajax("processing.php?ticket_seller=Y","fillTicketSeller");	


}



function fillEdit(element,refund_id,reference_id){
	var elementHTML="<br>";
	elementHTML+="<form action='refund daily.php' method='post' >";
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
	else if(element=="refund_time"){
		
		
		var prefix="refund_";
		
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
		elementHTML+="<td><input type=text name='"+element+"' /></td>";
	}


	elementHTML+="</tr>";		
	
	elementHTML+="<tr>";
	elementHTML+="<td colspan=2 align=center>";
	elementHTML+="<input type=hidden name='formElement' id='formElement' value='"+element+"' />";	
	elementHTML+="<input type='hidden' name='refund_index' value='"+refund_id+"' />";
	elementHTML+="<input type=submit value='Edit' />";
	elementHTML+="</td>"; 
	elementHTML+="</tr>";


	elementHTML+="</table>";
	elementHTML+="</form>";
	
	document.getElementById('fillRefund').innerHTML=elementHTML;

	if(element=="ticket_seller"){
		getTicketSeller();
	
	
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
Refund Monitoring
</div>
<form action='refund daily.php' method='post'>
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
$db=new mysqli("localhost","root","","station");

$sql="select * from station2";
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
	<input type="button" value="Add New Refund Entry" onclick="PasaCLC()" />
	</li>
	<li style="float:right;">
	<input type="button" onclick="window.open('generate_reason_refund.php')" value="Generate Reasons for Refund Report" />
	</li>
</ul>
</form>
<hr class="PgLine"/>
<?php
$db=new mysqli("localhost","root","","station");
if(isset($_SESSION['month'])){
	$year=$_SESSION['year'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];
	$daily_date=date("Y-m-d",strtotime($year."-".$month."-".$day));
	$daily_name=date("F d, Y",strtotime($daily_date));
	
	$station=$_SESSION['station2'];
//	$daily_id=$_SESSION['refund_id'];

	$sql="select * from station2 where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];
	
	$id_sql="select * from refund_daily where date='".$daily_date."' and station='".$station."'";
	$id_rs=$db->query($id_sql);
	
	$id_nm=$id_rs->num_rows;
	
	if($id_nm>0){
		$id_row=$id_rs->fetch_assoc();
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
	
	$sql="select * from station2 where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];
	
	$daily_id="";
	
	
	
	$id_sql="select * from refund_daily where date='".$daily_date."' and station='".$station."'";
	$id_rs=$db->query($id_sql);
	
	$id_nm=$id_rs->num_rows;
	
	if($id_nm>0){
		$id_row=$id_rs->fetch_assoc();
		$daily_id=$id_row['id'];
	}
	else {
		$update="insert into refund_daily(date,station) values ('".$daily_date."','".$station."')";
		$updateRS=$db->query($update);
		$daily_id=$db->insert_id;	
	}
	
	$_SESSION['month']=$_POST['month'];
	$_SESSION['day']=$_POST['day'];
	$_SESSION['year']=$_POST['year'];
	
	$_SESSION['station2']=$station;
	$_SESSION['refund_id']=$daily_id;

}

?>
<?php
if($daily_id==""){
}
else {
?>
<table class="TableCLC">
<tr>
	<th colspan="2" class="TableHeaderCLC">Station & Date</th>
</tr>
<tr>
	<td class="col1CLC">Station</td>
	<td class="col2CLC"><?php echo $station_name; ?></td>
</tr>
<tr>
	<td class="col1CLC">Date</td>
	<td class="col2CLC"><?php echo $daily_name;	?></td>
</tr>
</table>
<?php
}
?>
<br>
<table class="BigTableCLC">
<tr>
<th>Reference ID</th>
<th>Ticket Seller</th>
<th>ID Number</th>
<th>AD Number</th>
<th>Date</th>
<th>Time</th>
<th>Number of <br>Tickets Refunded</th>
</tr>


<!--table width=100% border=1px style='border-collapse:collapse' class='logbookTable'>
<tr>
<th>Reference ID</th>
<th>Ticket Seller</th>
<th>ID Number</th>
<th>AD Number</th>
<th>Date</th>
<th>Time</th>
<th>Number of <br>Tickets Refunded</th>
</tr-->
<?php
$sql="select * from refund_ticket_seller where refund_daily_id='".$daily_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
$reference_stamp=date("Ymd");
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();

	$reference_id=$reference_stamp."_".$i;		
	$ticket_seller_sql="select * from ticket_seller where id='".$row['ticket_seller']."' limit 1";
	$ticket_seller_rs=$db->query($ticket_seller_sql);
	$ticket_seller_row=$ticket_seller_rs->fetch_assoc();
	
	$ticket_seller_name=$ticket_seller_row['last_name'].", ".$ticket_seller_row['first_name'];
	$ticket_seller_id=$row['ticket_seller'];
	
	$ad_no=$row['ad_no'];
	$refund_date=date("F d, Y",strtotime($row['refund_time']));
	$refund_time=date("H:i",strtotime($row['refund_time']));
	
	$refund_id=$row['id'];
	
	$station=$row['station'];
?>
<tr>
	<td class="UnclickableCLC" align=center><?php echo $reference_id; ?></td>
	<td class="ClickableCLC" onclick="fillEdit('ticket_seller','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $ticket_seller_name; ?></td>
	<td class="ClickableCLC" onclick="fillEdit('ticket_seller','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $ticket_seller_id; ?></td>
	<td class="ClickableCLC" onclick="fillEdit('ad_no','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $ad_no; ?></td>
	<td class="ClickableCLC" onclick="fillEdit('refund_time','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $refund_date; ?></td>
	<td class="ClickableCLC" onclick="fillEdit('refund_time','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $refund_time; ?></td>
<?php	
	$refund_count_sql="select * from refund where refund_id='".$refund_id."'";	
	$refund_count_rs=$db->query($refund_count_sql);
	$refund_count=$refund_count_rs->num_rows;

	if($refund_count>0){
	?>	
	<td align=center><a href='#' onclick="window.open('refund_ticket.php?refund_id=<?php echo $refund_id; ?>&station=<?php echo $station; ?>')" ><?php echo $refund_count; ?></a>
<?php
	}
	else {
?>
	<td align=center><a href='#' onclick="window.open('refund_ticket.php?refund_id=<?php echo $refund_id; ?>&station=<?php echo $station; ?>')">Fill Refund</a></td>
<?php
	}
?>	
	 <td class="DeletableCLC"><a href='#' onclick="deleteRow('<?php echo $refund_id; ?>','refund_person')">X</a>

	</td>
</tr>
<?php
}
?>
</table>
<br>
<div id='fillRefund' name='fillRefund'>



</div>
<br>
<?php
if($daily_id==""){
}
else {
?>
<a href="#" style="display:none;" id="AddNewEntryCLC" onclick="window.open('refund entry.php?daily_id=<?php echo $daily_id; ?>')">Add New Refund Entry</a>
<!--a href='#' onclick="window.open('generate_reason_refund.php')">Generate Reasons for Refund Report</a-->


<?php


}
?>