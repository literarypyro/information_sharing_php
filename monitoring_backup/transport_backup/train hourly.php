<?php
session_start();
?>
<?php
function is_decimal( $val )
{
    return is_numeric( $val ) && floor( $val ) != $val;
}
?>
<style type='text/css'>
.rowClass {
	background-color:#eaf2d3;
}
.rowHeading {
	background-color:#a7c942;
	color:rgb(0,51,153);
}
.train_ava td{
	border: 1px solid #a7c942;
	color: rgb(0,51,153);
	cellpadding: 5px;

}
 .train_ava th {
	border: 1px solid #a7c942;
	cellpadding: 5px;
	
}
body {
	margin-left:30px;
	margin-right:30px;

}
input[type="text"]{ 
	height:25px; 
	font-weight:bold; 
	font-size:15px; 
	font-family:courier; 
	border: 1px solid #C6C6C6; 
	background-color: rgb(185, 201, 254);  
	color: rgb(0,51,153);
	border-radius: 3px;
}
#cellHeading {
	background-image: -o-linear-gradient(bottom, rgb(185, 201, 254) 38%, #4ad 62%);
	background-image: -moz-linear-gradient(bottom, rgb(185, 201, 254) 38%,#4ad 62%);
	background-image: -moz-linear-gradient(bottom, rgb(185, 201, 254) 38%, #4ad 62%);
	background-image: -webkit-gradient(linear, left bottom, left top, color-stop(0.38, rgb(185, 201, 254)), color-stop(0.62, #4ad));
	background-image: -webkit-linear-gradient(bottom, rgb(185, 201, 254) 38%,#4ad 62%);
	background-image: -ms-linear-gradient(bottom, rgb(185, 201, 254) 38%, #4ad 62%);
	background-image: linear-gradient(bottom, rgb(185, 201, 254) 38%, #4ad 62%);

	background-color: rgb(185, 201, 254);  

	color: rgb(0,51,153);
	padding:5px;
	-moz-border-radius: 5px;
	border-radius: 5px;

}
input[type="text"]:focus {
	background-color:rgb(158,27,32);
	color:white;

}
textarea:focus {
	background-color:rgb(158,27,32);
	color:white;
	font-weight:bold;

}
.date {
	text-style:bold;
	font-size:20px;

}
textarea{ 
	border: 1px solid rgb(185, 201, 254);
	background-color: rgb(185, 201, 254);  
	color: rgb(0,51,153);
	border-radius: 3px;
}
#add_form th{
background-color: #4ad;  
}

#add_form td:nth-child(odd) {
background-color: #33aa55; 
color:white;
font-weight:bold;
padding:5px;

}
#add_form td:last-child{
background-color:white;

}


#add_form td:nth-child(even) {
background-color: rgb(185, 201, 254);  
border:1px solid #4ad;

}


select { border: 1px solid rgb(185, 201, 254); color: rgb(0,51,153); background-color: rgb(185, 201, 254);  }
</style>
<body>
<a href='../index.php'>Go Back to Monitoring Menu</a>
<br>
<br>
<form action='train hourly.php' method='post'>
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
<input type=submit value='Access Monitoring' />
</form>
<?php
$db=new mysqli("localhost","root","","transport");

$timetable_code="";
if((isset($_POST['month']))||(isset($_SESSION['month']))){
	if(isset($_POST['month'])){
		$month=$_POST['month'];
		$day=$_POST['day'];
		$year=$_POST['year'];
		
		$_SESSION['month']=$_POST['month'];
		$_SESSION['day']=$_POST['day'];
		$_SESSION['year']=$_POST['year'];
	
	}
	else if(isset($_SESSION['month'])){
		$month=$_SESSION['month'];
		$day=$_SESSION['day'];
		$year=$_SESSION['year'];
	
	
	}

	$timetable=date("Y-m-d",strtotime($year."-".$month."-".$day));
	
	$sql="select * from timetable_day where train_date like '".$timetable."%%'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;

	if($nm>0){
		$row=$rs->fetch_assoc();
		$timetable_code=$row['timetable_code'];
	
	}
	
echo "<h2>".date("F d, Y",strtotime($timetable))."</h2><br>";	
}



?>
<table class='train_ava' width=100%>
<tr class='rowHeading'>
<th>Time</th>
<th>Headway</th>
<th>No. of Cars Req'd.</th>
<th>No. of Cars Provided</th>
<th>Reserve Req'd.</th>
<th>Reserve Provided</th>
<th>Cancelled Departure</th>
<th>Cancelled Loop</th>
<th>Incident No.</th>
</tr>
<?php
$db=new mysqli("localhost","root","","transport");

if($timetable_code==""){
}
else {

	$headway="select * from headway_1 inner join headway_2 on headway_1.timetable_code=headway_2.timetable_code where headway_1.timetable_code='".$timetable_code."'";
	$hRS=$db->query($headway);
	
	$hRow=$hRS->fetch_assoc();
	
	$reserve="select * from reserve_1 where date='".$timetable."'";
	$rRS=$db->query($reserve);
	
	$rRow=$rRS->fetch_assoc();


	$reserve="select * from reserve_2 where date='".$timetable."'";
	$rRS=$db->query($reserve);
	
	$rRow2=$rRS->fetch_assoc();
	
	
	
	$sql="select * from train_availability_required inner join timetable_hour on train_availability_required.time=timetable_hour.time where timetable_code='".$timetable_code."'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$hour=$row['time'];
		$nexthour=$hour+1;
		

?>		
	<tr <?php if($i%2>0){ echo "class='rowClass'"; } ?>>
		<td><?php echo $row['label']; ?></td>
		<td>
		<?php 
		
		if($hour==201){
		echo $hRow['h_20']; 
		
		}
		else if($hour==203){
		echo $hRow['h_21']; 
		
		}
		else {
		echo $hRow['h_'.$hour]; 
		}
		
		?></td>
		<td align=center><?php echo $row['cars_required']; ?></td>
		<?php
		
		
		if($hour=="201"){
			$timestamp="between '".date("Y-m-d H:i:s",strtotime($timetable." 20:01:00"))."' and '".date("Y-m-d H:i:s",strtotime($timetable." 20:30:00"))."'";

		}
		else if($hour=="203"){
			$timestamp="between '".date("Y-m-d H:i:s",strtotime($timetable." 20:31:00"))."' and '".date("Y-m-d H:i:s",strtotime($timetable." 21:00:00"))."'";

		}
		else {
			$timestamp="between '".date("Y-m-d H:i:s",strtotime($timetable." ".$hour.":01:00"))."' and '".date("Y-m-d H:i:s",strtotime($timetable." ".$nexthour.":00:00"))."'";
		}
		

		$car_sql="select * from train_availability inner join train_ava_time on train_availability.id=train_ava_time.train_ava_id where date ".$timestamp."  and status='active' and type='revenue' and insert_time is not null";

		$car_rs=$db->query($car_sql);
		$car_nm=$car_rs->num_rows;
		
		$cars_provided=$car_nm*3;
		
	
		
		
		for($n=0;$n<$car_nm;$n++){
			$car_row=$car_rs->fetch_assoc();
			if($car_row['cancel_loop']=="SB"){
//				$loop_cancelled+=.5;
			}
			else if($car_row['cancel_loop']=="NB"){
//				$loop_cancelled++;
			
			}
			
		}
		
		
		$car_sql3="select sum(cancel) as cancel from train_incident_view where train_ava_id in (select id from train_availability where date like '".$timetable."%%' and status='active') and incident_date ".$timestamp."";
		
		$car_rs3=$db->query($car_sql3);
		$car_nm3=$car_rs3->num_rows;
		if($car_nm3>0){
			$car_row3=$car_rs3->fetch_assoc();
			$loop_cancelled=$car_row3['cancel']*1;
		
		}
		
		

		$car_sql2="select * from train_availability where date ".$timestamp." and type='revenue' and status='cancelled'";

		$car_rs2=$db->query($car_sql2);
		$car_nm2=$car_rs2->num_rows;
		
		$cars_cancelled=$car_nm2;
		
	//	$loop_cancelled+=$cars_cancelled;
		
		if(is_decimal($loop_cancelled)){
			$loop_cancelled=round($loop_cancelled)." 1/2";
		
		}
		
		
		?>
		
		
		<td align=center><?php echo $cars_provided; ?></td>
		<td align=center><?php echo $row['reserve_required']; ?></td>
		
		<td align=center>
		<?php 
		
		if($hour=="201"){
			$hourLabel="20";
		
		}
		else if($hour=="203"){
			$hourLabel="21";
		}
		else {
			$hourLabel=$hour;
		
		}
		
		
		if($hourLabel<=12){
			echo $rRow['h_'.$hourLabel]; 
		}
		else {
			echo $rRow2['h_'.$hourLabel]; 
		
		}
		
		?>
		
		</td>
		<td align=center><?php echo $cars_cancelled; ?></td>
		<td align=center><?php echo $loop_cancelled; ?></td>
		<td>
		<?php
		
		$incident_sql="select * from train_incident_view inner join incident_description on train_incident_view.incident_id=incident_description.incident_id where train_ava_id in (select id from train_availability where date ".$timestamp." and type='revenue')";

		$incident_rs=$db->query($incident_sql);
		
		$incident_nm=$incident_rs->num_rows;
		if($incident_nm>0){
		for($m=0;$m<$incident_nm;$m++){
			$incident_row=$incident_rs->fetch_assoc();
			if($m==0){
				echo "<a href='#' onclick='window.open(\"edit_ccdr.php?ir=".$incident_row['incident_id']."\")'>".$incident_row['incident_no']."(".$incident_row['index_no'].")</a>";
			}
			else {
				echo ", <a href='#' onclick='window.open(\"edit_ccdr.php?ir=".$incident_row['incident_id']."\")'>".$incident_row['incident_no']."(".$incident_row['index_no'].")</a>";
			}
		}
		}
		else {
			echo "&nbsp;";			
		
		}
		
		


		
		?>
		</td>
	</tr>

<?php	
	
	}
}



?>
</table>
<br>
<a href='#' onclick='window.open("generate_star.php?star_id=<?php echo $timetable_code; ?>&timetable=<?php echo $timetable; ?>");'>Generate Printout</a>