<?php
$db=new mysqli("localhost","root","","station");
?>
<?php
if(isset($_POST['refund_id'])){
	$refund_id=$_POST['refund_id'];
	$reason_refund=$_POST['refund_reason'];
//	$serial_no=$_POST['serialno'];
	$refund_amount=$_POST['refund_amount'];
	$ticket_type=$_POST['ticket_type'];
		
//	$sql="insert into refund(ticket_type,serial_no,refund_amount,reason,refund_id) ";
//	$sql.=" values ('".$ticket_type."','".$serial_no."','".$refund_amount."','".$reason_refund."','".$refund_id."')";
	$sql="insert into refund(ticket_type,refund_amount,reason,refund_id) ";
	$sql.=" values ('".$ticket_type."','".$refund_amount."','".$reason_refund."','".$refund_id."')";

	$rs=$db->query($sql);	
}

if(isset($_POST['refund_index'])){
	$refund_index=$_POST['refund_index'];
	
	$sql="select * from refund where id='".$_POST['refund_index']."' limit 1";
	$rs=$db->query($sql);
	
	$row=$rs->fetch_assoc();
	
	$refund_id=$row['refund_id'];
	
	$formElement=$_POST['formElement'];
	
	$update="update refund set ";
	
	$update.=$formElement."='".$_POST[$formElement]."' ";
	
	$update.=" where id='".$refund_index."'";
	
	$updateRS=$db->query($update);

	//$update="update refund set ";
	//$update.=" where id='".$refund_id."'";
}

if(isset($_GET['refund_id'])){
	$refund_id=$_GET['refund_id'];
}

if($refund_id==""){
}
else {
	$sql="select * from refund_ticket_seller where id='".$refund_id."'";
	$rs=$db->query($sql);

	$row=$rs->fetch_assoc();
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

	$station_sql="select * from station2 where id='".$station."'";
	$station_rs=$db->query($station_sql);

	$station_row=$station_rs->fetch_assoc();

	$station_name=$station_row['station_name'];



?>
<script language='javascript' src='ajax.js'></script>
<script language='javascript'>

function deleteRow(index,table,refund_id,station){
	var check=confirm("Remove Record?");
	if(check){
		makeajax("processing.php?removeRow="+index+"&tableType="+table+"&refund_id="+refund_id+"&station="+station,"reloadPage");	
	}
}

function reloadPage(ajaxHTML){
	self.location=ajaxHTML;


}

function getReason(){
	makeajax("processing.php?reason_error=Y","fillReason");	
}

function fillReason(ajaxHTML){
	var driverHTML="";
	if(ajaxHTML=="None available"){
	}
	else {

		driverHTML="<select name='reason' id='reason'>";

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


function fillEdit(element,refund_id,reference_id){
	var elementHTML="<br>";
	elementHTML+="<form action='refund_ticket.php' method='post' >";
	elementHTML+="<table style='border:1px solid red'>";
	elementHTML+="<tr>";
	elementHTML+="<td>Reference ID</td>";
	elementHTML+="<td>"+reference_id+"</td>";
	
	elementHTML+="</tr>";		
	elementHTML+="<tr>";
	elementHTML+="<td>Enter "+element.toUpperCase()+"</td>";
	if((element=="serial_no")||(element=="refund_amount")){
		elementHTML+="<td><input type=text name='"+element+"' /></td>";
	}
	else if(element=="ticket_type"){
		elementHTML+="<td id='ticketFill' name='ticketFill'>";
		elementHTML+="<select name='ticket_type'>";
		elementHTML+="<option value='sjt'>SJT</option>";
		elementHTML+="<option value='sjd'>DSJT</option>";
		elementHTML+="<option value='svt'>SVT</option>";
		elementHTML+="<option value='svd'>DSVT</option>";
		
		elementHTML+="</select>";
		elementHTML+="</td>";
	}
	else if(element=="reason"){
		elementHTML+="<td id='reasonFill' name='reasonFill'></td>";
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

	if(element=="reason"){
		getReason();
	}

}
</script>

<link rel="stylesheet" href="layout/body.css" />
<link rel="stylesheet" href="layout/styles.css" />

<table class="TableCLC2" align="center">
<tr>
	<th colspan="2">Details</th>
</tr>
<tr>
<td>Ticket Seller: <b><?php echo $ticket_seller_name; ?></td>
<td>Date: <b> <?php echo $refund_date; ?></td>
</tr>
<tr>
<td>ID Number: <b> <?php echo $ticket_seller_id; ?></td>
<td>Time: <b> <?php echo $refund_time; ?></td>
</tr>
<tr>
<td>AD Number: <b> <?php echo $ad_no; ?></td>
<td>Station: <b> <?php echo $station_name; ?></td>
</tr>
</table>
<?php
}
?>

<table class="BigTableCLC">
<tr>
<th>Reference ID</th>
<th>Ticket Type</th>
<!--
	<th>Serial Number</th>
-->
<th>Refund Amount</th>
<th>Reason for Refund</th>
</tr>
<?php
$sql="select * from refund where refund_id='".$refund_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
$reference_stamp=date("Ymd");
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();

	$reference_id=$reference_stamp."_".$i;	
?>
<tr>
	<td class="UnclickableCLC"><?php echo $reference_id; ?></td>
	<td class="ClickableCLC" onclick="fillEdit('ticket_type','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo strtoupper($row['ticket_type']); ?></td>

	
	<td class="ClickableCLC" onclick="fillEdit('refund_amount','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $row['refund_amount']; ?></td>
	<td class="ClickableCLC" onclick="fillEdit('reason','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')" align=center><?php echo $row['reason']; ?></td>
	<td class="DeletableCLC"><a href='#' onclick="deleteRow('<?php echo $row['id']; ?>','refund_ticket','<?php echo $refund_id; ?>','<?php echo $station; ?>')" style="text-decoration:none;">X</a></td>
</tr>
<?php
}
/*
<td align=center><?php echo $row['serial_no']; ?> <a href='#' onclick="fillEdit('serial_no','<?php echo $row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td> 
*/
?>
</table>
<div id='fillRefund' name='fillRefund'></div>

<form action='refund_ticket.php' method='post'>
<table class="EntryTableCLC" width="30%" align="center"><tr><td>
<table class="miniHolderCLC">
<tr>
<th class="HeaderCLC" colspan=2>Add Entry</th>
</tr>
<tr>
<td>Ticket Type</td>
<td>
<select name='ticket_type'>
	<option value='sjt'>SJT</option>
	<option value='sjd'>DSJT</option>
	<option value='svt'>SVT</option>
	<option value='svd'>DSVT</option>
</select>
</td>
</tr>
<!--
<tr><th>Serial No.</th>
<td><input type='text' name='serialno' /></td></tr>
-->
<tr>
<td width="70%">Refund Amount</td>
<td width="30%"><input type='text' name='refund_amount' placeholder="Refund Amount" /></td>
</tr>
<tr>
<td>Reason for Refund</td>
<td>
<select name='refund_reason'>
<?php
$sql="select * from errors"; 
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<option value='<?php echo $row['code']; ?>'><?php echo $row['name']; ?></option>
<?php
}
?>

</select>
</td>
</tr>
</table></td></tr>
<?php
if($refund_id==""){
}
else {
?>
<tr>
<td align="center" style="padding:15px 0px;">
<input type=hidden name='refund_id' value='<?php echo $refund_id; ?>' />
<input type=submit value='Submit' />
</th>
</tr>
<?php
}
?>
</table>
</form>