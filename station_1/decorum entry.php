<?php
$db=new mysqli("localhost","root","","station");
?>
<?php

if(isset($_GET['daily_id'])){
	$daily_id=$_GET['daily_id'];
}

if(isset($_POST['name'])){
	$name=$_POST['name'];
	$position=$_POST['position'];
	$shift=$_POST['shift'];
	$remarks=$_POST['remarks'];
	
	$daily_id=$_POST['daily_id'];



	$sql="insert into decorum_person(daily_id,name,position,shift,remarks) values ('".$daily_id."',\"".$name."\",'".$position."','".$shift."',\"".$remarks."\")";
	$rs=$db->query($sql);


	$person_id=$db->insert_id;

	for($i=1;$i<13;$i++){
		if(isset($_POST['item_'.$i])){
			$update="insert into decorum_violation(item_id,d_person_id) values ('".$i."','".$person_id."')";
			$updateRS=$db->query($update);
			
		}
	}
	
	echo "<script language='javascript'>";
	echo "window.opener.location.reload();";
	echo "</script>";
	
	

}
?>




<form action='decorum entry.php' method='post'>
<table style='border:1px solid gray' width=100%>
<tr>
<td align=right>Name:</td><td colspan=2><input type=text name='name' /></td>
<td align=right>Shift:</td><td colspan=2><input type=text name='shift' /></td>
</tr>
<tr>
<td align=right>Position:</td><td colspan=2><input type=text name='position' /></td>
<td align=right>Remarks:</td><td colspan=2><input type=text name='remarks' /></td>
</tr>
<tr>
<?php
$sql="select * from decorum limit 0,6";
$rs=$db->query($sql);
$nm=$rs->num_rows;
$itemCount=1;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
?>	
	<td><input type='checkbox' name='item_<?php echo $itemCount; ?>' /><?php echo $row['item']; ?></td>

<?php
	$itemCount++;
}
?>
</tr>
<tr>
<?php
$sql="select * from decorum limit 6,6";
$rs=$db->query($sql);
$nm=$rs->num_rows;

$itemCount=7;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	
?>	
	<td><input type='checkbox' name='item_<?php echo $itemCount; ?>' /><?php echo $row['item']; ?></td>

<?php
	$itemCount++;
}
?>
</tr>
<?php


if($daily_id==""){
}
else {
?>


<tr>
<th colspan=6>
<input type=hidden name='daily_id' value='<?php echo $daily_id; ?>' />

<input type=submit value='Submit' /></th>
</tr>
<?php
}
?>
</table>
</form>