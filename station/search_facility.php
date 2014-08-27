<?php
$db=new mysqli("localhost","root","","station");
?>
<script language='javascript'>
function linkProblem(problem_id,details,record,incident_no){
	
	var check=confirm("Link to Repair?");
	if(check){
		
//		var incident_id=document.getElementById('incident_report').value;
//		var incident_no=document.getElementById('incident_report1').value;

		window.opener.document.getElementById('record_no').innerHTML=record;
		window.opener.document.getElementById('incident_no').innerHTML=incident_no;
		window.opener.document.getElementById('problem_details').innerHTML=details;
		window.opener.document.getElementById('equipt').value=problem_id;
		
		
		self.close();
	}
}
</script>
<link href="layout/landbank/control slip.css" rel="stylesheet" type="text/css"  id='stylesheet' />

<form action='search_facility.php' method='post'>
<table style='border:1px solid gray;' class='controlTable'>
<tr>
<th colspan=2>Search Equipment/Facility Problem</th>
</tr>
<tr>
<th>Record No.</th><td><input type=text name='record_no' /></td>
</tr>
<tr>
<th>Incident No.</th><td><input type=text name='incident_no' /></td>
</tr>
<tr>
<td align=center colspan=2><input type=submit value='Get Selection' /></td>
</tr>
</table>
</form>
<?php
if((isset($_POST['record_no']))&&(isset($_POST['incident_no']))){
	
	$record_no=$_POST['record_no'];
	$incident_no=$_POST['incident_no'];
	$repair_id=$_POST['repair_id'];
	
	$sql="select * from issued_no inner join equipment_incident on e_incident_id=equipment_incident.id where record like '".$record_no."%%' and incident_cdc like '".$incident_no."%%'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
?> 
	<form action='search_facility.php' method='post'>
	<table>	
	<tr>
		<th>Select Equipment/Facility Problem</th>
		<td>
			<select name='facility_problem'>
			<?php
			for($i=0;$i<$nm;$i++){
				$row=$rs->fetch_assoc();
			?>	
				<option value='<?php echo $row['e_incident_id']; ?>'><?php echo $row['details']; ?></option>
			<?php
			}
			?>
			</select>
		</td>
		<td><input type=submit value='Retrieve' /></td>
		
	</tr>	
	</table>
	</form>
<?php
	}
}
?>
<?php
if(isset($_POST['facility_problem'])){
?>
<br>
<br>
<?php
	$repair_id=$_POST['repair_id'];
	$equipt_id=$_POST['facility_problem'];
	$sql2="select * from issued_no inner join equipment_incident on e_incident_id=equipment_incident.id where e_incident_id='".$equipt_id."'";
	
	$rs2=$db->query($sql2);
	
	$row2=$rs2->fetch_assoc();
	
	$problem_date=date("Y-m-d",strtotime($row2['date']));
	$station=$row2['station'];
	$details=$row2['details'];
	
	$ss_time=$row2['time_ss'];
	$cc_time=$row2['time_cc'];
	
	if($ss_time=="0000-00-00 00:00:00"){
		$ss_timestamp="";
	
	}
	else {
		$ss_timestamp=date("Hi",strtotime($ss_time));
	}
	
	if($cc_time=="0000-00-00 00:00:00"){
		$cc_timestamp="";
	
	}
	else {
		$cc_timestamp=date("Hi",strtotime($cc_time));
	}
	
	$record_no=$row2['record'];
	$incident_no=$row2['incident_cdc'];
	
	
?>	
<a href='#' onclick="linkProblem('<?php echo $equipt_id; ?>','<?php echo $details; ?>','<?php echo $record_no; ?>','<?php echo $incident_no; ?>')" >Link Problem</a>
<table border='1px' style='border-collapse:collapse;' width=60%>
	<tr><th>Date:</th><td><?php echo $problem_date; ?></td></tr>
	<tr><th>Station:</th><td><?php echo $station; ?></td></tr>
	<tr><th colspan=2>Reported/Coordinated (Time)</th></tr>
	<tr><th>By SS</th><td><?php echo $ss_timestamp; ?></td></tr>
	<tr><th>By/To CC</th><td><?php echo $cc_timestamp; ?></td></tr>
	<tr><th>DETAILS OF THE REPORT:</th><td><?php echo $details; ?></td></tr>
</table>
<?php
}
?>