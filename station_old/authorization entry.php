<?php
if(isset($_POST['sj'])){
	$station=$_POST['station'];
	$classification=$_POST['classification'];
	$sj=$_POST['sj'];
	$sv=$_POST['sv'];
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];

	$auth_date=$year."-".$month."-".$day;
		
	$db=new mysqli("localhost","root","","station");
	$search="select * from authorization where station='".$station."' and classification='".$classification."' and date='".$auth_date."'";
	$rs=$db->query($search);
	$nm=$rs->num_rows;
	if($nm>0){
		$sql="update authorization set sjt='".$sj."', svt='".$sv."' where station='".$station."' and classification='".$classification."' and date='".$auth_date."'";
		$rs=$db->query($sql);
	}
	else {
		$sql="insert into authorization(station,classification,sjt,svt,date)";
		$sql.=" values ('".$station."','".$classification."','".$sj."','".$sv."','".$auth_date."')";
		$rs=$db->query($sql);
	}



}
?>
<a href='monitoring menu.php'>Go Back to Monitoring Menu</a>
<form action='authorization entry.php' method=post >
<table border=1>
<tr>
<th>Date</th>
<th>Station</th>
<th>Type</th>
<th>SJ</th>
<th>SV</th>
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
<td>
<?php
$db=new mysqli("localhost","root","","station");
$sql="select * from station order by id*1";
$rs=$db->query($sql);
$nm=$rs->num_rows;
?>
<select name='station'>
<?php
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<option value='<?php echo $row['id']; ?>'>
	<?php echo $row['station_name']; ?>
	</option>
<?php
}
?>
</select>
</td>
<td>
<select name='classification'>
<option value='senior'>Senior Citizen</option>
<option value='pwd'>Passengers with Disabilities</option>
<option value='stdt'>Student</option>
</select>
</td>
<td><input type=text name='sj' /></td>
<td><input type=text name='sv' /></td>
</tr>
<tr>
<th colspan=5><input type=submit value='Submit' /></th>
</tr>
</table>
</form>
<br>
<form action='authorization entry.php' method=post >
<table border=1>
<tr>
<th colspan=3>View Table</th>
</tr>
<tr>
<th>Date</th>
<th>Station</th>
<th>Type</th>
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
<td>
<?php
$db=new mysqli("localhost","root","","station");
$sql="select * from station order by id*1";
$rs=$db->query($sql);
$nm=$rs->num_rows;
?>
<select name='station'>
<?php
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
	<option value='<?php echo $row['id']; ?>'>
	<?php echo $row['station_name']; ?>
	</option>
<?php
}
?>
</select>
</td>
<td>
<select name='classification'>
<option value='senior'>Senior Citizen</option>
<option value='pwd'>Passengers with Disabilities</option>
<option value='stdt'>Student</option>
</select>
</td>
</tr>
<tr>
<th colspan=3><input type=submit value='Submit' /></th>
</tr>
</table>
</form>
<?php
if(isset($_POST['classification'])){
	echo "<table border=1 width=60%>";
	echo "<tr><th colspan=3>Authorization Slip Report - ";
	
	$db=new mysqli("localhost","root","","station");	
	$sql="select * from auth_classify where auth_id='".$_POST['classification']."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	$label=$row['description'];
	echo $label;
	echo "</th></tr>";
	echo "<tr>";
	echo "<th>Station</th><th>SJ</th><th>SV</th>";
	echo "</tr>";
	
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];

	$auth_date=$year."-".$month."-".$day;
	$update="select * from authorization inner join station on authorization.station=station.id where classification='".$_POST['classification']."' and date='".$auth_date."' order by station*1";

	$urs=$db->query($update);
	$nm=$urs->num_rows;
	
	for($i=0;$i<$nm;$i++){
		$urow=$urs->fetch_assoc();
		echo "<tr>";
		echo "<td>".$urow['station_name']."</td>";
		echo "<td>".$urow['sjt']."</td>";
		echo "<td>".$urow['svt']."</td>";
		
		
		echo "</tr>";
	
	}
	
	echo "</table>";
}
?>
