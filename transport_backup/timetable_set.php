<?php
if(isset($_POST['timetable_code'])){
	$db=new mysqli("localhost","root","","transport");
	
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$availability_date=date("Y-m-d",strtotime($year."-".$month."-".$day));
	$reset=$_POST['timetable_id'];
	
	
	if($_POST['action']=="edit"){
		$update="update	timetable_day set timetable_code='".$_POST['timetable_code']."' where id='".$reset."'";
		$rs=$db->query($update);

	}
	else if($_POST['action']=="new"){
		$update="insert into timetable_day(timetable_code,train_date) values ('".$_POST['timetable_code']."','".$availability_date."')"; 
		$rs=$db->query($update);
	

	
	}

	echo "<script language='javascript'>";
//	echo "window.opener.location.reload();";
	echo "window.opener.location='train_availability.php';";
//	echo "self.close();";
	echo "</script>";

}

if(isset($_GET['reset'])){
	$reset_value=$_GET['reset'];
	$action="edit";

}
if(isset($_GET['set'])){
	$action="new";

}
?>
<form name='timetable_form' id='timetable_form' action='timetable_set.php' method='post'>
<table border=1>
<tr>
<th colspan=2>Set Timetable Code</th>
</tr>
<tr>
<td>Enter Date</td>
<td>
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

</td>
</tr>
<tr>
<td>Enter Timetable Code</td>
<td>
<select name='timetable_code'>
<?php
	$db=new mysqli("localhost","root","","transport");
	$sql="select * from timetable_code";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	for($m=0;$m<$nm;$m++){
		$row=$rs->fetch_assoc();
?>
	<option value='<?php echo $row['id']; ?>'><?php echo $row['code']; ?></option>
<?php	
	}
?>
</select>
</td>
</tr>

</table>
<div align=center><input type=submit value='Submit' /></div>
<input type=hidden name='action' id='action' value='<?php echo $action; ?>'/><input type=hidden name='timetable_id' id='timetable_id' value='<?php echo $reset_value; ?>' />
</form>