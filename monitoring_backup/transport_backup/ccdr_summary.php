<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<style type='text/css'>
.ccdr tr:nth-child(odd)
{
background-color: #dfe7f2;
color: #000000;
}
.ccdr2 tr:nth-child(odd):not(:last-child)
{
background-color: #dfe7f2;
color: #000000;
}
.ccdr tr th:first-child {
	color: rgb(0,51,153);

}

.ccdr td, .ccdr th,.ccdr2 td, .ccdr2 th {
border: 1px solid rgb(185, 201, 254);
padding: 0.3em;
}
.ccdr, .ccdr2 {
border: 1px solid rgb(185, 201, 254);

}
.ccdr #ccdr_heading, .ccdr2 #ccdr_heading {
background-color:rgb(185, 201, 254);
color: rgb(0,51,153);
}
body {
	margin-left:30px;
	margin-right:30px;

}


textarea{ 
	border: 1px solid rgb(185, 201, 254);
	background-color: #dfe7f2;
	color: rgb(0,51,153);
	border-radius: 3px;
}

#edit_form th {
	background-color: rgb(185, 201, 254);
	color: rgb(0,51,153);

}
#edit_form input[type="text"] {
	height:25px; 
	font-weight:bold; 
	font-size:15px; 
	font-family:courier; 
	border: 1px solid rgb(185, 201, 254);
	background-color: #dfe7f2;
	color: rgb(0,51,153);
	border-radius: 3px;

}

.label {
	background-color: rgb(185, 201, 254);
	color: rgb(0,51,153);
	spacing: 2px;
	padding: 5px;
}

.text_input {
	height:25px; 
	font-weight:bold; 
	font-size:15px; 
	font-family:courier; 
	border: 1px solid rgb(185, 201, 254);
	background-color: #dfe7f2;
	color: rgb(0,51,153);
	border-radius: 3px;

}


.rowHeading {
	color: rgb(0,51,153);
	font-weight:bold;
}

select { border: 1px solid rgb(185, 201, 254); color: rgb(0,51,153); background-color: #dfe7f2;  }
</style>

<a href='../index.php'>Go Back to Monitoring Menu</a>
<br>
<br>
<form action='ccdr_summary.php' method='post'>
<?php
$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");


if(isset($_POST['day'])){
	$yy=$_POST['year'];
	$mm=$_POST['month'];
	$dd=$_POST['day'];
	
	$_SESSION['day']=$_POST['day'];
	$_SESSION['month']=$_POST['month'];
	$_SESSION['year']=$_POST['year'];
	
}	



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
<input type=submit value='Retrieve Date' />
</form>


<table>
<tr>
<td align=center valign=top>
<table class='ccdr' border='1px' style='border-collapse:collapse;'>
<tr>
<th  class='rowHeading' rowspan=2>Discipline</th>
<th  class='rowHeading' colspan=4>Number of faults per level</th>
</tr>
<tr>
<th class='rowHeading'>1</th>
<th class='rowHeading'>2</th>
<th class='rowHeading'>3</th>
<th class='rowHeading'>4</th>
</tr>
<?php
	if(isset($_POST['day'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	}
	
	if(isset($_SESSION['day'])){
	$year=$_SESSION['year'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];
	
	
	}
	
	
	$availability_date=date("Y-m-d",strtotime($year."-".$month."-".$day));
?>	
<?php
$db=new mysqli("localhost","root","","transport");
$sql="select * from equipment_type where sequence is not null order by sequence";
$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$problem_type=$row['equipment_code'];

	for($n=1;$n<=4;$n++){
	
		$incident[$n]=0;
		$condition[$n]=0;
		
	}
		
	
//	$incident_sql="select count(*) as level_count,level from train_incident_view where incident_type='".$problem_type."' and train_ava_id in (select id from train_availability where date like '".$availability_date."%%' and status='active') group by level";
	$incident_sql="select count(*) as level_count,level from incident_report where incident_type='".$problem_type."' and incident_date like '".$availability_date."%%' group by level";

	$incident_rs=$db->query($incident_sql);
	$incident_nm=$incident_rs->num_rows;
	for($n=0;$n<$incident_nm;$n++){
		$incident_row=$incident_rs->fetch_assoc();
		$incident[$incident_row['level']]=$incident_row['level_count'];
	
	}
	
	$incident_sql="select count(*) as level_count,level,level_condition from incident_report where incident_type='".$problem_type."' and incident_date like '".$availability_date."%%' group by level,level_condition";
	$incident_rs=$db->query($incident_sql);
	$incident_nm=$incident_rs->num_rows;
	for($n=0;$n<$incident_nm;$n++){
		$incident_row=$incident_rs->fetch_assoc();
		
		if($incident_row['level_condition']==""){
		}
		else {
		
			$condition[$incident_row['level_condition']]=$incident_row['level_count'];
		

		}
	}	
	
	
?>

<tr>
	<th><?php echo $row['equipment_name']; ?> </th>
	<th><?php echo $incident['1']; ?></th>
	<th><?php echo $incident['2']; ?></th>
	<th><?php 
	
	if($condition["1"]==""){
	
	
	}
	else {
		echo $condition["1"]." / ";
	
	}
	echo $incident['3'];  
	?>
	
	
	</th>
	<th><?php 

	if($condition['3']==""){
	
	
	}
	else {
		echo $condition['3']." / ";
	
	}
	
	echo $incident['4']; 
	?></th>
</tr>
<?php
}

?>

</table>


</td>
<td align=center valign=top>
<table class='ccdr' border='1px' style='border-collapse:collapse;'>
<tr>
<td rowspan=2>
LEVEL 1
</td>
<td>
Fault normalized
</td>
</tr>
<tr>
<td>No effect on the operation
</td>
</tr>
<tr>
<td>
LEVEL 2
</td>
<td>Train is removed with replacement</td>
</tr>
<tr>
<td rowspan=2>
LEVEL 3
</td>
<td>
Train is removed without replacement</td>
</tr>
<tr>
<td>Cancellation of loops and insertion</td>
</tr>
<tr>
<td rowspan=2>
LEVEL 4
</td>
<td>Service interruption</td>
</tr>
<tr>
<td>Cancellation of loops. Ticket refunds.</td>
</tr>
</table>

</td>
<td align=center valign=top>
<table class='ccdr' border='1px' style='border-collapse:collapse;'>
<tr class='rowHeading'>
<th class='rowHeading' colspan=2>AM</th>
<th class='rowHeading' colspan=2>PM</th>
</tr>
<tr>
<?php
for($i=0;$i<2;$i++){
?>
	<td align=center>Cancelled<br> Departure</td>
	<td align=center>Loop<br> Cancelled</td>

<?php
}
?>
</tr>
<tr>
<?php
$am_sql="select count(*) as am_count from train_availability where date like '".$availability_date."%%' and status='cancelled' and date between '".$availability_date." 00:00:01' and '".$availability_date." 12:00:00'";
$am_rs=$db->query($am_sql);
$am_nm=$am_rs->num_rows;

$am=0;
if($am_nm>0){
	$am_row=$am_rs->fetch_assoc();
	$am=$am_row['am_count'];
}

$am_sql="select count(*) as pm_count from train_availability where date like '".$availability_date."%%' and status='cancelled' and date between '".$availability_date." 12:00:01' and '".$availability_date." 00:00:00'";

$am_rs=$db->query($am_sql);
$am_nm=$am_rs->num_rows;

$pm=0;
if($am_nm>0){
	$am_row=$am_rs->fetch_assoc();
	$pm=$am_row['pm_count'];
}

$am_sql="select sum(cancel) as am_count from train_incident_view where train_ava_id in (select id from train_availability where date like '".$availability_date."%%' and status='active') and incident_date between '".$availability_date." 00:00:01' and '".$availability_date." 12:00:00'";
$am_rs=$db->query($am_sql);
$am_nm=$am_rs->num_rows;

$am_cancel=0;
if($am_nm>0){
	$am_row=$am_rs->fetch_assoc();
	$am_cancel=$am_row['am_count']*1;
}

$am_sql="select sum(cancel) as pm_count from train_incident_view where train_ava_id in (select id from train_availability where date like '".$availability_date."%%' and status='active') and incident_date between '".$availability_date." 12:00:01' and '".$availability_date." 00:00:00'";

$am_rs=$db->query($am_sql);
$am_nm=$am_rs->num_rows;

$pm_cancel=0;
if($am_nm>0){
	$am_row=$am_rs->fetch_assoc();
	$pm_cancel=$am_row['pm_count']*1;
}


?>
<td align=center><?php echo $am; ?></td>
<td align=center><?php echo $am_cancel; ?></td>
<td align=center><?php echo $pm; ?></td>
<td align=center><?php echo $pm_cancel; ?></td>

</tr>
<tr>
<td colspan=2 align=center>Planned Loops <br> Per day</td>
<td colspan=2 align=center>Actual Loops <br>Per day</td>
</tr>
<tr>
<?php
$planned=0;
$actual=0;
$percentage=0;

$planned_sql="select * from timetable_day inner join timetable_code on timetable_code=timetable_code.id where train_date='".$availability_date."'";
$planned_rs=$db->query($planned_sql);
$planned_nm=$planned_rs->num_rows;
if($planned_nm>0){

$planned_row=$planned_rs->fetch_assoc();
$am_sql="select sum(cancel) as cancel from train_incident_view where train_ava_id in (select id from train_availability where date like '".$availability_date."%%' and status='active')";

$am_rs=$db->query($am_sql);
$am_nm=$am_rs->num_rows;
$am_row=$am_rs->fetch_assoc();
	$planned=$planned_row['planned_loops'];
	$actual=$planned_row['planned_loops']*1-$am_row['cancel']*1;

	
	$percentage=number_format(($actual/$planned)*100,2);
	
	
}
?>
<td colspan=2 align=center><?php echo $planned; ?></td>
<td colspan=2 align=center><?php echo $actual; ?></td>

</tr>
<tr>
<?php 

$train_sql="select * from train_availability where date like '".$availability_date."%%' and status='active'";
$train_rs=$db->query($train_sql);
$train_nm=$train_rs->num_rows;

?>
<td colspan=2 align=center>Actual Loops<br>Performed</td>
<td colspan=2 align=center>No. Of LRV<br>Utilities/day</td>

</tr>
<tr>
<td colspan=2 align=center><?php echo $percentage."%"; ?></td>
<td colspan=2 align=center><?php echo $train_nm; ?></td>
</tr>
</table>
</td>
</tr>



</table>
<br>
<a href='#' onclick='window.open("generate_sccdr.php?sccdr=<?php echo $availability_date; ?>");'>Generate Printout</a>