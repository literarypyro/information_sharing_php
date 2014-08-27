<?php
$db=new mysqli("localhost","root","","station");

?>
<style type="text/css">
body {
	background-color:rgb(110,172,161); 

	color:white;

}
.nav {
	background-color: white;
	color:black;

}
a {
	color:white;
}	

h3 {

color: white;

}
.header {

	background-color: rgb(66,85,128);

	color:white;

}

.block_1,.block_1a {
	background-color:rgb(175,104,102);

	color:white;
}


.block_2,.block_2a {
	background-color: rgb(162,166,169);

	color:white;


}

.block_3,.block_3a {
	background-color:rgb(175,104,102);

	color:white;


}

.block_4, .block_4a {

	background-color: rgb(162,166,169);
	color:white;


}

.block_5, .block_5a {
	background-color:rgb(175,104,102);
	color:white;


}
</style>

<?php
if(isset($_POST['leading_car_time'])){
	$leading_car_shift=$_POST['leading_car_shift'];
	$leading_car_time=$_POST['leading_car_time'];
	
	$leading_elderly=$_POST['leading_elderly'];
	$leading_female=$_POST['leading_female'];
	$leading_disabled=$_POST['leading_disabled'];
	$leading_children=$_POST['leading_children'];
	
	$daily_id=$_POST['daily_id'];
	
	$sql="select * from leading_car where shift='".$leading_car_shift."' and stat_time='".$leading_car_time."' and segregation_id='".$daily_id."'";
	
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		
		$update="update leading_car set female='".$leading_female."',elderly='".$leading_elderly."',handicapped='".$leading_disabled."',children='".$leading_children."' where id='".$row['id']."'";
		$updateRS=$db->query($update);
		
		
	}
	else {
		$update="insert into leading_car(shift,stat_time,segregation_id,female,elderly,handicapped,children) values ";
		$update.="('".$leading_car_shift."','".$leading_car_time."','".$daily_id."','".$leading_female."','".$leading_elderly."','".$leading_disabled."','".$leading_children."')";
		$updateRS=$db->query($update);
	}




}

if(isset($_POST['next_car_time'])){
	$next_car_shift=$_POST['next_car_shift'];
	$next_car_time=$_POST['next_car_time'];
	
	$middle_car_volume=$_POST['middle_car_volume'];
	$last_car_volume=$_POST['last_car_volume'];
	
	$daily_id=$_POST['daily_id'];
	
	$sql="select * from next_car where shift='".$next_car_shift."' and stat_time='".$next_car_time."' and segregation_id='".$daily_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		
		$update="update next_car set middle_car='".$middle_car_volume."',last_car='".$last_car_volume."' where id='".$row['id']."'";
		$updateRS=$db->query($update);
		
		
	}
	else {
		$update="insert into next_car(shift,stat_time,segregation_id,middle_car,last_car) values ";
		$update.="('".$next_car_shift."','".$next_car_time."','".$daily_id."','".$middle_car_volume."','".$last_car_volume."')";
		
		$updateRS=$db->query($update);
	}
	
	
	
	
}

if(isset($_POST['s1_assessment'])){
	$s1_assessment=$_POST['s1_assessment'];
	$s2_assessment=$_POST['s2_assessment'];
	$s3_assessment=$_POST['s3_assessment'];

	$daily_id=$_POST['daily_id'];

	$sql="select * from dedicated_car_assessment where segregation_id='".$daily_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();
	
		$update="update dedicated_car_assessment set s1='".$s1_assessment."',s2='".$s2_assessment."',s3='".$s3_assessment."' where id='".$row['id']."'";
		$updateRS=$db->query($update);
	
	
	}
	else {
		$update="insert into dedicated_car_assessment(s1,s2,s3,segregation_id) values ('".$s1_assessment."','".$s2_assessment."','".$s3_assessment."','".$daily_id."')";
		$updateRS=$db->query($update);
	}
}

if(isset($_POST['comment'])){
	$daily_id=$_POST['daily_id'];
	$comment=$_POST['comment'];
	$comment_type=$_POST['comment_type'];

	$update="insert into segregation_comments(segregation_id,type,details) values ('".$daily_id."','".$comment_type."',\"".$comment."\")";
	$updateRS=$db->query($update);
	

}

if(isset($_POST['issues'])){
	$daily_id=$_POST['daily_id'];
	$remarks_type=$_POST['remarks_type'];
	$issues=$_POST['issues'];
	
	$update="insert into segregation_remarks(segregation_id,remarks_type,details) values ('".$daily_id."','".$remarks_type."',\"".$issues."\")";
	$updateRS=$db->query($update);


}

?>
<body>
<?php 
require("monitoring menu.php");
?>
<div id='fullForm'>
<br>
<br>


<form action='segregation_daily.php' method='post'>
<?php
$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");
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

<select name='station'>
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
<input type=submit value='Get Records' />
</form>


<br>
<br>
<?php
if((isset($_POST['month']))||(isset($_POST['daily_id']))){
	
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

		$id_sql="select * from segregation where date='".$daily_date."' and station='".$station."'";
		$id_rs=$db->query($id_sql);
		
		$id_nm=$id_rs->num_rows;
		
		if($id_nm>0){
			$id_row=$id_rs->fetch_assoc();
			$daily_id=$id_row['id'];
		}
		else {
			$update="insert into segregation(date,station) values ('".$daily_date."','".$station."')";
			$updateRS=$db->query($update);
			$daily_id=$db->insert_id;	
		}

	}
	if(isset($_POST['daily_id'])){
		$id_sql="select * from segregation where id='".$daily_id."'";
		$id_rs=$db->query($id_sql);
		
		$id_nm=$id_rs->num_rows;

		if($id_nm>0){
			$id_row=$id_rs->fetch_assoc();
			$daily_id=$id_row['id'];
			
			$daily_date=$id_row['date'];
			$daily_name=date("F d, Y",strtotime($daily_date));
	
			$sql="select * from station where id='".$id_row['station']."' limit 1";
			$rs=$db->query($sql);	
			$row=$rs->fetch_assoc();
			
			$station_name=$row['station_name'];		
			
			
		}


		
	}
	
	
?>

<?php

$time_statSQL="select * from segregation_time_stat";
$time_statRS=$db->query($time_statSQL);
$time_statNM=$time_statRS->num_rows;
for($i=0;$i<$time_statNM;$i++){
	$time_statRow=$time_statRS->fetch_assoc();
	
	$lead[$time_statRow['code']]["female"]="";
	$lead[$time_statRow['code']]["elderly"]="";
	$lead[$time_statRow['code']]["handicapped"]="";
	$lead[$time_statRow['code']]["children"]="";

}

$leadSQL="select * from leading_car where segregation_id='".$daily_id."'";

$leadRS=$db->query($leadSQL);
$leadNM=$leadRS->num_rows;

for($i=0;$i<$leadNM;$i++){
	$leadRow=$leadRS->fetch_assoc();
	$lead[$leadRow['stat_time']]['female']=$leadRow['female'];
	$lead[$leadRow['stat_time']]['elderly']=$leadRow['elderly'];
	$lead[$leadRow['stat_time']]['handicapped']=$leadRow['handicapped'];
	$lead[$leadRow['stat_time']]['children']=$leadRow['children'];



}






?>
<table width=100% >
<tr class='header'>
<td width=50%>
Station:
<?php
echo $station_name;
?>
</td>
<td width=50%>
Date: 
<?php
echo $daily_name;
?>
</td>
</tr>
</table>
<br>

Leading Car
<table width=100% style='border-collapse:collapse;' >

<tr class='header'>
<th>Shift</th>
<th>Status of Operation</th>
<th>Time</th>
<th>Female</th>
<th>Elderly</th>
<th>Disabled</th>
<th>Children</th>
</tr>
<tr class='block_1'>
<th class='block_1a' rowspan=2>1</th>
<th>Off Peak</th>
<th>Start of Revenue to 7AM</th>
<th><?php echo $lead['start']['female']; ?></th>
<th><?php echo $lead['start']['elderly']; ?></th>
<th><?php echo $lead['start']['handicapped']; ?></th>
<th><?php echo $lead['start']['children']; ?></th>

</tr>
<tr class='block_2'>
<th>Morning Peak Hour</th>
<th>7AM - 9AM</th>
<th><?php echo $lead['am_peak']['female']; ?></th>
<th><?php echo $lead['am_peak']['elderly']; ?></th>
<th><?php echo $lead['am_peak']['handicapped']; ?></th>
<th><?php echo $lead['am_peak']['children']; ?></th>
</tr>
<tr  class='block_3'>
<th  class='block_2a' >1 / 2</th>
<th>Off Peak</th>
<th>9AM - 5PM</th>
<th><?php echo $lead['off']['female']; ?></th>
<th><?php echo $lead['off']['elderly']; ?></th>
<th><?php echo $lead['off']['handicapped']; ?></th>
<th><?php echo $lead['off']['children']; ?></th>
</tr>
<tr  class='block_4'>
<th class='block_4a'>2</th>
<th>Afternoon Peak Hour</th>
<th>5PM - 7PM</th>
<th><?php echo $lead['pm_peak']['female']; ?></th>
<th><?php echo $lead['pm_peak']['elderly']; ?></th>
<th><?php echo $lead['pm_peak']['handicapped']; ?></th>
<th><?php echo $lead['pm_peak']['children']; ?></th>
</tr>
<tr class='block_5'>
<th class='block_5a'>2/3</th>
<th>Off Peak</th>
<th>7PM - end of revenue</th>
<th><?php echo $lead['end']['female']; ?></th>
<th><?php echo $lead['end']['elderly']; ?></th>
<th><?php echo $lead['end']['handicapped']; ?></th>
<th><?php echo $lead['end']['children']; ?></th>
</tr>




</table>
<br>
<form action='segregation_daily.php' method='post'>
<table  style='border-collapse:collapse;'  class='header'>
<tr >
<th class='block_1'>Shift</th>
<th class='block_1'>Time</th>

<th class='block_1'>Female</th>
<th class='block_1'>Elderly</th>
<th class='block_1'>Disabled</th>
<th  class='block_5'>Children</th>


</tr>
<tr>
<td>
<select name='leading_car_shift'>
<?php
$sql="select * from segregation_shift";
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
<td>
<select name='leading_car_time'>
<?php
$sql="select * from segregation_time_stat";
$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<option value='<?php echo $row['code']; ?>'><?php echo $row['time']; ?></option>

<?php
}
?>
</select>
</td>
<td><input type=text name='leading_female' />
</td>
<td><input type=text name='leading_elderly' />
</td>
<td><input type=text name='leading_disabled' />
</td>
<td><input type=text name='leading_children' />
</td>
<td>
<input type='hidden' name='daily_id' value="<?php echo $daily_id; ?>" />
<input type=submit value='Update' />


</td>
</tr>

</table>
</form>

<br>
<?php

$time_statSQL="select * from segregation_time_stat";
$time_statRS=$db->query($time_statSQL);
$time_statNM=$time_statRS->num_rows;
for($i=0;$i<$time_statNM;$i++){
	$time_statRow=$time_statRS->fetch_assoc();
	
	$next["middle_car"][$time_statRow['code']]["light"]="";
	$next["middle_car"][$time_statRow['code']]["mod"]="";
	$next["middle_car"][$time_statRow['code']]["full"]="";

	$next["last_car"][$time_statRow['code']]["light"]="";
	$next["last_car"][$time_statRow['code']]["mod"]="";
	$next["last_car"][$time_statRow['code']]["full"]="";

}
$nextSQL="select * from next_car where segregation_id='".$daily_id."'";
$nextRS=$db->query($nextSQL);
$nextNM=$nextRS->num_rows;

for($i=0;$i<$nextNM;$i++){
	$nextRow=$nextRS->fetch_assoc();
	$next["middle_car"][$nextRow['stat_time']][$nextRow['middle_car']]="X";
	$next["last_car"][$nextRow['stat_time']][$nextRow['last_car']]="X";


}
?>
2nd and 3rd Car
<table width=100% style='border-collapse:collapse;' >
<tr class='header'>
<th rowspan=2>Shift</th>
<th rowspan=2>Status of Operation</th>
<th rowspan=2>Time</th>
<th colspan=3>Middle Car (Male)</th>
<th colspan=3>Last Car (Male)</th>
</tr>
<tr>
<th class='block_1a'>Light</th>
<th class='block_1a'>Moderate</th>
<th class='block_1a'>Full</th>
<th class='block_1a'>Light</th>
<th class='block_1a'>Moderate</th>
<th class='block_1a'>Full</th>
</tr>
<tr class='block_1'>
<th class='block_1a' rowspan=2>1</th>
<th>Off Peak</th>
<th>Start of Revenue to 7AM</th>
<th><?php echo $next['middle_car']['start']['light']; ?></th>
<th><?php echo $next['middle_car']['start']['mod']; ?></th>
<th><?php echo $next['middle_car']['start']['full']; ?></th>
<th><?php echo $next['last_car']['start']['light']; ?></th>
<th><?php echo $next['last_car']['start']['mod']; ?></th>
<th><?php echo $next['last_car']['start']['full']; ?></th>
</tr>
<tr class='block_2'>
<th >Morning Peak Hour</th>
<th>7AM - 9AM</th>
<th><?php echo $next['middle_car']['am_peak']['light']; ?></th>
<th><?php echo $next['middle_car']['am_peak']['mod']; ?></th>
<th><?php echo $next['middle_car']['am_peak']['full']; ?></th>
<th><?php echo $next['last_car']['am_peak']['light']; ?></th>
<th><?php echo $next['last_car']['am_peak']['mod']; ?></th>
<th><?php echo $next['last_car']['am_peak']['full']; ?></th>
</tr>
<tr class='block_3'>
<th class='block_2a'>1 / 2</th>
<th>Off Peak</th>
<th>9AM - 5PM</th>
<th><?php echo $next['middle_car']['off']['light']; ?></th>
<th><?php echo $next['middle_car']['off']['mod']; ?></th>
<th><?php echo $next['middle_car']['off']['full']; ?></th>
<th><?php echo $next['last_car']['off']['light']; ?></th>
<th><?php echo $next['last_car']['off']['mod']; ?></th>
<th><?php echo $next['last_car']['off']['full']; ?></th>
</tr>
<tr class='block_4'>
<th class='block_4a'>2</th>
<th>Afternoon Peak Hour</th>
<th>5PM - 7PM</th>
<th><?php echo $next['middle_car']['pm_peak']['light']; ?></th>
<th><?php echo $next['middle_car']['pm_peak']['mod']; ?></th>
<th><?php echo $next['middle_car']['pm_peak']['full']; ?></th>
<th><?php echo $next['last_car']['pm_peak']['light']; ?></th>
<th><?php echo $next['last_car']['pm_peak']['mod']; ?></th>
<th><?php echo $next['last_car']['pm_peak']['full']; ?></th>
</tr>
<tr class='block_5'>
<th class='block_5a'>2/3</th>
<th>Off Peak</th>
<th>7PM - end of revenue</th>
<th><?php echo $next['middle_car']['end']['light']; ?></th>
<th><?php echo $next['middle_car']['end']['mod']; ?></th>
<th><?php echo $next['middle_car']['end']['full']; ?></th>
<th><?php echo $next['last_car']['end']['light']; ?></th>
<th><?php echo $next['last_car']['end']['mod']; ?></th>
<th><?php echo $next['last_car']['end']['full']; ?></th>
</tr>
</table>
<br>
<form action='segregation_daily.php' method='post'>
<table class='header' style='border-collapse:collapse;'>
<tr>
<th class='block_3a'>Shift</th>
<th class='block_3a'>Time</th>

<th class='block_3a'>Middle Car</th>
<th class='block_5'>Last Car</th>
</tr>
<tr>
<td>
<select name='next_car_shift'>
<?php
$sql="select * from segregation_shift";
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
<td>
<select name='next_car_time'>
<?php
$sql="select * from segregation_time_stat";
$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<option value='<?php echo $row['code']; ?>'><?php echo $row['time']; ?></option>

<?php
}
?>
</select>
</td>
<td>
<select name='middle_car_volume'>
<?php
$sql="select * from segregation_volume";
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
<td>
<select name='last_car_volume'>
<?php
$sql="select * from segregation_volume";
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
<td>
<input type='hidden' name='daily_id' value="<?php echo $daily_id; ?>" />
<input type=submit value='Update' />
</td>
</tr>
</table>
</form>
<br>
<?php
$s1_assess["ne"]="";
$s1_assess["je"]="";
$s1_assess["mte"]="";

$s2_assess["ne"]="";
$s2_assess["je"]="";
$s2_assess["mte"]="";

$s3_assess["ne"]="";
$s3_assess["je"]="";
$s4_assess["mte"]="";


$assessmentSQL="select * from dedicated_car_assessment where segregation_id='".$daily_id."'";
$assessmentRS=$db->query($assessmentSQL);
$assessmentNM=$assessmentRS->num_rows;
if($assessmentNM>0){
	$assessmentRow=$assessmentRS->fetch_assoc();
	$s1=$assessmentRow['s1'];
	$s2=$assessmentRow['s2'];
	$s3=$assessmentRow['s3'];

	$s1_assess[strtolower($s1)]="X";
	$s2_assess[strtolower($s2)]="X";
	$s3_assess[strtolower($s3)]="X";
	
	
	
}


?>
Assessment of Dedicated Car Occupancy
<table width=100% style='border-collapse:collapse;' >
<tr class='header'>
<th>Shift</th>
<th>Not Enough</th>
<th>Just Enough</th>
<th>More Than Enough</th>
</tr>
<tr class='block_1'>
<th>1</th>
<th><?php echo $s1_assess["ne"]; ?></th>
<th><?php echo $s1_assess["je"]; ?></th>
<th><?php echo $s1_assess["mte"]; ?></th>
</tr>
<tr class='block_2'>
<th>2</th>
<th><?php echo $s2_assess["ne"]; ?></th>
<th><?php echo $s2_assess["je"]; ?></th>
<th><?php echo $s2_assess["mte"]; ?></th>
</tr>
<tr class='block_3'>
<th>3</th>
<th><?php echo $s3_assess["ne"]; ?></th>
<th><?php echo $s3_assess["je"]; ?></th>
<th><?php echo $s3_assess["mte"]; ?></th>
</tr>
</table>
<br>
<form action='segregation_daily.php' method='post'>
<table style='border-collapse:collapse;'>
<tr>
<tr class='header'><th colspan=3>Assessment</th></tr>
<th class='block_5'>S1</th>
<th class='block_5'>S2</th>
<th class='block_5'>S3</th>
</tr>
<tr>
<td>
<select name='s1_assessment'>
<?php
$sql="select * from assessment";
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
<td>
<select name='s2_assessment'>
	<option></option>
<?php
$sql="select * from assessment";
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
<td>
<select name='s3_assessment'>
	<option></option>

<?php
$sql="select * from assessment";
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
<tr >
<td colspan=3 align=center>
<input type='hidden' name='daily_id' value="<?php echo $daily_id; ?>" />
<input type=submit value='Update' />
</td>
</tr>
</table>
</form>
<br>
Comments/Complaints from Passengers
<table style='border:1px solid gray'>
<?php
$issuesSQL="select * from segregation_comments where segregation_id='".$daily_id."'";
$issuesRS=$db->query($issuesSQL);
$issuesNM=$issuesRS->num_rows;
for($i=0;$i<$issuesNM;$i++){	
	$issuesRow=$issuesRS->fetch_assoc();
?>
<tr>
<th>
<?php echo $issuesRow['details']; ?>
</th>
</tr>
<?php
}
?>
</table>
<br>
<form action='segregation_daily.php' method='post'>
<table style='border-collapse:collapse'>
<tr class='header'>
<th colspan=2>Add Comment</th>
</tr>
<tr>
<td class='block_3' valign=top style='vertical-align:top'>
<select name='comment_type'>
<option value='comment'>Comments</option>
<option value='complaint'>Complaints</option>
</select>
</td>
<td class='block_2' >
<textarea name='comment' cols=40 rows=4>
</textarea>

</td>
</tr>
<tr>

<th colspan=2>
<input type='hidden' name='daily_id' value="<?php echo $daily_id; ?>" />
<input type=submit value='Submit' /></th>
</tr>


</table>
</form>
<br>
Issues, Concerns and Recommendations
<table style='border:1px solid gray'>
<?php
$issuesSQL="select * from segregation_remarks where segregation_id='".$daily_id."'";
$issuesRS=$db->query($issuesSQL);
$issuesNM=$issuesRS->num_rows;
for($i=0;$i<$issuesNM;$i++){	
	$issuesRow=$issuesRS->fetch_assoc();
?>
<tr >
<th>
<?php echo $issuesRow['details']; ?>
</th>
</tr>
<?php
}
?>
</table>
<br>
<form action='segregation_daily.php' method='post'>
<table style='border-collapse:collapse;'>
<tr class='header'>
<th colspan=2>Add Issues/Concerns/Recommendations</th>
</tr>
<tr>
<td  class='block_3a' valign=top>
<select name='remarks_type'>
<?php
$remarks_sql="select * from s_remarks_type";
$remarks_rs=$db->query($remarks_sql);	
$remarks_nm=$remarks_rs->num_rows;
for($i=0;$i<$remarks_nm;$i++){
	$remarks_row=$remarks_rs->fetch_assoc();	
?>
	<option value="<?php echo $remarks_row['code']; ?>"><?php echo $remarks_row['name']; ?></option>

<?php	
}	
?>
</select>
</td>
<td class='block_2a' >
<textarea name='issues' cols=40 rows=4>
</textarea>

</td>
</tr>
<tr>
<th colspan=2>
<input type='hidden' name='daily_id' value="<?php echo $daily_id; ?>" />
<input type=submit value='Submit' /></th>
</tr>
</table>
</form>
<?php
}
?>
</div>
</body>