<a href='../index.php'>Go Back to Monitoring Menu</a>
<form action='sales report.php' method='post'>
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

	$sales_date=$year."-".$month."-".$day;
echo "<table border=1>";

echo "<tr>";
echo "<th rowspan=3>Stations</th>";
echo "<th colspan=4>Single Journey</th>";
echo "<th colspan=4>Discounted (DSJT)</th>";
echo "<th colspan=4>Stored Value</th>";
echo "<th colspan=4>Discounted(DSVT)</th>";
echo "<th rowspan=2 colspan=2>TIM</th>";
echo "<th rowspan=3 colspan=2>Total Sales</th>";
echo "</tr>";

echo "<tr>";
for($i=0;$i<4;$i++){
echo "<th colspan=2>Sales</th>";
echo "<th colspan=2>Refund</th>";
}
echo "</tr>";
echo "<tr>";
for($i=0;$i<9;$i++){
	echo "<th>Qty.</th>";
	echo "<th>Amount</th>";
}

echo "</tr>";


$tickets[0]="sjt";
$tickets[1]="sjd";
$tickets[2]="svt";
$tickets[3]="svd";
$tickets[4]="TIM";

for($n=0;$n<5;$n++){
	$total_sales[$tickets[$n]]['qty']=0;
	$total_sales[$tickets[$n]]['amount']=0;

	$total_refund[$tickets[$n]]['qty']=0;
	$total_refund[$tickets[$n]]['amount']=0;
	
}
$db=new mysqli("localhost","root","","station");
$stationSQL="select * from station order by id*1";
$stationRS=$db->query($stationSQL);
$stationNM=$stationRS->num_rows;

for($n=0;$n<$stationNM;$n++){
	$stationRow=$stationRS->fetch_assoc();
	$station[$stationRow['id']]=$stationRow['station_name'];
	$total_station[$stationRow['id']]['qty']=0;
	$total_station[$stationRow['id']]['amount']=0;

	
	
	
}


for($i=1;$i<=13;$i++){
	for($n=0;$n<4;$n++){
		$sales["Station_".$i][$tickets[$n]]["qty"]="0";
		$sales["Station_".$i][$tickets[$n]]["amount"]="0";

		$refund["Station_".$i][$tickets[$n]]["qty"]="0";
		$refund["Station_".$i][$tickets[$n]]["amount"]="0";
		
		
	}
	$sales["Station_".$i][$tickets[4]]["qty"]="0";
	$sales["Station_".$i][$tickets[4]]["amount"]="0";

}



$sql="select * from actual_sales where date='".$sales_date."' order by station*1";

$rs=$db->query($sql);
$nm=$rs->num_rows;



for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$sales["Station_".$row['station']][$row['type']]["qty"]=$row['quantity'];
	$sales["Station_".$row['station']][$row['type']]["amount"]=$row['amount'];
//	$sales["Station_4"][$row['type']]["qty"]=$row['quantity'];
	//$sales["Station_4"][$row['type']]["amount"]=$row['amount'];

	$total_sales[$row['type']]['qty']+=$row['quantity'];
	$total_sales[$row['type']]['amount']+=$row['amount'];

	$total_station[$row['station']]['qty']+=$row['quantity'];
	$total_station[$row['station']]['amount']+=$row['amount'];


//	$total_sales["Total"]['amount']+=$row['amount'];

	
	
}

$sql="select * from refund where date='".$sales_date."' order by station*1";

$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	$refund["Station_".$row['station']][$row['type']]["qty"]=$row['quantity'];
	$refund["Station_".$row['station']][$row['type']]["amount"]=$row['amount'];
//	$sales["Station_4"][$row['type']]["qty"]=$row['quantity'];
	//$sales["Station_4"][$row['type']]["amount"]=$row['amount'];
	$total_refund[$row['type']]['qty']+=$row['quantity'];
	$total_refund[$row['type']]['amount']+=$row['amount'];

	$total_station[$row['station']]['qty']-=$row['quantity'];
	$total_station[$row['station']]['amount']-=$row['amount'];
	

}

for($k=1;$k<=13;$k++){

	$total_sales["Total"]['amount']+=$total_station[$k]['amount'];	

}

for($k=1;$k<=13;$k++){
	echo "<tr>";
	echo "<td>".$station[$k]."</td>";
	for($n=0;$n<4;$n++){
		echo "<td>";

		echo $sales["Station_".$k][$tickets[$n]]["qty"];
		echo "</td>";
		echo "<td>";
		echo $sales["Station_".$k][$tickets[$n]]["amount"];
		echo "</td>";
		echo "<td>";
		echo $refund["Station_".$k][$tickets[$n]]["qty"];
		echo "</td>";
		echo "<td>";
		echo $refund["Station_".$k][$tickets[$n]]["amount"];
		echo "</td>";

	}
	echo "<td>";
	echo $sales["Station_".$k][$tickets[4]]["qty"];
	echo "</td>";
	echo "<td>";
	echo $sales["Station_".$k][$tickets[4]]["amount"];
	echo "</td>";
	
		echo "<td colspan=2>";
		echo $total_station[$k]["amount"];
		echo "</td>";		

	echo "</tr>";
}
	echo "<tr>";
	echo "<th>Sub-Total</th>";
	for($n=0;$n<4;$n++){
		echo "<td>";

		echo $total_sales[$tickets[$n]]["qty"];
		echo "</td>";
		echo "<td>";
		echo $total_sales[$tickets[$n]]["amount"];
		echo "</td>";
		echo "<td>";
		echo $total_refund[$tickets[$n]]["qty"];
		echo "</td>";
		echo "<td>";
		echo $total_refund[$tickets[$n]]["amount"];
		echo "</td>";

	}
	echo "<td>";
	echo $total_sales[$tickets[4]]["qty"];
	echo "</td>";
	echo "<td>";
	echo $total_sales[$tickets[4]]["amount"];
	
	echo "</td>";

	echo "<td colspan=2>";
	echo $total_sales["Total"]['amount'];
	echo "</td>";




	echo "</tr>";
	
	

echo "</table>";
}

?>