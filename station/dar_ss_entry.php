<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['daily_id'])){
	$daily_id=$_POST['daily_id'];
	$shift1=$_POST['s1'];
	$shift2=$_POST['s2'];
	$shift3=$_POST['s3'];
	
	$supervisor_sql="select * from dar_supervisor where daily_id='".$daily_id."'";
	$supervisor_rs=$db->query($supervisor_sql);
	$supervisor_nm=$supervisor_rs->num_rows;
	if($supervisor_nm>0){
		$supervisor_row=$supervisor_rs->fetch_assoc();
		
		$supervisor_update="update dar_supervisor set s1='".$shift1."',s2='".$shift2."',s3='".$shift3."' where id='".$supervisor_row['id']."'";
		$supervisor_update_rs=$db->query($supervisor_update);
	}
	else {
		
		$supervisor_update="insert into dar_supervisor(s1,s2,s3,daily_id) values ('".$shift1."','".$shift2."','".$shift3."','".$daily_id."')";
		$supervisor_update_rs=$db->query($supervisor_update);
	}	
	
	echo "<script language='javascript'>";
	
	echo "window.opener.location.reload();";
	
	
	echo "</script>";		
	
}
?>
<?php
if(isset($_GET['daily_id'])){
	$daily_id=$_GET['daily_id'];
}
?>
<link rel="stylesheet" href="layout/styles.css" />
<link rel="stylesheet" href="layout/bodyEntry.css" />
<form action='dar_ss_entry.php' method='post'>
<table class="EntryTableCLC" align="center" width="40%" ><tr><td>
<table class="miniHolderCLC">
<tr>
	<th class="HeaderCLC" colspan="2">Add - Station Supervisors</th>
</tr>
<tr>
	<td class="LabelCLC">Shift 1</td>
	<td><input type=text name='s1' placeholder="Shift 1"/></td>
</tr>
<tr>
	<td class="LabelCLC">Shift 2</td>
	<td><input type=text name='s2' placeholder="Shift 2"/></td>
</tr>
<tr>
	<td class="LabelCLC">Shift 3</td>
	<td><input type=text name='s3' placeholder="Shift 3"/></td>
</tr>
	<input type="hidden" value='<?php echo $daily_id; ?>' name='daily_id' />
</table>
</td></tr>
<tr>
	<td class="EntrySubmitCLC">
	<input type="submit" value='Submit' />
	</td>
</tr>
</table>
</form>