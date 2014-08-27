<?php
session_start();
?>
<?php
require("monitoring menu.php");
?>
<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['decorum_index'])){
	$element=$_POST['formElement'];
	$update="update decorum_person set ".$element."='".$_POST[$element]."' where id='".$_POST['decorum_index']."'";

	
	$updateRS=$db->query($update);	

}
?>

<script language='javascript' src='ajax.js'></script>
<script language='javascript'>

function deleteRow(index,table){
	var check=confirm("Remove Record?");
	if(check){
		makeajax("processing.php?removeRow="+index+"&tableType="+table,"reloadPage");	
	}
}

function reloadPage(ajaxHTML){
	self.location="decorum daily.php";


}
function tagDecorum(person_id,item_id){

	var check=confirm("Mark a violation?");
	if(check){
		makeajax("processing.php?tagDecorum=Y&item_id="+item_id+"&person_id="+person_id,"reloadPage");	
		
	
	}


}
function removeViolation(violation_id){
	var check=confirm("Unmark Violation?");
	if(check){
		makeajax("processing.php?removeViolation="+violation_id,"reloadPage");	

	}


}
function fillEdit(element,decorum_id,reference_id){
	var elementHTML="<br>";
	elementHTML+="<form action='decorum daily.php' method='post' >";
	elementHTML+="<table style='border:1px solid red'>";
	elementHTML+="<tr>";
	elementHTML+="<td>Reference ID</td>";
	elementHTML+="<td>"+reference_id+"</td>";
	
	elementHTML+="</tr>";		
	elementHTML+="<tr>";
	elementHTML+="<td>Enter "+element.toUpperCase()+"</td>";
	elementHTML+="<td><input type=text name='"+element+"' /></td>";	
	
	elementHTML+="</tr>";

	elementHTML+="<tr>";
	elementHTML+="<td colspan=2 align=center>";
	elementHTML+="<input type=hidden name='formElement' id='formElement' value='"+element+"' />";	
	elementHTML+="<input type='hidden' name='decorum_index' value='"+decorum_id+"' />";
	elementHTML+="<input type=submit value='Edit' />";
	elementHTML+="</td>"; 
	elementHTML+="</tr>";


	elementHTML+="</table>";
	elementHTML+="</form>";
	
	document.getElementById('fillDecorum').innerHTML=elementHTML;		
	
}


</script>
<br>
<br>
<form action='decorum daily.php' method='post'>
<?php
$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");
?>


<select name='month'>
<?php
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

<select name='station'>
<?php
$db=new mysqli("localhost","root","","station");

$sql="select * from station";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();

?>
	<option value='<?php echo $row['id']; ?>'><?php echo $row['station_name']; ?></option>




<?php
}


?>

</select>
<input type=submit value='Get Records' />
</form>
<?php
if(isset($_SESSION['month'])){
	$year=$_SESSION['year'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];
	
	$station=$_SESSION['station'];
	
	$daily_date=$year."-".$month."-".$day;
	$daily_name=date("F d, Y",strtotime($daily_date));
	
	$sql="select * from station where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];
	
	$daily_id="";
	
	
	
	$id_sql="select * from decorum_daily where date='".$daily_date."' and station='".$station."'";
	$id_rs=$db->query($id_sql);
	
	$id_nm=$id_rs->num_rows;
	
	if($id_nm>0){
		$id_row=$id_rs->fetch_assoc();
		$daily_id=$id_row['id'];
	}


}


if(isset($_POST['month'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$station=$_POST['station'];
	
	$daily_date=$year."-".$month."-".$day;
	$daily_name=date("F d, Y",strtotime($daily_date));
	
	$sql="select * from station where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];
	
	$daily_id="";
	
	
	
	$id_sql="select * from decorum_daily where date='".$daily_date."' and station='".$station."'";
	$id_rs=$db->query($id_sql);
	
	$id_nm=$id_rs->num_rows;
	
	if($id_nm>0){
		$id_row=$id_rs->fetch_assoc();
		$daily_id=$id_row['id'];
	}
	else {
		$update="insert into decorum_daily(date,station) values ('".$daily_date."','".$station."')";
		$updateRS=$db->query($update);
		$daily_id=$db->insert_id;	
	}
	
	
	$_SESSION['station']=$station;
	$_SESSION['month']=$month;
	$_SESSION['year']=$year;
	$_SESSION['day']=$day;
	
	
	
}
?>


<table width=100% style='border:1px solid gray'>
<tr>
<td width=50%>
Station:
<?php
echo $station_name;
?>
</td>
<td width=50%>
Date: 
<?php
echo $daily_name;
?>
</td>
</tr>
</table>
<br>
<?php
$decorum_person_sql="select * from decorum_person where daily_id='".$daily_id."' order by shift";
$decorum_person_rs=$db->query($decorum_person_sql);
$decorum_person_nm=$decorum_person_rs->num_rows;

for($i=0;$i<$decorum_person_nm;$i++){
	$decorum_person_row=$decorum_person_rs->fetch_assoc();
	
	$person["dp_".$decorum_person_row['id']]["id"]=$decorum_person_row['id'];
	$person["dp_".$decorum_person_row['id']]["name"]=$decorum_person_row['name'];
	$person["dp_".$decorum_person_row['id']]["position"]=$decorum_person_row['position'];
	$person["dp_".$decorum_person_row['id']]["shift"]=$decorum_person_row['shift'];
	$person["dp_".$decorum_person_row['id']]["remarks"]=$decorum_person_row['remarks'];
	
	for($k=1;$k<13;$k++){
		$person["dp_".$decorum_person_row['id']]["item_".$k]="<a href='#' style='color:white;' onclick=\"tagDecorum('".$decorum_person_row['id']."','".$k."')\">MARK</a>";
	}
	
	$decorum_violation_sql="select * from decorum_violation where d_person_id='".$decorum_person_row['id']."'";
	$decorum_violation_rs=$db->query($decorum_violation_sql);
	$decorum_violation_nm=$decorum_violation_rs->num_rows;

	for($k=0;$k<$decorum_violation_nm;$k++){
		$decorum_violation_row=$decorum_violation_rs->fetch_assoc();
		$person["dp_".$decorum_person_row['id']]["item_".$decorum_violation_row['item_id']]="<a href='#' onclick=\"removeViolation('".$decorum_violation_row['id']."')\">X</a>";
	}
	
	
}

?>
<table width=100% border=1px style='border-collapse:collapse;'>
<tr>
<th>Reference ID</th>
<th>Name</th>
<th>Position</th>
<th>Shift</th>

<?php
$sql="select * from decorum limit 0,6";
$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>	
	<th><?php echo $row['item']; ?></th>

<?php
}
?>
<th>Remarks</th>
</tr>
<?php
$decorum_person_sql="select * from decorum_person where daily_id='".$daily_id."' order by shift";
$decorum_person_rs=$db->query($decorum_person_sql);
$decorum_person_nm=$decorum_person_rs->num_rows;

$reference_stamp=date("Ymd");	
for($i=0;$i<$decorum_person_nm;$i++){
	$reference_id=$reference_stamp."_".$i;		
	$decorum_person_row=$decorum_person_rs->fetch_assoc();
?>
	<tr>
	<th><?php echo $reference_id; ?></th>
	<th><?php echo $person["dp_".$decorum_person_row['id']]['name']; ?> <a href='#' onclick="fillEdit('name','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></th>
	<th><?php echo $person["dp_".$decorum_person_row['id']]['position']; ?> <a href='#' onclick="fillEdit('position','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></th>
	<th><?php echo $person["dp_".$decorum_person_row['id']]['shift']; ?> <a href='#' onclick="fillEdit('shift','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></th>
	<?php
	for($m=1;$m<=6;$m++){
	?>
		<th>
		<?php echo $person["dp_".$decorum_person_row['id']]['item_'.$m]; ?>
		</th>
	<?php
	}
	?>
	<th><?php echo $person["dp_".$decorum_person_row['id']]['remarks']; ?> <a href='#' onclick="fillEdit('remarks','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a>
	</th>
	<th>
	<a href='#' onclick="deleteRow('<?php echo $decorum_person_row['id']; ?>','decorum')">X</a>
	</th>

	</tr>
<?php
}
?>
</table>
<br>
<br>
<table width=100% border=1px style='border-collapse:collapse;'>
<tr>
<th>Reference ID</th>
<th>Name</th>
<th>Position</th>
<th>Shift</th>



<?php
$sql="select * from decorum limit 6,6";
$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>	
	<th><?php echo $row['item']; ?></th>

<?php
}
?>
<th>Remarks</th>
</tr>
<?php
$decorum_person_sql="select * from decorum_person where daily_id='".$daily_id."' order by shift";
$decorum_person_rs=$db->query($decorum_person_sql);
$decorum_person_nm=$decorum_person_rs->num_rows;

$reference_stamp=date("Ymd");	
for($i=0;$i<$decorum_person_nm;$i++){
	$reference_id=$reference_stamp."_".$i;		

	$decorum_person_row=$decorum_person_rs->fetch_assoc();
?>
	<tr>
	<th><?php echo $reference_id; ?></th>
	<th><?php echo $person["dp_".$decorum_person_row['id']]['name']; ?> <a href='#' onclick="fillEdit('name','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></th>
	<th><?php echo $person["dp_".$decorum_person_row['id']]['position']; ?> <a href='#' onclick="fillEdit('position','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></th>
	<th><?php echo $person["dp_".$decorum_person_row['id']]['shift']; ?> <a href='#' onclick="fillEdit('shift','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></th>
	<?php
	for($m=7;$m<=12;$m++){
	?>
		<th>
		<?php echo $person["dp_".$decorum_person_row['id']]['item_'.$m]; ?>
		</th>
	<?php
	}
	?>
	<th><?php echo $person["dp_".$decorum_person_row['id']]['remarks']; ?> <a href='#' onclick="fillEdit('remarks','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></th>
	<th><a href='#' onclick="deleteRow('<?php echo $decorum_person_row['id']; ?>','decorum')">X</a>
	
	
	</th>

	</tr>
<?php
}
?>


</table>
<br>
<div id='fillDecorum' name='fillDecorum'>



</div>


<br>
<?php 
if($daily_id==""){
}
else {
?>
<a href='#' onclick="window.open('decorum entry.php?daily_id=<?php echo $daily_id; ?>')">Add New Entry</a>
<br>
<br>
<br>
<a href='#' onclick="window.open('generate_dr5.php')">Generate DR5</a>

<?php
}
?>

