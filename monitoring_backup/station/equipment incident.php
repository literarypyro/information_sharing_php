<a href='../index.php'>Go Back to Monitoring Menu</a>
<form action='equipment incident.php' method='post'>
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

	$equipt_date=$year."-".$month."-".$day;

echo "<table border=1 width=100%>";
echo "<tr>";
echo "<th rowspan=2>Station</th>";
echo "<th colspan=2>Reported</th>";
echo "<th rowspan=2>Problems Encountered</th>";
echo "<th rowspan=2>Incident Number</th>";
echo "<th colspan=2>Repaired</th>";
echo "</tr>";
echo "<tr>";
echo "<th>Date</th>";
echo "<th>Time</th>";

echo "<th>Date</th>";
echo "<th>Time</th>";

echo "</tr>";



$db=new mysqli("localhost","root","","station");
$sql="select * from equipment_incident where date like '".date("Y-m-d",strtotime($equipt_date))."%%' order by station,date";
$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$station=$row['station'];
	$incident_no=$row['incident_number'];
	$time=date("Hm",strtotime($row['date']));
	$details=$row['details'];
	$date=date("m/d/Y",strtotime($row['date']));
	$incident_id=$row['id'];
	
	echo "<tr>";
	echo "<td align=center>".$station."</td>";
	echo "<td align=center>".$date."</td>";
	echo "<td align=center>".$time."</td>";
	echo "<td>".$details."</td>";
	echo "<td>".$incident_no."</td>";
	
	$repair="select * from equipment_repair where incident_id='".$incident_id."'";
	$repairRS=$db->query($repair);
	$repairNM=$repairRS->num_rows;
	if($repairNM>0){
		$row2=$repairRS->fetch_assoc();
		$time_repaired=date("Hm",strtotime($row2['date_repaired']));
		$date_repaired=date("m/d/Y",strtotime($row2['date_repaired']));
	
	}
	else {
		$time_repaired="&nbsp;";
		$date_repaired="&nbsp;";
	
	}
	echo "<td align=center>".$date_repaired."</td>";
	echo "<td align=center>".$time_repaired."</td>";
	echo "</tr>";
	

}


echo "</table>";
}
?>