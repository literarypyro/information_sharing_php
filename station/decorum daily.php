<?php
session_start();
?>
<?php
require("db.php");
?>
<?php
require("monitoring menu.php");
?>
<link href="layout/landbank/logbook style.css" rel="stylesheet" type="text/css"  id='stylesheet' />
<?php
$db=retrieveDb();if(isset($_POST['decorum_index'])){
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
<link rel="stylesheet" href="layout/body.css" />
<link rel="stylesheet" href="layout/styles.css" />
<div class="PgTitle">
Decorum Daily Report
</div>
<form action='decorum daily.php' method='post'>
<?php
$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");
?>

<ul class="SearchBar">
	<li>
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
	</li>
	<li>
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
	</li>
	<li>
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
	</li>
	<li>
<select name='station'>
<?php
$db=retrieveDb();
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
	</li>
	<li>
<input type=submit value='Get Records' />
	</li>
	<li style="float:right;">
		<input type="button" value="Add New Entry" onclick="PasaCLC()" />
	</li>
	<li style="float:right;">
		<input type="button" value="Generate DR5" onclick="window.open('generate_dr5.php')" />
	</li>
</ul>
</form>
<hr class="PgLine"/>
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

	
	if($_SESSION['user_station']==$station){
		$_SESSION['login_type']=="1";
	}
	else {
		if($_SESSION['user_role']=="3"){
			$_SESSION['login_type']==$_SESSION['user_role'];
		
		}
		else {
			$_SESSION['login_type']=="2";
		
		}
	
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
	
	if($_SESSION['user_station']==$station){
		$_SESSION['login_type']=="1";
	}
	else {
		if($_SESSION['user_role']=="3"){
			$_SESSION['login_type']==$_SESSION['user_role'];
		
		}
		else {
			$_SESSION['login_type']=="2";
		
		}
	
	}		
	
	
}
?>


	<table class="TableCLC">
		<th colspan="2" class="TableHeaderCLC">Station & Date</th>
	<tr>
		<td class="col1CLC">Station</td>
		<td class="col2CLC"><?php echo $station_name; ?></td>
	</tr>
	<tr>
		<td class="col1CLC">Date</td>
		<td class="col2CLC"><?php echo $daily_name; ?></td>
	</tr>
	</table>
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
		$person["dp_".$decorum_person_row['id']]["item_".$k]="<a href='#' style='color:#2F6A9E;font-size:13px;' onclick=\"tagDecorum('".$decorum_person_row['id']."','".$k."')\">mark</a>";
	}
	
	$decorum_violation_sql="select * from decorum_violation where d_person_id='".$decorum_person_row['id']."'";
	$decorum_violation_rs=$db->query($decorum_violation_sql);
	$decorum_violation_nm=$decorum_violation_rs->num_rows;

	for($k=0;$k<$decorum_violation_nm;$k++){
		$decorum_violation_row=$decorum_violation_rs->fetch_assoc();
		$person["dp_".$decorum_person_row['id']]["item_".$decorum_violation_row['item_id']]="<a href='#' class='XmarkCLC' onclick=\"removeViolation('".$decorum_violation_row['id']."')\">&#10004</a>";
	}
	
	
}

?>
<!--table width=100% border=1px style='border-collapse:collapse;' class='logbookTable'>
<tr>
<th>Reference ID</th>
<th>Name</th>
<th>Position</th>
<th>Shift</th-->
<div id="SHCLC">
<table class="BigTableCLC" id="SpTblCLC">
<tr>
<th>Reference ID</th>
<th>Name</th>
<th>Position</th>
<th>Shift</th>

<?php
function ClCSpT($a){
	$b = explode(' ',$a);
	return implode('<br>',$b);
}
$sql="select * from decorum limit 0,12";
$rs=$db->query($sql);
$nm=$rs->num_rows;

for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>	
	<th><?php echo ClCSpT($row['item']); ?></th>
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

	$ticket_seller_sql="select * from ticket_seller where id='".$person["dp_".$decorum_person_row['id']]['name']."' limit 1";
	$ticket_seller_rs=$db->query($ticket_seller_sql);
	$ticket_seller_row=$ticket_seller_rs->fetch_assoc();
	
	$ticket_seller_name=$ticket_seller_row['last_name'].", ".$ticket_seller_row['first_name'];	
		
	
	

?>
	<tr>
	<td class="UnclickableCLC"><?php echo $reference_id; ?></td>
	<td class="ClickableCLC"  <?php if($_SESSION['login_type']=="1"){ ?> onclick="fillEdit('name','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')" <?php } ?>><?php echo $ticket_seller_name; ?></td>
	<td class="ClickableCLC"  <?php if($_SESSION['login_type']=="1"){ ?> onclick="fillEdit('position','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')" <?php } ?>><?php echo $person["dp_".$decorum_person_row['id']]['position']; ?></td>
	<td class="ClickableCLC"  <?php if($_SESSION['login_type']=="1"){ ?> onclick="fillEdit('shift','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')" <?php } ?>><?php echo $person["dp_".$decorum_person_row['id']]['shift']; ?></td>
	<?php
	for($m=1;$m<=12;$m++){
	?>
		<td>
		<?php echo $person["dp_".$decorum_person_row['id']]['item_'.$m]; ?>
		</td>
	<?php
	}
	?>
	<td class="ClickableCLC" <?php if($_SESSION['login_type']=="1"){ ?> onclick="fillEdit('remarks','<?php echo $decorum_person_row['id']; ?>','<?php echo $reference_id; ?>')" <?php } ?>><?php echo $person["dp_".$decorum_person_row['id']]['remarks']; ?></td>
	<td class="DeletableCLC">	<a href='#'  <?php if($_SESSION['login_type']=="1"){ ?> onclick="deleteRow('<?php echo $decorum_person_row['id']; ?>','decorum')" <?php } ?>>X</a></td>

	</tr>
<?php
}
?>
</table>
</div>

<div id='fillDecorum' name='fillDecorum'></div>

<?php 
if($daily_id==""){
}
else {
	if($_SESSION['login_type']=="1"){
?>
<a href='#' style="display:none;" id="AddNewEntryCLC" onclick="window.open('decorum entry.php?daily_id=<?php echo $daily_id; ?>')">Add New Entry</a>
<!--a href='#' onclick="window.open('generate_dr5.php')">Generate DR5</a-->

<?php
	}
}
?>
<style>
#SHCLC{
height:300px;
width:98%;
overflow:scroll;
padding:13px 0px;
margin:auto;
background:#d2d4ce;
border:1px solid #999;
box-shadow:0px 0px 5px 0px #AAA;
}
#SpTblCLC{
margin:0px 10px;
width:150%;
}

#SpTblCLC td:nth-child(1n+4){
text-align:center;
}

</style>
