<a href='../index.php'>Go Back to Monitoring Menu</a>
<form action='passenger incident.php' method='post'>
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
	echo "<tr><th>Station</th><th>Subject</th>";
	$db=new mysqli("localhost","root","","station");
	$sql="SELECT * FROM passenger_incident inner join station on passenger_incident.station=station.id where date like '".date("Y-m-d",strtotime($auth_date))."%%' order by station*1,date desc";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		echo "<tr>";
		echo "<td width=20%>".$row['station_name']."</td>";
		echo "<td width=80%><b>Incident/Complaint:</b> ".$row['description']."<br>";
		echo "<b>Action Taken:</b> ".$row['action_taken']."<br>";
		echo "<b>Recommendation:</b> ".$row['recommendation'];
		if($row['type']=="personnel"){
			echo "<b>Personnel Name:</th> ".$row['name_of_personnel'];
		
		}
		echo "</td>";

		
		echo "</tr>";
	}
	
	echo "</table>";
	echo "<br>";
	echo "<br>";
	echo "<h3>RECOMMENDATION</h3>"; 
	
	echo "<table border=1 width=80%>";
	echo "<tr><th>From All Stations</th><th>Status</th><th>Remarks</th>";
	$db=new mysqli("localhost","root","","station");
	$sql="SELECT * FROM recommendation where recommend_date like '".date("Y-m-d",strtotime($auth_date))."%%' order by recommend_date";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		echo "<tr>";
		echo "<td width=50%>".$row['recommendation']."</td>";
		echo "<td width=25%>".$row['status']."</td>";
		echo "<td width=25%>".$row['remarks']."</td>";
		
		echo "</tr>";
	}
	
	
	
	echo "</table>";
	
	
}