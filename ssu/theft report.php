<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
if(isset($_POST['incident_no'])){
	$incident_id=$_POST['incident_no'];
	$description=$_POST['description'];
	$victim=$_POST['victim'];
	$suspect=$_POST['suspect'];
	$location=$_POST['location'];
	$action_taken=$_POST['action_taken'];
	$status=$_POST['status'];

	
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$hour=$_POST['hour'];
	$minute=$_POST['minute'];
	$amorpm=$_POST['amorpm'];

	$equipment=$_POST['equipment'];
	if($amorpm=="pm"){
		if($hour>12){
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
	$incident_date=$year."-".$month."-".$day." ".$hour.":".$minute;
	
	$type=$_POST['type'];
	$db=new mysqli("localhost","root","","transport");

	$sql="insert into theft(theft_time,description,location,victim,suspect,action_taken,status,station) ";
	$sql.=" values ";
	$sql.="('".$incident_date."',\"".$description."\",\"".$location."\",\"".$victim."\",\"".$suspect."\",";	
	$sql.="\"".$action_taken."\",\"".$status."\",'".$station."')";
	/*
	$sql="insert into incident_report ";
	$sql.="(incident_type,incident_no,level,incident_date,";
	$sql.="description,action_dotc,action_maintenance,duration,equipt)";
	$sql.=" values ";
	$sql.="(\"".$type."\",\"".$incident_id."\",'".$level."','".$incident_date."',";
	$sql.="\"".$description."\",\"".$dotc_taken."\",\"".$maintenance_taken."\",\"".$duration."\",'".$equipment."')";
	*/
	
	$rs=$db->query($sql);
	
	

}
?>
<style type='text/css'>
.rowClass {
	background-color:#eaf2d3;
}
.rowHeading {
	background-color:#a7c942;

	color:rgb(0,51,153);
	color:white;	
	
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
#add_form {
	border-collapse:collapse;

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
<?php
require("monitoring menu.php");
?>
<br>
<br>
<br>
<form action='theft report.php' method='post'>
<table  id='add_form' >
<tr class='rowHeading'>
<th colspan=2>Theft/Pickpocket Report</th>
</tr>
<tr>
<td>Date/Time:</td><td>
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
</td><tr>
<tr><td>Time: </td><td>
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
</tr>


<tr>
<td valign=top>Incident:</td><td> <textarea rows=5 cols=50 name='description'></textarea></td></tr>
<tr>
<td valign=top>Location:</td><td> <textarea rows=5 cols=50 name='location'></textarea></td></tr>

</tr>
<tr>
<td>Station/Depot</td>
<td>
<select name='station'>
	<option value='depot'>Depot</option>

<?php
$db=new mysqli("localhost","root","","station");
$sql="select * from station";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($n=0;$n<$nm;$n++){
	$row=$rs->fetch_assoc();
?>
	<option value='<?php echo $row['id']; ?>'><?php echo $row['station_name']; ?></option>
<?php
}
?>
</select>
</td>
</tr>

<td valign=top>Victim:</td><td> <input type=text name='victim' /></td></tr>
<tr>
<td valign=top>(Alleged) Suspect:</td><td> <input type=text name='suspect' /></td></tr>
<tr>
<td valign=top>Action Taken:</td><td> <textarea rows=5 cols=50 name='action_taken'></textarea></td></tr>
<tr>
<td valign=top>Status/Description:</td><td> <textarea rows=5 cols=50 name='status'></textarea></td></tr>

</tr>
<tr>
<th colspan=2><input type=submit value='Submit' /></th>
</tr>

</table>
</form>

