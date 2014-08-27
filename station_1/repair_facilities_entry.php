<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","station");

if(isset($_POST['station'])){

	$station=$_POST['station'];

	$ss=$_POST['ss'];	
	
	$remarks=$_POST['remarks'];
	
	$repair_no=$_POST['repair_no'];
	
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
	
	$reported_date=date("Y-m-d H:i",strtotime($first_year."-".$first_month."-".$first_day." ".$first_hour.":".$first_minute));
	
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
	
	$repair_date=date("Y-m-d H:i",strtotime($first_year."-".$first_month."-".$first_day." ".$first_hour.":".$first_minute));

	$equipt=$_POST['equipt'];	
	
	$monitoring_date=$_POST['monitoring_date'];

	$update="insert into repair(e_incident_id,reported_cc,repair_time,repair_no,ss,date,remarks_repair,station) ";
	$update.=" values ";
	$update.="('".$equipt."','".$reported_date."','".$repair_date."','".$repair_no."',\"".$ss."\",'".$monitoring_date."',\"".$remarks."\",'".$station."')";	
	$updateRS=$db->query($update);

	
	echo "<script language='javascript'>";
	echo "window.opener.location='repair_facilities_monitoring.php';";
	
	echo "</script>";
	
	
	
}

if(isset($_GET['dd'])){
	$monitoring_date=$_GET['dd'];
}
?>
<form action='repair_facilities_entry.php' method='post'>
<table style='border:1px solid gray'>
<tr>
<td>Station</td>
<td>
<select name='station'>
<option></option>
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
<td>SS</td>
<td>
<input type=text name='ss' />
</td>
</tr>
<tr>
<th colspan=2>Problem Details</th>
</tr>
<tr>
<td align=center colspan=2>
<a href='#' onclick="window.open('search_facility.php','_blank')">Link Defective Facility/Equipment</a>
</td>
</tr>
<tr>
<th>Record No.</th><td id='record_no' name='record_no' ></td>
</tr>
<tr>
<th>Incident No.</th><td id='incident_no' name='incident_no'></td>
</tr>
<tr>
<th>Details</th><td id='problem_details' name='problem_details'></td>
</tr>


<tr>
<td align=center colspan=2>
<a href='#' onclick="window.open('search_facility.php','_blank')">Link Defective Facility/Equipment</a>

<input type=hidden name='equipt' id='equipt' />
</td>
</tr>
<tr>
<th colspan=2>Time</th>
</tr>

<tr>
<td>Reported to CC</td>
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
<td>Repaired As Of</td>
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
	<td align=center>Repair No.</td>
	<td><input type=text name='repair_no' /></td>




</tr>
<tr>
<td align=center>Remarks</td>
<td>
<textarea rows=5 cols=50 name='remarks'></textarea>
</td>
</tr>
<?php
if($monitoring_date==""){ }
else {
?>
<tr>
	<td colspan=2 align=center>
		<input type=submit value='Submit' />
		<input type=hidden name='monitoring_date' value='<?php echo $monitoring_date; ?>' />
	</td>
</tr>
<?php
}
?>
</table>
</form>

