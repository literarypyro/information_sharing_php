<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","station");
?>
<?php
if(isset($_GET['daily_id'])){
	$daily_id=$_GET['daily_id'];

}

if(isset($_POST['ticket_type'])){
	$ticket_type=$_POST['ticket_type'];
	$serial_no=$_POST['serial_no'];
	$ticket_status=$_POST['ticket_status'];
	$remaining_value=$_POST['remaining_value'];
	$collected_upon=$_POST['collected_upon'];
	$cash_assistant=$_POST['cash_assistant'];
	$eesp_no=$_POST['eesp_no'];
	
	$remarks=$_POST['remarks'];
	
	$first_year=$_POST['first_year'];
	$first_month=$_POST['first_month'];
	$first_day=$_POST['first_day'];
	
	$first_hour=$_POST['first_hour'];
	$first_minute=$_POST['first_minute'];
	$first_amorpm=$_POST['first_amorpm'];

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
	
	$first_date=date("Y-m-d H:i",strtotime($first_year."-".$first_month."-".$first_day." ".$first_hour.":".$first_minute));

	$last_year=$_POST['last_year'];
	$last_month=$_POST['last_month'];
	$last_day=$_POST['last_day'];
	
	$last_hour=$_POST['last_hour'];
	$last_minute=$_POST['last_minute'];
	$last_amorpm=$_POST['last_amorpm'];

	if($last_amorpm=="pm"){
		if($last_hour<12){
			$last_hour+=12;
			
		}
		else {
		}
	}
	else {
		if($last_hour=="12"){
			$last_hour=0;
			
		}
	
	}
	
	$last_date=date("Y-m-d H:i",strtotime($last_year."-".$last_month."-".$last_day." ".$last_hour.":".$last_minute));

	
	$daily_id=$_POST['daily_id'];
	
	
	$update="insert into manually_collected(c_daily_id,type,serial_no,status,remaining_value,first_use,last_use,collected_on,cash_assistant,remarks,eesp_no) values ";
	$update.="('".$daily_id."','".$ticket_type."','".$serial_no."','".$ticket_status."','".$remaining_value."','".$first_date."','".$last_date."','".$collected_upon."','".$cash_assistant."',\"".$remarks."\",'".$eesp_no."')";
	$updateRS=$db->query($update);
	
	echo "<script language='javascript'>";
	echo "window.opener.location='manually collected.php';";
	echo "</script>";
}
?>
<link href="layout/landbank/control slip.css" rel="stylesheet" type="text/css"  id='stylesheet' />

<form action='manually collected entry.php' method='post'>
<table style='border:1px solid gray' class='controlTable'>
<tr>
<th>Ticket Type</th>
<td>
<select name='ticket_type'>
<option value='sjt'>SJT</option>
<option value='svt'>SVT</option>
<option value='sjd'>SJD</option>
<option value='svd'>SVD</option>
</select>
</tr>
<tr>
<th>Serial Number</th>
<td><input type='text' name='serial_no' /></td>

</tr>
<tr>
<th>Ticket Status</th>
<td><input type='text' name='ticket_status' /></td>

</tr>
<tr>
<th>Remaining Value</th>
<td><input type='text' name='remaining_value' /></td>

</tr>
<tr>
<th>First Use</th>
<td>
<select name='first_month'>
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
<select name='first_day'>
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
<select name='first_year'>
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
</td></tr>
<tr><td>&nbsp; </td><td>
<select name='first_hour'>
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
<select name='first_minute'>
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
<select name='first_amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>

</td>

</tr>
<tr>
<th>Last Use</th>
<td>
<select name='last_month'>
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
<select name='last_day'>
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
<select name='last_year'>
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
</td></tr>
<tr><td>&nbsp; </td><td>
<select name='last_hour'>
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
<select name='last_minute'>
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
<select name='last_amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>

</td>

</tr>

<tr>
<th>EESP #</th>
<td><input type=text name='eesp_no' /></td>

</tr>
<tr>
<th>Ticket Collected Upon</th>
<td>
<select name='collected_upon'>
<option value='entry'>Entry</option>
<option value='exit'>Exit</option>
</select>
</td>
</tr>
<tr>
<th>Cash Assistant</th>
<td>
<select name='cash_assistant'>
<?php
$sql="select * from cash_assistant order by lastName";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>	
	<option value="<?php echo $row['username']; ?>"><?php echo $row['lastName'].", ".$row['firstName']; ?></option>
	
	
<?php
}
?>
</select>
</td>
</tr>
<tr>
<th>Remarks</th>
<td>
<textarea name='remarks' cols=40 rows=4></textarea>
</td>
</tr>
<?php 
if($daily_id==""){
}
else {
?>
<tr>
<th colspan=2>
<input type=hidden name='daily_id' value="<?php echo $daily_id; ?>" />
<input type=submit value='Submit' />
</th>
</tr>
<?php
}
?>
</table>
</form>