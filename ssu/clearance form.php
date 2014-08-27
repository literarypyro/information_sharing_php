<?php
session_start();
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
<body>
<?php
require("monitoring menu.php");
?>
<br>
<br>
<br>
<form action='clearance form.php' method='post'>
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
<table class='train_ava' width=100%>
<tr class='rowHeading'>
	<th>Clearance No.</th>
	<th>Location</th>
	<th>Activity</th>
	<th>Person</th>
	<th>Position/Company</th>
	<th>Received By</th>
	<th>Login</th>
	<th>Logout</th>
	<th>Work Permit/Control No.</th>
</tr>
<?php
if((isset($_POST['day']))||(isset($_SESSION['day']))){
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
	
	$clearance_date=date("Y-m-d",strtotime($year."-".$month."-".$day));

	$db=new mysqli("localhost","root","","transport");	
	
	$sql="select * from clearance where date like '".$clearance_date."%%' order by clearance_no";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;	
	
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			$clearance_no=$row['clearance_no'];
			$location=$row['location'];
			$activity=$row['activity'];
			$person=$row['person'];
			$position=$row['position'];
			$company=$row['company'];
			$received_by=$row['received_by'];
			$login=date("H:i",strtotime($row['login']));
			$logout=date("H:i",strtotime($row['logout']));
			$control_no=$row['control_no'];
			
?>			
			<tr <?php if($i%2>0){ echo "class='rowClass'"; } ?>>
				<td align=center><?php echo $clearance_no; ?></td>	
				<td><?php echo $location; ?></td>

				<td><?php echo $activity; ?></td>
				<td><?php echo $person; ?></td>
				<td><?php echo $position." / ".$company; ?></td>
				<td><?php echo $received_by; ?></td>
				<td><?php echo $login; ?></td>
				<td><?php echo $logout; ?></td>
				<td><?php echo $control_no; ?></td>
			</tr>	
			
<?php		
		}	
	}
	

}


?>
</table>
</body>