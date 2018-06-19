<!--- Modified by Jun
//--- Date: 8/6/2014
//--- Modify: screen layout
//--- Marker: @mjun
//--------------------------------------------------->
<style type='text/css'>

.rowClass {background-color: #F3F3F3;}

/* color header */
.rowHeading {background-color: #cccccc;
			color:black
}
.train_ava td{
	border: 1px solid #FBCC2A;
	color: black;
	cellpadding: 5px
}

.train_ava th {
	border: 1px solid #FBCC2A;;
	cellpadding: 5px;	
	color: black
}

select { border: 1px solid rgb(185, 201, 254); color: black; background-color: #FFFACD; }

/* --- mjun -- generate */
a.two:visited {color:black;}
a.two:hover, a.two:active {font-size:120%; color:orange;}

</style>

<!-- <form action='statistics_report.php' method='post'> -->
<form action='statistics_report_modified.php' method='post'>
<table>
<tr><th>Level</th>
<td>
<select name='level'>
<option value='1'>1</option>
<option value='2'>2</option>
<option value='3'>3</option>
</select>
</td>
</tr>
<tr>
<th>Year</th>
<td>
<?php
$startYear=1999;

$endYear=date("Y")*1+16;

?>
<select name='year'>
<?php
for($k=$startYear;$k<=$endYear;$k++){
?>
	<option value="<?php echo $k; ?>"><?php echo $k; ?></option>

<?php
}
?>
</select>
</td>
</tr>
<tr>
<th colspan=2><input type=submit value='Submit' /></th>
</tr>
</table>
</form>

<?php
$db=new mysqli("localhost","root","","transport");
$sql="select * from equipment where id in ('114','102','110','11','113','104','108','109','103','124','67','111','112','105','81','118','119','64','115','89','120','123','121','116','2','122','117','105','81','118','119','64','115','89','120','123','121','116','2','122','117') order by equipment_name";

$rs=$db->query($sql);

$nm=$rs->num_rows;




for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();

	$equipt[$i]['id']=$row['id'];
	$equipt[$i]['equipment']=$row['equipment_name'];
	for ($k=1;$k<=12;$k++){
		$equipt_count["Equipt_".$row['id']]["Month_".$k]=0;
		
	}
	
	
	
	
}



if(isset($_POST['year'])){
	$year=$_POST['year'];
	$level=$_POST['level'];
}
else {
	$year=date("Y");
	$level="2";
}

?>
<h2><?php echo "Year ".$year; echo " / ";echo " Level ".$level;?></h2>
<!-- <h2><?php echo "Level ".$level; ?></h2> -->
<table class='train_ava' border=1px style='border-collapse:collapse;' width=100%>
<tr class='rowHeading'>
<th>&nbsp;</th>
<?php
for($k=1;$k<=12;$k++){
?>	
	<th>
	<?php echo date("F",strtotime(date("Y")."-".$k."-01")); ?>
	</th>
	
<?php
}


//$sql="select *,count(1) as equipt_count from incident_report where level='".$level."' and incident_date between '".$start_date." 00:00:00' and '".$end_date." 23:59:59' and equipt in ('114','102','110','11','113','104','108','109','103','124','67','111','112','105','81','118','119','64','115','89','120','123','121','116','2','122','117','105','81','118','119','64','115','89','120','123','121','116','2','122','117') group by equipt";
//$rs=$db->query($sql);

//$nm=$rs->num_rows;


?>



</tr>
<?php
for($i=1;$i<=12;$i++){
	$month_heading=date("F",strtotime($year."-".$i."-01"));
	
	
	$date_limit=date("t",strtotime($year."-".$i."-01"));
	$start_date=date("Y-m-d",strtotime($year."-".$i."-01"));
	$end_date=date("Y-m-d",strtotime($year."-".$i."-".$date_limit));

	
	$sql="select *,count(1) as equipt_count from incident_report where level='".$level."' and incident_date between '".$start_date." 00:00:00' and '".$end_date." 23:59:59' and equipt in ('114','102','110','11','113','104','108','109','103','124','67','111','112','105','81','118','119','64','115','89','120','123','121','116','2','122','117','105','81','118','119','64','115','89','120','123','121','116','2','122','117') group by equipt";
	
	if($i==1){
//		echo $sql;
//		echo "<br>";
	}
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	for($k=0;$k<$nm;$k++){
		$row=$rs->fetch_assoc();
		$equipt_count["Equipt_".$row['equipt']]["Month_".$i]=$row['equipt_count'];
	}


	$sql="select *,count(1) as equipt_count from incident_report inner join external.incident_defects on incident_report.id=external.incident_defects.incident_id where level='".$level."' and incident_date between '".$start_date." 00:00:00' and '".$end_date." 23:59:59' and external.incident_defects.equipt_id in ('114','102','110','11','113','104','108','109','103','124','67','111','112','105','81','118','119','64','115','89','120','123','121','116','2','122','117','105','81','118','119','64','115','89','120','123','121','116','2','122','117') group by equipt_id"; 
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($i==1){
//		echo $sql;
//		echo "<br>";
	}	
	for($k=0;$k<$nm;$k++){
		$row=$rs->fetch_assoc();
		$equipt_count["Equipt_".$row['equipt_id']]["Month_".$i]+=$row['equipt_count'];
	}



/*	
	$sql="select *,count(1) as equipt_count from incident_report where level='".$level."' and incident_date between '".$start_date." 00:00:00' and '".$end_date." 23:59:59' and equipt in ('105','81','118','119','64','115','89','120','123','121','116','2','122','117','105','81','118','119','64','115','89','120','123','121','116','2','122','117') group by level";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	if($nm>0){
		$row=$rs->fetch_assoc();
		$equipt_count["Equipt_Others"]["Month_".$i]=$row['equipt_count'];
	}

	$sql="select *,count(1) as equipt_count from incident_report inner join external.incident_defects on incident_report.id=external.incident_defects.incident_id where level='".$level."' and incident_date between '".$start_date." 00:00:00' and '".$end_date." 23:59:59' and external.incident_defects.equipt_id in ('105','81','118','119','64','115','89','120','123','121','116','2','122','117','105','81','118','119','64','115','89','120','123','121','116','2','122','117') group by level"; 
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	for($k=0;$k<$nm;$k++){
		$row=$rs->fetch_assoc();
		$equipt_count["Month_".$i]["Equipt_".$row['equipt_id']]+=$row['equipt_count'];
	}
*/

	
}	
?>	

<?php

$sql="select * from equipment where id in ('114','102','110','11','113','104','108','109','103','124','67','111','112','105','81','118','119','64','115','89','120','123','121','116','2','122','117','105','81','118','119','64','115','89','120','123','121','116','2','122','117') order by equipment_name";

$rs=$db->query($sql);

$nm=$rs->num_rows;



for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>
<tr <?php if($i%2>0){ echo "class='rowClass'"; } ?>>
	<th>
	<?php echo $row['equipment_name']; ?>
	</th>

	<?php
	for($k=1;$k<=12;$k++){
	?>	
		<td class='stat_hover' align=center>
		<a href='#' style='text-decoration:none; color:black;' onclick='window.open("equipment_history.php?equipt=<?php echo $equipt[$i]['id']; ?>&y=<?php echo $year; ?>&m=<?php echo $k; ?>&level=<?php echo $level; ?>",target="_self")' >
		<?php
		echo $equipt_count["Equipt_".$equipt[$i]['id']]["Month_".$k];
		?>
		</a>
		</td>


	<?php
	}
	?>
	
</tr>	
<?php	
	
}

?>

</table>
<br>
<br>
<a href='#' class="two" onclick='window.open("generate_statistics_report.php?year=<?php echo $year; ?>&level=<?php echo $level; ?>");'><b>Generate Printout</b></a>
<style type='text/css'>
.stat_hover:hover {
	background-color:#fbcc2a;
	text-decoration:underline;
	font-weight:bold;
}
</style>
