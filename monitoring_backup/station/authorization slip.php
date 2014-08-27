<a href='../index.php'>Go Back to Monitoring Menu</a>
<form action='authorization slip.php' method='post'>
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
<?php
if(isset($_POST['year'])){
	$db=new mysqli("localhost","root","","station");
	$sql="SELECT * FROM station order by id*1";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];

	$auth_date=$year."-".$month."-".$day;

	for($i=1;$i<=$nm;$i++){
		$row=$rs->fetch_assoc();
		$pwd[$i]["sj"]="&nbsp;";
		$pwd[$i]["sv"]="&nbsp;";

		$senior[$i]["sj"]="&nbsp;";
		$senior[$i]["sv"]="&nbsp;";

		$student[$i]["sj"]="&nbsp;";
		$student[$i]["sv"]="&nbsp;";
		
		$station[$i]=$row['station_name'];

		$searchSQL="select * from authorization where date='".$auth_date."' and station='".$i."'";
		$searchRS=$db->query($searchSQL);
		$searchNM=$searchRS->num_rows;
		for($n=0;$n<$searchNM;$n++){
			$searchRow=$searchRS->fetch_assoc();
			if($searchRow['classification']=="pwd"){
				$pwd[$i]["sj"]=$searchRow['sjt'];
				$pwd[$i]["sv"]=$searchRow['svt'];

			}
			else if($searchRow['classification']=="senior"){
				$senior[$i]["sj"]=$searchRow['sjt'];
				$senior[$i]["sv"]=$searchRow['svt'];			
			}
			else if($searchRow['classification']=="stdt"){
				$student[$i]["sj"]=$searchRow['sjt'];
				$student[$i]["sv"]=$searchRow['svt'];			
			}
			
		
		}
	}
	
	echo "<table border=1 width=60%>";
	echo "<tr>";
	echo "<th colspan=7>Authorization Slip Report - ".$auth_date."</th>";
	echo "</tr>";
	echo "<tr>";
	echo "<th rowspan=2>Station</th>";
	echo "<th colspan=2>Senior</th><th colspan=2>Persons with Disabilities</th><th colspan=2>Student</th>";
	echo "</tr>";
	echo "<tr>";

	for($i=1;$i<4;$i++){
		echo "<th>SJD</th><th>SVD</th>";
	}
		echo "</tr>";

	for($i=1;$i<=$nm;$i++){
		echo "<tr>";
		echo "<td>".$station[$i]."</td>";
		echo "<td>".$senior[$i]["sj"]."</td>";
		echo "<td>".$senior[$i]["sv"]."</td>";			

		echo "<td>".$pwd[$i]["sj"]."</td>";
		echo "<td>".$pwd[$i]["sv"]."</td>";			

		echo "<td>".$student[$i]["sj"]."</td>";
		echo "<td>".$student[$i]["sv"]."</td>";			
		
		echo "</tr>";
	}
	
	
	echo "</table>";
	
}
?>