<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
function getStation($station){
	$db=new mysqli("localhost","root","","transport");
	
	$sql="select * from station where id='".$station."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	$name=$row['station_name'];
	return $name;

}
function getIncidentDate($incident_id){
	$db=new mysqli("localhost","root","","transport");
	$sql="select * from train_incident_report inner join train_availability on train_incident_report.train_ava_id=train_availability.id where incident_id='".$incident_id."'";
	$rs=$db->query($sql);
	
	$row=$rs->fetch_assoc();
	
	$incident_date=date("Y-m-d",strtotime($row['incident_date']));
	
	return $incident_date;
}
?>

<?php


if(isset($_GET['incident'])){
	$db=new mysqli("localhost","root","","transport");
	
	$sql="select * from incident_report inner join incident_description on incident_report.id=incident_description.incident_id where incident_report.id='".$_GET['incident']."'";

	$rs=$db->query($sql);	
	
	$row=$rs->fetch_assoc();
	
	$incident_no=$row['incident_no'];

	$direction=$row['direction'];

	$index_no=$row['index_no'];
	$car_no=$row['car_no'];
	$details=$row['description'];
	
	$location=$row['location'];
	$stationCount=substr_count($location,"-");
	if($stationCount>0){
		$station=explode("-",$location);		

		$originStation=trim($station[0]);
		$destinationStation=trim($station[1]);
		
		$stationName=getStation($originStation)." - ".getStation($destinationStation);
		
	
	}
	else {
		$stationName=getStation(trim($location));
	
	}

	$service_location="";
	
	if($direction=="S"){	
		$service_location=$stationName;
		
	}
	else if(($direction=="NB")||($direction=="SB")){
		$service_location=$stationName." ".$direction;
	}
	else {
		$service_location=$direction;
	}
}

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
Incident Number:  <?php echo $incident_no; ?><br>
Breakdown: <?php echo "Index #".$index_no.", Car ".$car_no.", <b>".$details."</b>"; ?>
Location: <?php echo $service_location; ?> <br>
<br>
<br>


<table class='ccdr'  width=100%>
<tr id='ccdr_heading'>
<th width=10%>Time</th>
<th width=90%>Description</th>
</tr>
<?php
if(isset($_GET['incident'])){
	$db=new mysqli("localhost","root","","transport");

	$sql="select * from service_interruption where incident_id='".$_GET['incident']."'";
	$rs=$db->query($sql);

	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		
		$time=date("Hi",strtotime($row['time']))."H";
			
		$description=$row['description'];
?>	
		<tr>
			<td><?php echo $time; ?></td>
			<td><?php echo $description; ?></td>
		</tr>
<?php	
	}	
}
?>
</table>
<br>
<a href='#' onclick='window.open("generate_ser_int.php?serint_date=<?php getIncidentDate($_GET['incident']); ?>&ser_int=<?php echo $_GET['incident']; ?>");'>Generate Printout</a>