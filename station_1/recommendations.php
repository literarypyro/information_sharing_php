<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
if(isset($_POST['details'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];	
	$hour=$_POST['hour'];
	$minute=$_POST['minute'];
	$amorpm=$_POST['amorpm'];
	if($amorpm=='pm'){
		$hour+=(12*1);
		if($hour>=24){
			$hour=0;
		}
	}
	else {
		$hour=$hour;
		
	}
	$recommend_date=$year."-".$month."-".$day." ".$hour.":".$minute;

	$details=$_POST['details'];
	$status=$_POST['status'];
	$remarks=$_POST['remarks'];
	
	
	
	$db=new mysqli("localhost","root","","station");
	$sql="insert into recommendation(recommend_date,recommendation,status";
	$sql.=",remarks)";
	$sql.=" values ('".$recommend_date."','".$details."'";
	$sql.=",'".$status."','".$remarks."')";
	$rs=$db->query($sql);
	
	echo "Data added.";	
	
	
	
}	
?>	
<a href='monitoring menu.php'>Go Back to Monitoring Menu</a>
<form action='recommendations.php' method=post>

<table>
<tr><th colspan=2>Add Recommendations</th></tr>
<tr>
<th>Date</th>
<th>&nbsp;</th>
</tr>
<tr>
<td colspan=2>

<select name='month'>
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
<select name='hour'>
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
<select name='minute'>
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
<select name='amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>
</td>
<td>
</td>
</tr>
<tr>
	<td>Details</td>
	<td><textarea name='details'></textarea></td>

</tr>
<tr>
	<td>Status</td>
	<td><input type=text name='status' /></td>
</tr>
<tr>
	<td>Remarks</td>
	<td><textarea name='remarks'></textarea></td>
</tr>	
<tr>
<td colspan=2 align=center><input type=submit value='Submit' /></td>
</tr>
</table>
</form>