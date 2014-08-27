<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['ticket_seller'])){
	$daily_id=$_POST['daily_id'];
	
	$ticket_seller=$_POST['ticket_seller'];
	$shift=$_POST['shift'];
	
	$ad_no=$_POST['ad_no'];
	$remitted=$_POST['remitted'];
	$refund=$_POST['refund'];
	
	$sked_type=$_POST['sked_type'];
	
	
	$sjt=$_POST['sjt'];
	$svt=$_POST['svt'];
	$sjd=$_POST['sjd'];
	$svd=$_POST['svd'];

	$discrepancy=$_POST['discrep'];
	$shortage="";
	$overage="";
	
	$remarks=$_POST['remarks'];

	if($discrepancy=="overage"){
		$overage=$_POST['discrep_value'];
	}
	else if($discrepancy=="shortage"){
		$shortage=$_POST['discrep_value'];
	}
	
	
	$dar_entry_search="select * from dar where ticket_seller='".$ticket_seller."' and shift='".$shift."' and ad_no='".$ad_no."' and sked_type='".$sked_type."' and daily_id='".$daily_id."'";
	$dar_entry_rs=$db->query($dar_entry_search);
	
	$dar_entry_nm=$dar_entry_rs->num_rows;
	
	if($dar_entry_nm>0){
		$dar_entry_row=$dar_entry_rs->fetch_assoc();
		$dar_entry_id=$dar_entry_row['id'];
		
		$dar_entry_update="update dar set remitted='".$remitted."',refund='".$refund."',remarks=\"".$remarks."\" where id='".$dar_entry_id."'";
		$dar_entry_update_rs=$db->query($dar_entry_update);
			
		$sold_entry_update="update ticket_sold set sjt='".$sjt."',svt='".$svt."',sjd='".$sjd."',svd='".$svd."' where dar_id='".$dar_entry_id."'";
		$sold_entry_update_rs=$db->query($sold_entry_update);
		
		$discrepancy_entry_update="update discrepancy set shortage='".$shortage."',overage='".$overage."' where dar_id='".$dar_entry_id."'";
		$discrepancy_entry_update_rs=$db->query($discrepancy_entry_update);
	}
	else {
		$dar_entry_update="insert into dar(ticket_seller,shift,ad_no,remitted,refund,remarks,daily_id,sked_type) ";
		$dar_entry_update.=" values ('".$ticket_seller."','".$shift."','".$ad_no."','".$remitted."','".$refund."',\"".$remarks."\",'".$daily_id."','".$sked_type."')";
		
		$dar_entry_update_rs=$db->query($dar_entry_update);
		
		$dar_entry_id=$db->insert_id;
		
		
		$sold_entry_update="insert into ticket_sold(sjt,svt,sjd,svd,dar_id) values ('".$sjt."','".$svt."','".$sjd."','".$svd."','".$dar_entry_id."')";
		$sold_entry_update_rs=$db->query($sold_entry_update);
		
		$discrepancy_entry_update="insert into discrepancy(shortage,overage,dar_id) values ('".$shortage."','".$overage."','".$dar_entry_id."')";
		$discrepancy_entry_update_rs=$db->query($discrepancy_entry_update);
		
	}
	
	$tickets=array("sjt","sjd","svt","svd");
	
	for($n=0;$n<count($tickets);$n++){
		if($_POST["d_".$tickets[$n]]==""){
		}
		else {
			$search="select * from discrepancy_ticket where dar_id='".$dar_entry_id."' and ticket_type='".$tickets[$n]."' limit 1";
			$searchRS=$db->query($search);
			
			$searchNM=$searchRS->num_rows;
			$overage="";
			$shortage="";
			$discrep_type=$_POST['discrep_'.$tickets[$n]];

			if($discrep_type=="overage"){
				$overage=$_POST["d_".$tickets[$n]];

			}
			else if($discrep_type=="shortage"){
				$shortage=$_POST["d_".$tickets[$n]];

				
			}

			
			if($searchNM>0){
				$searchRow=$searchRS->fetch_assoc();
				
				$update="update discrepancy_ticket set shortage='".$shortage."',overage='".$overage."' where id='".$searchRow['id']."'";
				$updateRS=$db->query($update);
		
			}
			else {


				$update="insert into discrepancy_ticket(dar_id,ticket_type,shortage,overage) values ";
				$update.="('".$dar_entry_id."','".$tickets[$n]."','".$shortage."','".$overage."')";
			
				$updateRS=$db->query($update);				
				
			
			
			}
		}
	}
	/*
	
	
	
	
	if($_POST['sjt']==""){
	}
	else {
	
	
	
	}
	
	if($_POST['sjd']==""){
	}
	else {
	
	
	
	}
	if($_POST['svt']==""){
	}
	else {
	
	
	
	}
	
	if($_POST['svd']==""){
	}
	else {
	
	
	
	
	}
	*/
	echo "<script language='javascript'>";
	
	echo "window.opener.location='daily_accomplishment.php';";
	
	
	echo "</script>";
	
	
}
?>
<link href="layout/landbank/control slip.css" rel="stylesheet" type="text/css"  id='stylesheet' />

<form action='dar_entry.php' method='post'>
<table border=1>
<tr>
<td>
<table width=100% class='controlTable'>
<tr class='header'>
<th>Ticket Seller</th>
<td>

<select name='ticket_seller'>
<?php
$db=new mysqli("localhost","root","","station");

$sql="select * from ticket_seller order by last_name";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();

?>
	<option value='<?php echo $row['id']; ?>'><?php echo $row['last_name'].", ".$row['first_name']; ?></option>




<?php
}


?>

</select>
</td>
</tr>
<tr class='grid'>
<th>Shift</th>
<td>
<select name='shift'>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
</select>
</td>

</tr>
<tr class='category'>
<th>Schedule Type</th>
<td>
<select name='sked_type'>
<option value='reg'>Regular</option>
<option value='ot'>OT</option>
</select>
</td>
</tr>
<tr>
<th>A/D No.</th>
<td>
<input type=text name='ad_no' size='5' />
</td>
</tr>
<tr>
<th>Amount Remitted</th>
<td><input type='text' name='remitted' /></td>
</tr>

</table>

<br>
<table width=100% class='controlTable' >
<tr class='header'>
<th colspan=4>Tickets Sold</th>
</tr>
<tr class='grid'>
<th>SJT</th><td><input type=text name='sjt' size='5'></td>
<th>SJD</th><td><input type=text name='sjd' size='5'></td>
</tr>
<tr class='category'>
<th>SVT</th><td><input type=text name='svt' size='5'></td>
<th>SVD</th><td><input type=text name='svd' size='5'></td>
</tr>

</table>
<br>
<table width=100% class='controlTable'>
<tr>
<th>Total Amt. of Refund</th><td><input type=text name='refund' /></td>
</tr>
<tr>
<th>Cash Discrepancy</th>
<td>
<select name='discrep'>
<option value='shortage'>Shortage</option>
<option value='overage'>Overage</option>
</select>
<input type='text' name='discrep_value' />
</td>
<tr>
	<th colspan=2 class='header'>Ticket Discrepancies</th>
</tr>
<tr>
<th>SJT</th>
<td>
	<select name='discrep_sjt'>
		<option value='shortage'>Shortage</option>
		<option value='overage'>Overage</option>
	</select>
	<input type=text name='d_sjt' />
	
</td>
</tr>
<tr>
<th>SJD</th>
<td>
	<select name='discrep_sjd'>
		<option value='shortage'>Shortage</option>
		<option value='overage'>Overage</option>
	</select>

	<input type=text name='d_sjd' />
	
</td>


</tr>
<tr>
<th>SVT</th>
<td>
	<select name='discrep_svt'>
		<option value='shortage'>Shortage</option>
		<option value='overage'>Overage</option>
	</select>
	<input type=text name='d_svt' />

	
</td>


</tr>
<tr>
<th>SVD</th>
<td>
	<select name='discrep_svd'>
		<option value='shortage'>Shortage</option>
		<option value='overage'>Overage</option>
	</select>
	<input type=text name='d_svd' />

	
</td>


</tr>

</tr>
<tr>
<th>Remarks</th>
<td><textarea name='remarks' cols=40 rows=4></textarea></td>
</tr>

</table>
<?php
if(isset($_GET['daily_id'])){
	$daily_id=$_GET['daily_id'];


}



if($daily_id==""){
}
else {
?>
<table width=100%>
<tr>
<th><input type=hidden name='daily_id' value='<?php echo $daily_id; ?>' /><input type='submit' value='Submit' /></th>
</tr>

</table>

<?php
}
?>
</td>
</tr>
</table>
</form>