<a href='../index.php'>Go Back to Monitoring Menu</a>
<form action='theft report.php' method='post'>
<table border=1>
<tr>
<th colspan=3>View Table</th>
</tr>
<tr>
<th>Date</th>
</tr>
<tr>
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
<th><input type=submit value='Submit' /></th>
</tr>
</table>
</form>
<br>
<?php
if(isset($_POST['year'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];

	$auth_date=$year."-".$month."-".$day;
	
	echo "<table border=1 width=80%>";
	echo "<tr><th>Date/Time</th><th>Incident</th><th>Location</th><th>Description</th><th>Action Taken</th>";
	echo "<th>Status/Description</th></tr>";
	$db=new mysqli("localhost","root","","ssu");
	$sql="select * from theft_report where theft_time like '".$auth_date."%%' order by auth_time";
//	$sql="SELECT * FROM passenger_incident inner join station on passenger_incident.station=station.id where date like '".date("Y-m-d",strtotime($auth_date))."%%' order by station*1,date desc";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		echo "<tr>";
		
		echo "<td>".date("d F Y Hi",strtotime($row['theft_time']))."</td>";
		echo "<td>".$row['description']."</td>";
		echo "<td>".$row['location']."</td>";
		echo "<td>Victim: ".$row['victim']."<br>Suspect: ".$row['suspect']."</td>";
		echo "<td>".$row['action_taken']."</td>";
		echo "<td>".$row['status']."</td>";
		echo "</tr>";
	}
	
	echo "</table>";
	echo "<br>";
	
	echo "<table>";
	echo "<tr><th colspan=2>Recapitulation</th></tr>";	
	echo "<tr><th>Station</th><th>&nbsp;</th></tr>";
	$db=new mysqli("localhost","root","","station");
	$sql="select * from station";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($n=0;$n<$nm;$n++){
		$row=$rs->fetch_assoc();
		
		$countSQL="select * from theft_report where theft_time like '".$auth_date."%%' and station='".$row['id']."'";
		$countRS=$db->query($countSQL);
		$countNM=$countRS->num_rows;
		
		echo "<tr>";
		echo "<td>".$row['station_name']."</td>";
		echo "<td>".$countNM."</td>";
		
		echo "</tr>";
		
	}
	
	echo "</table>";
	
	
}