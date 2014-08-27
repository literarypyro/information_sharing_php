<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['ticket_seller'])){
	
	$year=$_POST['general_year'];
	$month=$_POST['general_month'];
	$day=$_POST['general_day'];
	
	$hour=$_POST['general_hour'];
	$minute=$_POST['general_minute'];
	$amorpm=$_POST['general_amorpm'];

	$daily_id=$_POST['daily_id'];
	$ticket_seller=$_POST['ticket_seller'];
	$station=$_POST['station'];
	$ad_no=$_POST['ad_no'];
	
	
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

	$refund_time=date("Y-m-d H:i",strtotime($year."-".$month."-".$day." ".$hour.":".$minute));	

	$sql="insert into refund_ticket_seller(ticket_seller,ad_no,refund_time,station,refund_daily_id)";
	$sql.=" values ";
	$sql.="('".$ticket_seller."','".$ad_no."','".$refund_time."','".$station."','".$daily_id."')";
	$rs=$db->query($sql);
	
	$refund_id=$db->insert_id;
	
	echo "<script language='javascript'>";
	echo "window.opener.location.reload();";
	echo "window.open('refund_ticket.php?refund_id=".$refund_id."&station=".$station."','_self');";
	echo "</script>";
	
	
	//header("Location: refund_ticket.php?refund_id=".$refund_id."&station=".$station);
}

?>
<?php
if(isset($_GET['daily_id'])){
	$daily_id=$_GET['daily_id'];
}
?>
<form action="refund entry.php" method='post'>
<table style='border:1px solid gray'>
<tr>
<th>Ticket Seller</th>
<td>
<select name='ticket_seller'>
<?php
$db=new mysqli("localhost","root","","station");

$sql="select * from ticket_seller order by last_name";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();

?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['last_name'].", ".$row['first_name']; ?></option>
<?php
}
?>
</select>
</td>
</tr>
<tr>
<th>AD No.</th>
<td><input type=text name='ad_no' /></td>
</tr>
<tr>
<th>Refund Time</th>
<td>
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
<tr>
<th>Station</th>
<td>
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
</td>
</tr>
<tr>
<th>Error (MTC/Judge)</th>
<td>
<select name='error'>
<?php
$sql="select * from teemr_error_list"; 
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
<!--
<input type=text name='error' size='5' />
-->
</td>
</tr>
<tr>
<th>Error Quantity</th>
<td><input type=text name='quantity' /></td>
</tr>


<?php
if($daily_id==""){
}
else {
?>
<tr>
	<th colspan=2>
		<input type=hidden name='daily_id' value='<?php echo $daily_id; ?>' />
		<input type=submit value='Submit' />
	</th>
</tr>
<?php
}
?>
</table>
</form>