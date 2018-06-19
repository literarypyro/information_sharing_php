<?php
require("db.php");
?>
<?php
$db=retrieveDb();
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


<link rel="stylesheet" href="layout/styles.css" />
<link rel="stylesheet" href="layout/bodyEntry.css" />

<form action='decorum entry.php' method='post'>
<table class="EntryTableCLC" width="80%" align="center"><tr><td>
<table class="miniHolderCLC">
<tr>
	<th class="HeaderCLC" colspan="6">Add New Entry</th>
</tr>
<tr>
<td align=right>Name:</td>
<td colspan=2>
<select name='name'>
<?php
$db=retrieveDb();
$sql="select * from ticket_seller order by last_name";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();

?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['last_name'].", ".$row['first_name']; ?></option>
<?php
}


?>

</select>


</td>
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
	<td>
	<input type='checkbox' name='item_<?php echo $itemCount; ?>' id="item_<?php echo $itemCount; ?>" />
	<?php echo '<label for="item_'.$itemCount.'">'.$row['item'].'</label>'; ?>
	</td>

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
	<td>
	<input type='checkbox' name='item_<?php echo $itemCount; ?>' id="item_<?php echo $itemCount; ?>" />
	<?php echo '<label for="item_'.$itemCount.'">'.$row['item'].'</label>'; ?>
	</td>
<?php
	$itemCount++;
}
?>
</tr>
</table></td></tr>
<?php


if($daily_id==""){
}
else {
?>
<tr>
<td class="EntrySubmitCLC">
<input type=hidden name='daily_id' value='<?php echo $daily_id; ?>' />

<input type=submit value='Submit' /></td>
</tr>
<?php
}
?>
</table>
</form>