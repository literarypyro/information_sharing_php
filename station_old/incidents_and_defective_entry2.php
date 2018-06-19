<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","station");

if(isset($_GET['incident'])){
	$incident_type=$_GET['type'];
	$incident_id=$_GET['incident'];

	$sql="select * from incident inner join report on incident.id=report.incident_id where incident.id='".$incident_id."'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		
		$station_sql="select * from station where id='".$row['station']."'";
		$station_rs=$db->query($station_sql);
		
		$station_row=$station_rs->fetch_assoc();
		$station_name=$station_row['station_name'];
		
		
		$details=$row['details'];
	}


}
?>
<table style='border:1px solid gray'>
<tr>
<td>Station</td>
<td>
<?php echo $station_name; ?>
</td>

</tr>
<tr>

<td>Details of the Report</td>
<td>
<?php echo $details; ?>
</td>
</tr>

<tr>
<th colspan=2>Issued Numbers:</th>
</tr>
<tr>
<td>Record No.</td>
<td><input type=text name='record' /></td>
</tr>

<tr>
<td>Incident No.</td>
<td><input type=text name='incident' /></td>
</tr>




<tr>
<th colspan=2>Reported/Coordinated To:</th>
</tr>
<tr>
<td>Office</td>
<td><input type=text name='reported_office' /></td>
</tr>
<tr>
<td>Personnel</td>
<td><input type=text name='reported_personnel' /></td>
</tr>

<tr>
<td>Remarks</td>
<td>
<textarea rows=5 cols=50 name='remarks'></textarea>
</td>
</tr>
</table>
<table>
<tr>
<td>
<input type=hidden name='incident_type' />
<input type=hidden name='incident_id' />



</td>
</tr>
</table>


