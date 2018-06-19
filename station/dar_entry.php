<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
require("db.php");
?>
<?php
$db=retrieveDb();
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

<link rel="stylesheet" href="layout/styles.css" />
<link rel="stylesheet" href="layout/bodyEntry.css" />

<form action='dar_entry.php' method='post'>
<table class="EntryTableCLC" align="center" width="35%">
<tr>
	<th class="HeaderCLC">Add - Daily Accomplishment Report</th>
</tr>
<tr>
	<td><table class="miniHolderCLC">
	<tr>
	<td>Ticket Seller</td>
	<td>
	<select name='ticket_seller'>
	<?php
	
	$db=retrieveDb();
	
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
<tr>
<td>Shift</td>
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
<tr>
<td>Schedule Type</td>
<td>
<select name='sked_type'>
<option value='reg'>Regular</option>
<option value='ot'>OT</option>
</select>
</td>
</tr>
<tr>
<td>A/D No.</td>
<td>
<input type=text name='ad_no' size='5' placeholder="A/D No." />
</td>
</tr>
<tr>
<td>Amount Remitted</td>
<td><input type='text' name='remitted' placeholder="Amount Remitted" /></td>
</tr>
	</table></td>
</tr>
<tr>
	<td class="HeaderCLC">Tickets Sold</td>
</tr>
<tr>
<td>
	<table class="miniHolderCLC">
		<tr>
			<td colspan="2" style="width:100%;marging:0px;padding:0px;" style="background:red;">
				<table style="width:100%;marging:0px;padding:0px;">
					<tr>
						<td width="10%">SJT</td>
						<td width="40%"><input type=text name='sjt' size='5' placeholder="SJT"></td>
						<td width="10%">SJD</td>
						<td width="40%"><input type=text name='sjd' size='5' placeholder="SJD"></td>
					</tr>
					<tr>
						<td>SVT</td><td><input type=text name='svt' size='5' placeholder="SVT"></td>
						<td>SVD</td><td><input type=text name='svd' size='5' placeholder="SVD"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>Total Amt. of Refund</td><td><input type=text name='refund' placeholder="Total Amount of Refund" /></td>
		</tr>
		<tr>
			<td>Cash Discrepancy</td>
			<td>
			<select name='discrep'>
			<option value='shortage'>Shortage</option>
			<option value='overage'>Overage</option>
			</select>
			<input type='text' name='discrep_value' placeholder="Cash Discrepancy"/>
			</td>
		</tr>
	</table>
</td>
</tr>
<tr>
	<td class="HeaderCLC">Ticket Discrepancies</td>
</tr>
<tr>
<td>
	<table class="miniHolderCLC">
		<tr>
			<td>SJT</td>
			<td>
				<select name='discrep_sjt'>
					<option value='shortage'>Shortage</option>
					<option value='overage'>Overage</option>
				</select>
				<input type=text name='d_sjt' placeholder="SJT"/>
			</td>
		</tr>
		<tr>
			<td>SJD</td>
			<td>
				<select name='discrep_sjd'>
					<option value='shortage'>Shortage</option>
					<option value='overage'>Overage</option>
				</select>
			
				<input type=text name='d_sjd' placeholder="SJD" />
			</td>
		</tr>
		<tr>
			<td>SVT</td>
			<td>
				<select name='discrep_svt'>
					<option value='shortage'>Shortage</option>
					<option value='overage'>Overage</option>
				</select>
				<input type=text name='d_svt' placeholder="SVT" />		
			</td>
		</tr>
		<tr>
			<td>SVD</td>
			<td>
				<select name='discrep_svd'>
					<option value='shortage'>Shortage</option>
					<option value='overage'>Overage</option>
				</select>
				<input type=text name='d_svd' placeholder="SVD" />
			</td>
		</tr>
		<tr>
			<td>Remarks</td>
			<td><textarea name='remarks' cols=40 rows=4 placeholder="Remarks"></textarea></td>
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
<tr>
	<td colspan="2" class="EntrySubmitCLC">
					<input type=hidden name='daily_id' value='<?php echo $daily_id; ?>' /><input type='submit' value='Submit' />
	</td>
</tr>
<?php
}
?>

</td>
</tr>
</table>
</form>