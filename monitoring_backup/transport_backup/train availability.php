<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","transport");

function getTrainDriver($id){

$db=new mysqli("localhost","root","","transport");
	$sql="select * from train_driver where id='".$id."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	
	$name=$row['position']." ".substr($row['firstName'],0,1).". ".$row['lastName'];
	return $name;


}

function getOrdinal($number){
$ends = array('th','st','nd','rd','th','th','th','th','th','th');
if (($number %100) >= 11 && ($number%100) <= 13)
   $abbreviation = $number. 'th';
else
   $abbreviation = $number. $ends[$number % 10];

   
 return $abbreviation;  

}

if(isset($_POST['index_no'])){

	$index_no=$_POST['index_no'];
	$lpam_id=$_POST['lpam_id'];

	
	$car_a=$_POST['car_1'];
	$car_b=$_POST['car_2'];

	$car_c=$_POST['car_3'];
	
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$hour=$_POST['hour'];
	$minute=$_POST['minute'];
	$amorpm=$_POST['amorpm'];

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
	$availability_date=date("Y-m-d H:i",strtotime($year."-".$month."-".$day." ".$hour.":".$minute));

	$update="insert into train_availability(index_no,date,car_a,car_b,car_c,lpam_id,status,type) values ";
	$update.="('".$index_no."','".$availability_date."','".$car_a."','".$car_b."','".$car_c."','".$lpam_id."','active','revenue')";			
	$rs=$db->query($update);	
	$index_id=$db->insert_id;
	
	$update="insert into train_ava_time(train_ava_id,boundary_time) values ('".$index_id."','".$availability_date."')";
	$rs=$db->query($update);		
}
if(isset($_POST['other_index_no'])){

	$index_no=$_POST['other_index_no'];
	$lpam_id=$_POST['lpam_id'];
	
//	$car_a=$_POST['car_1'];
//	$car_b=$_POST['car_2'];
//	$car_c=$_POST['car_3'];
	
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$hour=$_POST['hour'];
	$minute=$_POST['minute'];
	$amorpm=$_POST['amorpm'];

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
	$availability_date=date("Y-m-d H:i",strtotime($year."-".$month."-".$day." ".$hour.":".$minute));

	
	$train_type=$_POST['train_type'];
	
	$update="insert into train_availability(index_no,date,status,typr) values ";
	$update.="('".$index_no."','".$availability_date."','active')";			
	$rs=$db->query($update);	
	$index_id=$db->insert_id;
	
	$update="insert into train_ava_time(train_ava_id,boundary_time) values ('".$index_id."','".$availability_date."','".$train_type."')";
	$rs=$db->query($update);		
}

if(isset($_POST['insertion_id'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$hour=$_POST['hour'];
	$minute=$_POST['minute'];
	$amorpm=$_POST['amorpm'];
	
	
	

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
	$availability_date=date("Y-m-d H:i",strtotime($year."-".$month."-".$day." ".$hour.":".$minute));

	if(isset($_POST['unimog_train_driver'])){
		$train_driver=$_POST['unimog_train_driver'];
	
	}
	else {
		$train_driver=$_POST['train_driver'];
	
	}
	
	

	$sql="update train_ava_time set insert_time='".$availability_date."',insert_driver='".$train_driver."' where train_ava_id='".$_POST['insertion_id']."'";
	$rs=$db->query($sql);


}
if(isset($_POST['remove_id'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$hour=$_POST['hour'];
	$minute=$_POST['minute'];
	$amorpm=$_POST['amorpm'];

	
	if(isset($_POST['unimog_train_driver'])){
		$train_driver=$_POST['unimog_train_driver'];
	
	}
	else {
		$train_driver=$_POST['train_driver'];
	
	}
	
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
	$availability_date=date("Y-m-d H:i",strtotime($year."-".$month."-".$day." ".$hour.":".$minute));

	
	$cancel_loop=$_POST['cancel_loop'];
//	echo $cancel_loop;

	$sql="update train_ava_time set remove_time='".$availability_date."',remove_driver='".$train_driver."',removal_remarks=\"".$_POST['remarks']."\" where train_ava_id='".$_POST['remove_id']."'";

	$rs=$db->query($sql);

	if(isset($_POST['cancel_loop'])){
		$sql="update train_ava_time set cancel_loop='".$_POST['bound']."' where train_ava_id='".$_POST['remove_id']."'";

		$rs=$db->query($sql);

		echo "<script language='javascript'>";
		echo "window.open('incident report.php?add_incident=".$_POST['remove_id']."')";
		echo "</script>";
	
	}
	
}
if(isset($_POST['switch_id'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$hour=$_POST['hour'];
	$minute=$_POST['minute'];
	$amorpm=$_POST['amorpm'];

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
	$availability_date=date("Y-m-d H:i",strtotime($year."-".$month."-".$day." ".$hour.":".$minute));

	$sql="insert into train_switch(train_ava_id,new_index,date_change) values ('".$_POST['switch_id']."','".$_POST['new_index']."','".$availability_date."')";
//	$sql="update train_ava_time set remove_time='".$availability_date."',remove_driver='".$_POST['train_driver']."',removal_remarks=\"".$_POST['remarks']."\" where train_ava_id='".$_POST['remove_id']."'";
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
<script language='javascript' src='ajax.js'></script>


<script language="javascript">
var driverHTML="";

function setHTML(){
	makeajax("processing.php?trainDriver=Y","getDriver");	

}

function getDriver(ajaxHTML){

	if(ajaxHTML=="None available"){
	}
	else {

		driverHTML="<select name='train_driver' id='train_driver'>";

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
	document.getElementById('td').innerHTML=driverHTML;
	
}

function setDate(){
	var d=new Date();
	
	var year=d.getFullYear();
	var mmonth=d.getMonth()*1+1;
	var day=d.getDate();
	
	var tentativehour=d.getHours();
	var minute=d.getMinutes();
	var hour=0;
	
	var amorpm="AM";
	
	if(tentativehour==0){
		hour=12;
		
		amorpm="AM";
	
	}
	else {
		if(tentativehour>12){
			hour=tentativehour-12;
			amorpm="PM";
		}
		else {
			hour=tentativehour;
			amorpm="AM";
		}
	
	}
	
	
	
	dateHTML="<select name='month' id='month'>";

	for(var i=1;i<=12;i++){
		d=new Date(year+"-"+i+"-1");	
		var month="";

		switch(i){
			case 1: month='January'; break;
			case 2: month='February'; break;
			case 3: month='March'; break;
			case 4: month='April'; break;
			case 5: month='May'; break;
			case 6: month='June'; break;
			case 7: month='July'; break;
			case 8: month='August'; break;
			case 9: month='September'; break;
			case 10: month='October'; break;
			case 11: month='November'; break;
			case 12: month='December'; break;
		
		}
		
		dateHTML+="<option value='"+i+"' "; 
		
		if(mmonth==i){
		dateHTML+="selected";
		}
		dateHTML+=">";
		dateHTML+=month;
		dateHTML+="</option>";
		
	}
	dateHTML+="</select>";

	
	dateHTML+="<select name='day' id='day'>";
	for(var i=1;i<=31;i++){
		dateHTML+="<option value='"+i+"' ";
		if(day==i){
		dateHTML+="selected";
		}
		dateHTML+=">"+i+"</option>";
	}
	
	dateHTML+="</select>";

	yearLimit=year*1+16;
	dateHTML+="<select name='month' id='month'>";
	for(var i=1999;i<=yearLimit;i++){
		dateHTML+="<option value='"+i+"' ";
		if(year==i){
		dateHTML+="selected";
		}
		dateHTML+=">"+i+"</option>";
	}
	
	dateHTML+="</select>";
	dateHTML+="<br>";
	dateHTML+="<select name='hour'>";
	
	for(var i=1;i<=12;i++){
		dateHTML+="<option value='"+i+"' ";
		if(hour==i){
		dateHTML+="selected";
		}
		dateHTML+=">"+i+"</option>";
	}
	
	
	
	dateHTML+="</select>";

	dateHTML+="<select name='minute'>";
	
	var label="";
	for(var i=0;i<=59;i++){
		
		if(i<10){
			label="0"+i;			
		}
		else {
			label=i;
		}
		
		dateHTML+="<option value='"+i+"' ";
		if(minute==i){
		dateHTML+="selected";
		}
		dateHTML+=">"+label+"</option>";

	}
	
	
	
	dateHTML+="</select>";
	dateHTML+="<select name='amorpm'>";
	dateHTML+="<option value='am' ";
	if(amorpm=="AM"){
		dateHTML+="selected";
	}
	dateHTML+=">AM</option>";

	dateHTML+="<option value='pm' ";
	if(amorpm=="PM"){
		dateHTML+="selected";
	}
	dateHTML+=">PM</option>";

	dateHTML+="</select>";
	
	document.getElementById('cell').innerHTML=dateHTML;
	



	
}


function changeForm(form_type,form_id,form_extra){
	var htmlCode="";
	if(form_type=="insertion"){
		htmlCode="<table>";
		htmlCode+="<tr>";
		htmlCode+="<th colspan=2>Add Insertion</th>";
		htmlCode+="</tr>";

		htmlCode+="<tr>";
		htmlCode+="<td>Insertion Time</td>";
		htmlCode+="<td id='cell' name='cell'>";
		htmlCode+="</td>";
		htmlCode+="</tr>";
		htmlCode+="<tr>";
		htmlCode+="<td>Train Driver</td>";
		
		if(form_extra=="unimog"){
			htmlCode+="<td><input type=text name='unimog_train_driver' />";
			
			htmlCode+="</td>";

		}
		if(form_extra=="test"){
			htmlCode+="<td><input type=text name='unimog_train_driver' />";
			
			htmlCode+="</td>";

		}

		else {
			htmlCode+="<td id='td' name='td'>";
//			htmlCode+=document.getElementById('td').innerHTML;
			htmlCode+="</td>";
		
		}		
		
		setHTML();	
		
		
		htmlCode+="</tr>";
		htmlCode+="<tr>";
		htmlCode+="<td colspan=2 class='submit' align=center>";
		htmlCode+="<input type=hidden name='insertion_id' id='insertion_id' value='"+form_id+"' />";
		htmlCode+="<input type=submit value='Submit' />";
		htmlCode+="</td>";
		htmlCode+="</tr>";
		htmlCode+="</table>";
	
	}
	else if(form_type=="removal"){
		htmlCode="<table>";
		htmlCode+="<tr>";
		htmlCode+="<th colspan=2>Add Removal</th>";
		htmlCode+="</tr>";

		htmlCode+="<tr>";
		htmlCode+="<td>Removal Time</td>";
		htmlCode+="<td id='cell' name='cell'>";
	//	htmlCode+=document.getElementById('cell').innerHTML;
		htmlCode+="</td>";
		htmlCode+="</tr>";
		htmlCode+="<tr>";
		htmlCode+="<td>Train Driver</td>";
		
		if(form_extra=="unimog"){
			htmlCode+="<td><input type=text name='unimog_train_driver' />";
			
			htmlCode+="</td>";

		}
		else if(form_extra=="test"){
			htmlCode+="<td><input type=text name='unimog_train_driver' />";
			
			htmlCode+="</td>";

		}

		else {
			htmlCode+="<td id='td' name='td'>";
			htmlCode+="</td>";
		
		}


		htmlCode+="</tr>";
		if(form_extra=="test"){
		htmlCode+="<tr>";
		htmlCode+="<td>MSD</td>";
		htmlCode+="<td><input type=text name='test_msd' /></td>";
		
		htmlCode+="</tr>";
		htmlCode+="<tr>";
		htmlCode+="<td>SSU</td>";
		htmlCode+="<td><input type=text name='test_ssu' /></td>";
		
		
		htmlCode+="</tr>";
		htmlCode+="<tr>";
		htmlCode+="<td>PH Trams</td>";
		htmlCode+="<td><input type=text name='test_maintenance' /></td>";
		
		
		htmlCode+="</tr>";
		}
		else {
		
		htmlCode+="<tr>";
		
		htmlCode+="<td>Remarks/Cause of <br>Failure/Removal</td>";
		htmlCode+="<td>";
		htmlCode+="<textarea name='remarks' cols=50></textarea>";
		htmlCode+="</td>";	
		
		htmlCode+="</tr>";
		}	
		htmlCode+="<tr>";
		htmlCode+="<td>";
		htmlCode+="Cancel Loop";
		htmlCode+="</td>";	

		htmlCode+="<td>";
		htmlCode+="<input type='checkbox' name='cancel_loop' id='cancel_loop' />";
		htmlCode+="<select name='bound' id='bound'>";
		htmlCode+="<option value='SB'>Southbound</option>";
		htmlCode+="<option value='NB'>Northbound</option>";
		htmlCode+="</select>";
		htmlCode+="</td>";	
		htmlCode+="</tr>";
		htmlCode+="<tr>";
		htmlCode+="<td colspan=2 class='submit' align=center>";
		htmlCode+="<input type=hidden name='remove_id' id='remove_id' value='"+form_id+"' />";
		htmlCode+="<input type=submit value='Submit' />";
		htmlCode+="</td>";
		htmlCode+="</tr>";
		htmlCode+="</table>";
	}
	else if(form_type=="index_switch"){
		setHTML();
		htmlCode="<table>";
		htmlCode+="<tr>";
		htmlCode+="<th colspan=2>Switch Index No.</th>";
		htmlCode+="</tr>";

		htmlCode+="<tr>";
		htmlCode+="<td>New Index No.</td>";
		htmlCode+="<td><input type=text name='new_index' /></td>";
		htmlCode+="</tr>";
		
		htmlCode+="<tr>";
		htmlCode+="<td>";
		htmlCode+="Time of Switch";
		htmlCode+="</td>";
		htmlCode+="<td id='cell' name='cell'>";
		htmlCode+="</td>";
		htmlCode+="</tr>";
		htmlCode+="<tr>";
		htmlCode+="<td colspan=2>";
		htmlCode+="<input type='submit' class='submit' value='Submit' />";
		htmlCode+="</td>";
		htmlCode+="</tr>";
		htmlCode+="<tr>";
		htmlCode+="<input type=hidden name='switch_id' id='switch_id' value='"+form_id+"' />";
		htmlCode+="</tr>";
		htmlCode+="</table>";
	
	}
	else if(form_type=="add_train"){
		htmlCode="<table>";
		htmlCode+="<tr>";
		htmlCode+="<th colspan=2>Add/Prep Train</th>";
		htmlCode+="</tr>";

		htmlCode+="<tr>";
		htmlCode+="<td>Index No.</td>";
		htmlCode+="<td><input type=text name='index_no' /></td>";
		htmlCode+="</tr>";
		htmlCode+="<tr>";
		htmlCode+="<td>Car 1</td>";
		htmlCode+="<td><input type=text name='car_1' /></td>";
		htmlCode+="</tr>";
		htmlCode+="<tr>";
		htmlCode+="<td>Car 2</td>";
		htmlCode+="<td><input type=text name='car_2' /></td>";
		htmlCode+="</tr>";
		htmlCode+="<tr>";
		htmlCode+="<td>Car 3</td>";
		htmlCode+="<td><input type=text name='car_3' /></td>";
		htmlCode+="</tr>";
		htmlCode+="<tr>";

		htmlCode+="<td>LPAM No.</td>";
		htmlCode+="<td><input type=text name='lpam_id' /></td>";		
		htmlCode+="</tr>";
		
		htmlCode+="<tr>";
		
		htmlCode+="<td>I336 Time</td>";
		htmlCode+="<td id='cell' name='cell'>";
		htmlCode+="</td>";
		htmlCode+="</tr>";

		htmlCode+="<tr><td align=center class='submit' colspan=2>";

		htmlCode+="<input type='submit' value='Add' />";
		htmlCode+="</td>";
		htmlCode+="</table>";
	}
	else if(form_type=="unimog"){
		htmlCode="<table>";
		htmlCode+="<tr>";
		htmlCode+="<th colspan=2>Add/Prep Unimog Train</th>";
		htmlCode+="</tr>";
		htmlCode+="<tr><td>Type of Train</td>";
		htmlCode+="<td>";	
		htmlCode+="<select name='train_type'>";
		htmlCode+="<option value='unimog'>UNIMOG</option>";	
		htmlCode+="<option value='test'>Test Train</option>";
		htmlCode+="</select>";

		htmlCode+="</td>";	

		htmlCode+="</tr>";

		htmlCode+="<tr>";
		htmlCode+="<td>Index No.</td>";
		htmlCode+="<td>";
		htmlCode+="<select name='other_index_no'>";
		for(var n=80;n<=89;n++){

			htmlCode+="<option value='"+n+"'>"+n+"</option>";
		}
		htmlCode+="</select>";
		
		htmlCode+="</td>";
		htmlCode+="</tr>";

		
		htmlCode+="<tr>";
		
		htmlCode+="<td>I336 Time</td>";
		htmlCode+="<td id='cell' name='cell'>";
		htmlCode+="</td>";
		htmlCode+="</tr>";

		htmlCode+="<tr><td align=center colspan=2>";

		htmlCode+="<input type='submit' class='submit'value='Add' />";
		htmlCode+="</td>";
		htmlCode+="</table>";
	}
	document.getElementById('add_form').innerHTML=htmlCode;
	
	setDate();
	
	if((form_type=="removal")||(form_type=="insertion")){
		setHTML();	

	}
}

function cancelTrain(train_id){
	var check=confirm("Cancel Train?");
	if(check){
		window.open("incident report.php?cancel="+train_id);
		
	}

}

</script>
<?php




?>
<body>

<a href='../index.php'>Go Back to Monitoring Menu</a>
<br>
<br>
<form action='train availability.php' method='post'>
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





<br>
<table id='cellHeading' width=100%>
<tr>
<td colspan=2 valign=bottom>
<font class='date' color=red>
<b>
<?php


if(isset($_POST['day'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];


	
	$availability_date=date("F d, Y",strtotime($year."-".$month."-".$day));
	$availability_date_code=date("Y-m-d",strtotime($year."-".$month."-".$day));

	echo $availability_date;
}	
else if(isset($_SESSION['day'])){
	$year=$_SESSION['year'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];
	
	$availability_date=date("F d, Y",strtotime($year."-".$month."-".$day));
	$availability_date_code=date("Y-m-d",strtotime($year."-".$month."-".$day));

	echo $availability_date;
}	
else {

	$year=date("Y");
	$month=date("m");
	$day=date("d");

	$availability_date=date("F d, Y",strtotime($year."-".$month."-".$day));
	$availability_date_code=date("Y-m-d",strtotime($year."-".$month."-".$day));

	echo $availability_date;



}
	
	
	
?>
</b>
</font>
</td>
<td valign=center align=right><b>Timetable Code: 
<?php
$db=new mysqli("localhost","root","","transport");
$timeTableSQL="select *,timetable_day.id as timeId from timetable_day inner join timetable_code on timetable_day.timetable_code=timetable_code.id where train_date like '".$availability_date_code."%%'";

$timeTableRS=$db->query($timeTableSQL);
$timeTableNM=$timeTableRS->num_rows;
if($timeTableNM>0){
	$timeTableRow=$timeTableRS->fetch_assoc();
	echo $timeTableRow['code'];
?>
 <a href='#' style='text-underline:none;' onclick='window.open("timetable_set.php?reset=<?php echo $timeTableRow['timeId']; ?>","code","height=300, width=350")'>Set/Reset Code</a>
<?php	

	}
else {

	echo "________";
?>
 <a href='#' style='text-underline:none;' onclick='window.open("timetable_set.php?set=1","code","height=300, width=350")'>Set/Reset Code</a>
<?php	
}
?>
</b> 
</td>

</tr>
<tr>
<td colspan=2 valign=top>
<?php
	$availabilityWeekDay=date("l",$availability_date_code);
	echo "<b>".$availabilityWeekDay."</b>";

?>

</td>
</tr>
</table>
<table class='train_ava'>
<tr class='rowHeading'>
<th rowspan=2>
Index No.
</th>
<th colspan=7>
Switch
</th>
<th rowspan=2>Train Compo</th>

<th rowspan=2>Time on I336</th>
<th rowspan=2>LPAM No.</th>

<th rowspan=2>Time Inserted</th>
<th rowspan=2>Train Driver</th>


<th rowspan=2>Time Removed</th>
<th rowspan=2>Train Driver</th>
<th rowspan=2>Remarks/Cause of Failure/Removal</th>
<th colspan=3>Removal</th>

</tr>
<tr class='rowHeading'>
	
	<?php 
		for($i=0;$i<7;$i++){
	?>	
	<th>Index No.</th>
	<?php	
		}
	?>
	<th>L2</th>
	<th>L3</th>
	<th>L4</th>

	</tr>
<?php
//if((isset($_POST['day']))||(isset($_SESSION['day']))){
	
	
	$year=date("Y");
	$month=date("m");
	$day=date("d");

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

	$db=new mysqli("localhost","root","","transport");
	
	$sql="select * from train_availability where date like '".$availability_date."%%' order by date";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$train_index=$row['index_no'];	
	?>
	<tr <?php if($i%2>0){ echo "class='rowClass'"; } ?>>	
		<td rowspan=3><?php echo $row['index_no']; ?></td>
<?php
	$sql3="select * from train_switch where train_ava_id='".$row['id']."' order by date_change";
	$rs3=$db->query($sql3);
	$nm3=$rs3->num_rows;

	if($nm3>7){
		$nm3=7;
	}
	for($n=0;$n<$nm3;$n++){
		$row3=$rs3->fetch_assoc();
?>
		<td rowspan=3><?php echo date("H:i",strtotime($row3['date_change']))." / ".$row3['new_index']; ?> </td>
<?php
	
	
	}
	
	$blank=7-($nm3*1);
	

	for($n=0;$n<$blank;$n++){	
	
?>
		<td rowspan=3>&nbsp;</td>
<?php
}
?>	


		<td><?php echo $row['car_a']; ?></td>
		
	<?php	
		
		$sql2="select * from train_ava_time where train_ava_id='".$row['id']."'";
		$rs2=$db->query($sql2);
		$row2=$rs2->fetch_assoc();
		
		if($row2['boundary_time']==""){
			$boundary_time="";
		}
		else {
			$boundary_time=date("H:i",strtotime($row2['boundary_time']));
		}
		if($row['status']=="active"){
		if($row2['insert_time']==""){
			$insert_time="";
			$insert_driver="";
		}
		else {
			$insert_time=date("H:i",strtotime($row2['insert_time']));
			
			if(($train_index=="70")||($train_index=="80")||($train_index=="81")){
				$insert_driver=$row2['insert_driver']."<br>PH TRAMS - CB & T";
			}
			else {
				$insert_driver=getTrainDriver($row2['insert_driver']);
			
			
			}
		}

		if($row2['remove_time']==""){
			$remove_time="";
			$remove_driver="";
			$remove_remarks="";

		}
		else {
			$remove_time=date("H:i",strtotime($row2['remove_time']));
			if(($train_index=="70")||($train_index=="80")||($train_index=="81")){
				$remove_driver=$row2['remove_driver']."<br>PH TRAMS - CB & T";
			}
			else {
			$remove_driver=getTrainDriver($row2['remove_driver']);
			
			
			}


			$remove_remarks=$row2['removal_remarks'];
		}
		}

		if($row['status']=="active"){

			
		//	$cancelSQL="select * from train_incident_report inner join incident_report on train_incident_report.incident_id=incident_report.id where train_ava_id='".$row['id']."'";
			$cancelSQL="select * from train_incident_view where train_ava_id='".$row['id']."'";			
			$cancelRS=$db->query($cancelSQL);
			$incidentClause="";	
			$level2Clause="";	
			
			$level3Clause="";
			
			$l2Count=0;
			$l3Count=0;
			
			$cancelNM=$cancelRS->num_rows;
			if($cancelNM>0){
				$incidentClause="<br>See ";

				for($m=0;$m<$cancelNM;$m++){
					$cancelRow=$cancelRS->fetch_assoc();	
					$level=$cancelRow['level'];
					
					
					if($m==0){
						$incidentClause.="<a href='#' onclick='window.open(\"incident details.php?ir=".$cancelRow['incident_id']."\")'>".$cancelRow['incident_no']."</a>";
					
					
					
					}
					else {
						$incidentClause.=",<br>";
						$incidentClause.="<a href='#' onclick='window.open(\"incident details.php?ir=".$cancelRow['incident_id']."\")'>".$cancelRow['incident_no']."</a>";
						
					}
					
					if($level==2){
						if($l2Count==0){
							$level2Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						}
						else {
							$level2Clause.=",<br>";
							$level2Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						
						}
						$l2Count++;
						$level3Clause="&nbsp;";
						$level4Clause="&nbsp;";
						
					}
					
					else if($level==3){
						if($l3Count==0){
							$level3Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						}
						else {
							$level3Clause.=",<br>";
							$level3Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						
						}
						$l3Count++;
						$level2Clause="&nbsp;";
						$level4Clause="&nbsp;";
						
					}
					else if($level==4){

						if($l4Count==0){
							$level4Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						}
						else {
							$level4Clause.=",<br>";
							$level4Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						
						}
						$l4Count++;
						$level3Clause="&nbsp;";
						$level2Clause="&nbsp;";						
						
						
					}
					else {
						$level3Clause="&nbsp;";
						$level4Clause="&nbsp;";
						$level2Clause="&nbsp;";						
					}
		
				
				}
			}
		
		?>	
		<td rowspan=3><?php echo $boundary_time; ?></td>

		<td rowspan=3><?php echo $row['lpam_id']; ?></td>		

		<td rowspan=3><?php echo $insert_time; ?> </td>
		<td rowspan=3><?php echo  $insert_driver; ?></td>

		<td rowspan=3><?php echo $remove_time; ?> </td>
		<td rowspan=3><?php echo $remove_driver; ?></td>
		<td rowspan=3><?php echo $remove_remarks; ?>
		<?php echo $incidentClause; ?>
		
		
		</td>
		<td rowspan=3><?php echo $level2Clause; ?></td>
		<td rowspan=3><?php echo $level3Clause; ?></td>
		<td rowspan=3><?php echo $level4Clause; ?></td>
		<?php
		}
		else if($row['status']=="cancelled"){
		
			
			$cancelSQL="select * from train_incident_report inner join incident_report on train_incident_report.incident_id=incident_report.id where train_ava_id='".$row['id']."'";
			
			$cancelRS=$db->query($cancelSQL);
		
			$incidentClause="See ";
			$level2Clause="";	
			
			$level3Clause="";
			
			$l2Count=0;
			$l3Count=0;

			
			$cancelNM=$cancelRS->num_rows;
			for($m=0;$m<$cancelNM;$m++){
				$cancelRow=$cancelRS->fetch_assoc();	
				if($m==0){
					$incidentClause.="<a href='#' onclick='window.open(\"incident details.php?ir=".$cancelRow['incident_id']."\")'>".$cancelRow['incident_no']."</a>";
				}
				else {
					$incidentClause.=",<br>";
					$incidentClause.="<a href='#' onclick='window.open(\"incident details.php?ir=".$cancelRow['incident_id']."\")'>".$cancelRow['incident_no']."</a>";
					
				}

					if($level==2){
						if($l2Count==0){
							$level2Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						}
						else {
							$level2Clause.=",<br>";
							$level2Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						
						}
						$l2Count++;
						$level3Clause="&nbsp;";
						$level4Clause="&nbsp;";
						
					}
					
					else if($level==3){
						if($l3Count==0){
							$level3Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						}
						else {
							$level3Clause.=",<br>";
							$level3Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						
						}
						$l3Count++;
						$level2Clause="&nbsp;";
						$level4Clause="&nbsp;";
						
					}
					else if($level==4){

						if($l4Count==0){
							$level4Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						}
						else {
							$level4Clause.=",<br>";
							$level4Clause.=$cancelRow['level'].getOrdinal($cancelRow['level']);
						
						}
						$l4Count++;
						$level3Clause="&nbsp;";
						$level2Clause="&nbsp;";						
						
						
					}
					else {
						$level3Clause="&nbsp;";
						$level4Clause="&nbsp;";
						$level2Clause="&nbsp;";						
					}
					
			}
		
		?>
		<?php 
		if($boundary_time==""){
		?>	
		<td rowspan=3 colspan=6 align=center>CANCELLED</td>
		<?php
		}
		else {
		?>	
		<td rowspan=3><?php echo $boundary_time; ?></td>
		<td rowspan=3 colspan=5 align=center>CANCELLED</td>
		<?php
		}
		?>
		<td rowspan=3><?php echo $incidentClause; ?></td>
		<td rowspan=3><?php echo $level2Clause; ?></td>
		<td rowspan=3><?php echo $level3Clause; ?></td>
		<td rowspan=3><?php echo $level4Clause; ?></td>
		
		<?php
		
		}
		
		?>
		
	<?php	
	?>
	</tr>
	<tr <?php if($i%2>0){ echo "class='rowClass'"; } ?>>
		<td><?php echo $row['car_b']; ?></td>
	</tr>	
	<tr <?php if($i%2>0){ echo "class='rowClass'"; } ?>>
		<td><?php echo $row['car_c']; ?></td>
	</tr>	

<?php	
	}



//}
?>
</table>
<br>
<a href='#' onclick='window.open("generate_tar.php?tar=<?php echo $availability_date; ?>");'>Generate Printout</a>

</body>