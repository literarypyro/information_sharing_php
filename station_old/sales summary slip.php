<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['sales_daily_id'])){
	$daily_id=$_POST['sales_daily_id'];
	$ticket_type=$_POST['ticket_type'];
	$regular_sold=$_POST['regular_sold'];
	$regular_amount=$_POST['regular_amount'];
	$discounted_sold=$_POST['discounted_sold'];
	$discounted_amount=$_POST['discounted_amount'];
		
	$search="select slip_id,id from sales_entry where slip_id='".$daily_id."' and type='".$ticket_type."' limit 1";
	$searchRS=$db->query($search);
	$searchNM=$searchRS->num_rows;
	if($searchNM>0){
		$searchRow=$searchRS->fetch_assoc();
		$entry_id=$searchRow['id'];
		
		$update="update sales_entry set reg_amount='".$regular_amount."',disc_amount='".$discounted_amount."',reg_sold='".$regular_sold."',discounted_sold='".$discounted_sold."' where id='".$entry_id."'";
		$updateRS=$db->query($update);	
			
	
	}	
	else {
		$update="insert into sales_entry(slip_id,type,reg_amount,disc_amount,reg_sold,disc_sold) ";
		$update.=" values ";
		$update.="('".$daily_id."','".$ticket_type."','".$regular_amount."','".$discounted_amount."','".$regular_sold."','".$discounted_sold."')";
		$updateRS=$db->query($update);
	
	
	}
	
	
}

if(isset($_POST['refund_daily_id'])){
	$daily_id=$_POST['refund_daily_id'];
	$ticket_type=$_POST['ticket_type'];
	
	$reg_ticket_refund=$_POST['regular_ticket_refunded'];
	$reg_amount_refund=$_POST['regular_amount_refunded'];

	$disc_ticket_refund=$_POST['discounted_ticket_refunded'];
	$disc_amount_refund=$_POST['discounted_amount_refunded'];

	
//	$refund_reason=$_POST['refund_reason'];
	
	$search="select slip_id,id from sales_refund where slip_id='".$daily_id."' and type='".$ticket_type."' limit 1";
	$searchRS=$db->query($search);
	$searchNM=$searchRS->num_rows;
	if($searchNM>0){
		$searchRow=$searchRS->fetch_assoc();
		$entry_id=$searchRow['id'];
		
//		$update="update sales_refund set reg_amount_refund='".$reg_amount_refund."',reg_ticket_refund='".$reg_ticket_refund."',disc_amount_refund='".$disc_amount_refund."',disc_ticket_refund='".$disc_ticket_refund."',reason_refund=\"".$refund_reason."\" where id='".$entry_id."'";
		$update="update sales_refund set reg_amount_refund='".$reg_amount_refund."',reg_ticket_refund='".$reg_ticket_refund."',disc_amount_refund='".$disc_amount_refund."',disc_ticket_refund='".$disc_ticket_refund."' where id='".$entry_id."'";

		$updateRS=$db->query($update);
		
		
		
	}
	else {
		$update="insert into sales_refund(slip_id,type,reg_amount_refund,reg_ticket_refund,disc_amount_refund,disc_ticket_refund) ";
		$update.=" values ";
		$update.="('".$daily_id."','".$ticket_type."','".$reg_amount_refund."','".$reg_ticket_refund."','".$disc_amount_refund."','".$disc_ticket_refund."')";	
		$updateRS=$db->query($update);
	}
}

if(isset($_POST['unsold_daily_id'])){
	$daily_id=$_POST['unsold_daily_id'];
	$ticket_type=$_POST['ticket_type'];
	
	$reg_ticket_unsold=$_POST['regular_ticket_unsold'];
	$reg_amount_unsold=$_POST['regular_amount_unsold'];

	$disc_ticket_unsold=$_POST['discounted_ticket_unsold'];
	$disc_amount_unsold=$_POST['discounted_amount_unsold'];

	
//	$refund_reason=$_POST['refund_reason'];
	
	$search="select slip_id,id from sales_unsold where slip_id='".$daily_id."' and type='".$ticket_type."' limit 1";
	
	$searchRS=$db->query($search);
	$searchNM=$searchRS->num_rows;
	if($searchNM>0){
		$searchRow=$searchRS->fetch_assoc();
		$entry_id=$searchRow['id'];
		
//		$update="update sales_refund set reg_amount_refund='".$reg_amount_refund."',reg_ticket_refund='".$reg_ticket_refund."',disc_amount_refund='".$disc_amount_refund."',disc_ticket_refund='".$disc_ticket_refund."',reason_refund=\"".$refund_reason."\" where id='".$entry_id."'";
		$update="update sales_unsold set reg_amount_unsold='".$reg_amount_unsold."',reg_ticket_unsold='".$reg_ticket_unsold."',disc_amount_unsold='".$disc_amount_unsold."',disc_ticket_unsold='".$disc_ticket_unsold."' where id='".$entry_id."'";
		
		$updateRS=$db->query($update);
		
		
		
	}
	else {
		$update="insert into sales_unsold(slip_id,type,reg_amount_unsold,reg_ticket_unsold,disc_amount_unsold,disc_ticket_unsold) ";
		$update.=" values ";
		$update.="('".$daily_id."','".$ticket_type."','".$reg_amount_unsold."','".$reg_ticket_unsold."','".$disc_amount_unsold."','".$disc_ticket_unsold."')";	
		$updateRS=$db->query($update);
	}
}


if(isset($_POST['fare_daily_id'])){
	$daily_id=$_POST['fare_daily_id'];

	$fare_adjustment=$_POST['fare_adjustment'];
	$time_over=$_POST['time_over'];
	$search="select summary_id,id from sales_slip where summary_id='".$daily_id."' limit 1";
	$searchRS=$db->query($search);
	$searchNM=$searchRS->num_rows;
	if($searchNM>0){
		$searchRow=$searchRS->fetch_assoc();
		$entry_id=$searchRow['id'];
		$update="update sales_slip set fare_adjustment='".$fare_adjustment."',time_over='".$time_over."' where id='".$entry_id."'";
		$updateRS=$db->query($update);
	}
	else {
		$update="insert into sales_slip(fare_adjustment,time_over,summary_id) values ('".$fare_adjustment."','".$time_over."','".$daily_id."')";
		$updateRS=$db->query($update);
	
	
	
	}

}

if(isset($_POST['ee_daily_id'])){
	$daily_id=$_POST['ee_daily_id'];

	$entry=$_POST['entry'];
	$exit=$_POST['exit'];
	$search="select slip_id,id from sales_ee where slip_id='".$daily_id."' limit 1";

	$searchRS=$db->query($search);
	$searchNM=$searchRS->num_rows;
	if($searchNM>0){
		$searchRow=$searchRS->fetch_assoc();
		$entry_id=$searchRow['id'];
		
		$update="update sales_ee set entry='".$entry."',exit='".$exit."' where id='".$entry_id."'";
		$updateRS=$db->query($update);
		
	}
	else {
		$update="insert into sales_ee(slip_id,ticket_entry,ticket_exit) values ('".$daily_id."','".$entry."','".$exit."')";
		$updateRS=$db->query($update);
	
	}

}



?>
<?php
require("monitoring menu.php");
?>
<link href="layout/landbank/logbook style.css" rel="stylesheet" type="text/css"  id='stylesheet' />

<br>
<br>

<form action='sales summary slip.php' method='post'>
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
$sql="select * from station2";
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
<br>
<br>
<?php
if(isset($_SESSION['month'])){
	$station=$_SESSION['station2'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];
	$year=$_SESSION['year'];
	
	$daily_date=$year."-".$month."-".$day;
	$daily_name=date("F d, Y",strtotime($daily_date));
	
	$sql="select * from station2 where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];
	
	$daily_id="";

	$id_sql="select * from sales_summary where date='".$daily_date."' and station='".$station."'";
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
	
	$sql="select * from station2 where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];
	
	$daily_id="";
	
	
	
	$id_sql="select * from sales_summary where date='".$daily_date."' and station='".$station."'";
	$id_rs=$db->query($id_sql);
	
	$id_nm=$id_rs->num_rows;
	
	if($id_nm>0){
		$id_row=$id_rs->fetch_assoc();
		$daily_id=$id_row['id'];
	}
	else {
		$update="insert into sales_summary(date,station) values ('".$daily_date."','".$station."')";
		$updateRS=$db->query($update);
		$daily_id=$db->insert_id;	
	}
	
	$_SESSION['station2']=$station;
	$_SESSION['month']=$_POST['month'];
	$_SESSION['day']=$_POST['day'];
	$_SESSION['year']=$_POST['year'];	
	
	
}
?>
<?php
if($daily_id==""){
}
else {
?>
<table width=100% style='border:1px solid gray' id='menu'>
<tr id='selectLogbook'>
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
<?php
}
?>
<br>
<table width=100% border='1px' style='border-collapse:collapse' class='logbookTable'>
<tr>
<th colspan=9>
Sales (based on SCS Data)
</th>
</tr>
<tr>
<th rowspan=2>Type</th>
<th colspan=2>Regular Sales</th>
<th colspan=2>Discounted Sales</th>
<th colspan=2>Refund</th>
<th colspan=2>Unsold</th>

</tr>
<tr>
<th>Qty.</th><th>Amount</th>
<th>Qty.</th><th>Amount</th>
<th>Qty.</th><th>Amount</th>
<th>Qty.</th><th>Amount</th>
</tr>
<tr>
<th>Single Journey</th>
<?php
$regular_sales="&nbsp;";
$discount_sales="&nbsp;";

$regular_amount="&nbsp;";
$discount_amount="&nbsp;";

$refund_sales="&nbsp;";
$refund_amount="&nbsp;";

$unsold_sales="&nbsp;";
$unsold_amount="&nbsp;";

//$refund_reason="&nbsp;";

$sql="select * from sales_entry where slip_id='".$daily_id."' and type='sj' limit 1";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	
	$regular_amount=$row['reg_amount'];
	$regular_sales=$row['reg_sold'];
	
	$discount_amount=$row['disc_amount'];
	$discount_sales=$row['disc_sold'];

}
$sql="select * from sales_refund where slip_id='".$daily_id."' and type='sj' limit 1";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	
	$refund_sales=$row['reg_ticket_refund']."/".$row['disc_ticket_refund'];
	$refund_amount=$row['reg_amount_refund']."/".$row['disc_amount_refund'];

//	$refund_reason=$row['reason_refund'];
	
}
$sql="select * from sales_unsold where slip_id='".$daily_id."' and type='sj' limit 1";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	
	$unsold_sales=$row['reg_ticket_unsold']."/".$row['disc_ticket_unsold'];
	$unsold_amount=$row['reg_amount_unsold']."/".$row['disc_amount_unsold'];

//	$refund_reason=$row['reason_refund'];
	
}

?>
	<td align=center><?php echo $regular_sales; ?></td>
	<td align=center><?php echo $regular_amount; ?></td>
	<td align=center><?php echo $discount_sales; ?></td>
	<td align=center><?php echo $discount_amount; ?></td>

	<td align=center><?php echo $refund_sales; ?></td>
	<td align=center><?php echo $refund_amount; ?></td>

	<td align=center><?php echo $unsold_sales; ?></td>
	<td align=center><?php echo $unsold_amount; ?></td>

</tr>
<tr>
<th>Stored Value</th>
<?php
$regular_sales="&nbsp;";
$discount_sales="&nbsp;";

$regular_amount="&nbsp;";
$discount_amount="&nbsp;";

$refund_sales="&nbsp;";
$refund_amount="&nbsp;";

$unsold_sales="&nbsp;";
$unsold_amount="&nbsp;";

//$refund_reason="&nbsp;";

$sql="select * from sales_entry where slip_id='".$daily_id."' and type='sv' limit 1";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	$regular_amount=$row['reg_amount'];
	$regular_sales=$row['reg_sold'];
	
	$discount_amount=$row['disc_amount'];
	$discount_sales=$row['disc_sold'];	

}

$sql="select * from sales_refund where slip_id='".$daily_id."' and type='sv' limit 1";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	
	$refund_sales=$row['reg_ticket_refund']."/".$row['disc_ticket_refund'];
	$refund_amount=$row['reg_amount_refund']."/".$row['disc_amount_refund'];

//	$refund_reason=$row['reason_refund'];
	
}
$sql="select * from sales_unsold where slip_id='".$daily_id."' and type='sv' limit 1";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	
	$unsold_sales=$row['reg_ticket_unsold']."/".$row['disc_ticket_unsold'];
	$unsold_amount=$row['reg_amount_unsold']."/".$row['disc_amount_unsold'];

}

?>
	<td align=center><?php echo $regular_sales; ?></td>
	<td align=center><?php echo $regular_amount; ?></td>
	<td align=center><?php echo $discount_sales; ?></td>
	<td align=center><?php echo $discount_amount; ?></td>

	<td align=center><?php echo $refund_sales; ?></td>
	<td align=center><?php echo $refund_amount; ?></td>
	<td align=center><?php echo $unsold_sales; ?></td>
	<td align=center><?php echo $unsold_amount; ?></td>

</tr>
</table>
<br>
<table width=80%>
<tr>
<td width=25% valign=top>
<form action='sales summary slip.php' method='post'>
<table width=100% style='border:1px solid gray' class='logbookTable'>
<tr>
<th colspan=2>Enter Sales</th>
</tr>
<tr>
<th>Ticket Type</th>
<td>
<select name='ticket_type'>
<option value='sj'>Single Journey</option>
<option value='sv'>Stored Value</option>
</select>
</td>
</tr>
<tr>
<th colspan=2>Regular Sales</th>
</tr>
<tr>
<th>No. of Tickets Sold</th>
<td><input type=text name='regular_sold' /></td>
</tr>
<tr>
<th>Sales Amount</th>
<td><input type=text name='regular_amount' /></td>
</tr>
<tr>
<th colspan=2>Discounted Sales</th>
</tr>
<tr>
<th>No. of Tickets Sold</th>
<td><input type=text name='discounted_sold' /></td>
</tr>
<tr>
<th>Sales Amount</th>
<td><input type=text name='discounted_amount' /></td>
</tr>
<?php
	if($daily_id==""){
	}
	else {
?>	
	<tr>
		<td colspan=2 align=center>
		<input type=submit value='Submit' />
		<input type=hidden name='sales_daily_id' value='<?php echo $daily_id; ?>' />
		</td>
	</tr>
<?php	
	}
?>	
</table>
</form>
</td>
<td width=35% valign=top>
<form action='sales summary slip.php' method='post'>
<table width=100% style='border: 1px solid gray' class='logbookTable'>
	<tr>
		<th colspan=3>Enter Refund</th>
	</tr>
	<tr>
		<th>Ticket Type</th>
		<td colspan=2>
		<select name='ticket_type'>
		<option value='sj'>Single Journey</option>
		<option value='sv'>Stored Value</option>
		</select>		
		</td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td><b>Regular</b></td>
		<td><b>Discounted</b></td>
	
	</tr>
	<tr>
		<th>No. of Tickets Refunded</th>
		<td><input type=text name='regular_ticket_refunded' /></td>
		<td><input type=text name='discounted_ticket_refunded' /></td>
	</tr>
	<tr>
		<th>Refund Amount</th>
		<td><input type=text name='regular_amount_refunded' /></td>
		<td><input type=text name='discounted_amount_refunded' /></td>

	</tr>
<?php
	if($daily_id==""){
	}
	else {
?>	
	<tr>
		<td colspan=3 align=center>
		<input type=submit value='Submit' />
		<input type=hidden name='refund_daily_id' value='<?php echo $daily_id; ?>' />
		</td>
	</tr>
<?php	
	}
?>	
</table>
</form>
</td>
<td width=40% valign=top>

<form action='sales summary slip.php' method='post'>
<table style='border: 1px solid gray' class='logbookTable'>
	<tr>
		<th colspan=3>Enter Unsold</th>
	</tr>
	<tr>
		<th>Ticket Type</th>
		<td colspan=2>
		<select name='ticket_type'>
		<option value='sj'>Single Journey</option>
		<option value='sv'>Stored Value</option>
		</select>		
		</td>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<td><b>Regular</b></td>
		<td><b>Discounted</b></td>
	
	</tr>
	<tr>
		<th>No. of Tickets Unsold</th>
		<td><input type=text name='regular_ticket_unsold' /></td>
		<td><input type=text name='discounted_ticket_unsold' /></td>
	</tr>
	<tr>
		<th>Unsold Amount</th>
		<td><input type=text name='regular_amount_unsold' /></td>
		<td><input type=text name='discounted_amount_unsold' /></td>

	</tr>
<?php
	if($daily_id==""){
	}
	else {
?>	
	<tr>
		<td colspan=3 align=center>
		<input type=submit value='Submit' />
		<input type=hidden name='unsold_daily_id' value='<?php echo $daily_id; ?>' />
		</td>
	</tr>
<?php	
	}
?>	
</table>
</form>


</td>
</tr>
</table>
<br>
<table width=100%>
<tr>
<td valign=top>
<table width=100% border=1px style='border-collapse:collapse' class='logbookTable'>
<tr>
<th colspan=2>Data based on SCS Data</th>
</tr>
<tr>
<th>Fare Adjustment</th><th>Time Over</th>
</tr>
<?php
$fare_adjustment="&nbsp;";
$time_over="&nbsp;";

$sql="select * from sales_slip where summary_id='".$daily_id."' limit 1";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	$fare_adjustment=$row['fare_adjustment'];
	$time_over=$row['time_over'];

}
?>
<tr>
	<td align=center><?php echo $fare_adjustment; ?></td>
	<td align=center><?php echo $time_over; ?></td>
</tr>
</table>
</td>
<td  valign=top>
<table width=100% border=1px style='border-collapse:collapse' class='logbookTable'>
<tr>
<th colspan=2>Entry/Exit based on SCS</th>
</tr>
<tr>
<th>Entry</th><th>Exit</th>
</tr>
<?php
$entry="&nbsp;";
$exit="&nbsp;";

$sql="select * from sales_ee where slip_id='".$daily_id."' limit 1";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$entry=$row['ticket_entry'];
	$exit=$row['ticket_exit'];
}
?>
<tr>
	<td align=center><?php echo $entry; ?></td>
	<td align=center><?php echo $exit; ?></td>
</tr>
</table>
</td>
</tr>
</table>
<br>
<table width=80%>
<tr>
<td width=20%>
<form action='sales summary slip.php' method='post'>
<table style='border:1px solid gray' class='logbookTable'>
<tr>
	<th colspan=2>Enter Data</th>
</tr>
<tr>
	<th>Fare Adjustment</th>
	<td><input type=text name='fare_adjustment' /></td>
</tr>	
<tr>
	<th>Time Over</th>
	<td><input type=text name='time_over' /></td>
</tr>	
<?php
if($daily_id==""){
}
else {
?>
<tr>
	<td colspan=2 align=center>
		<input type=submit value='Submit' />
		<input type=hidden name='fare_daily_id' value='<?php echo $daily_id; ?>' />
	</td>
</tr>	
<?php
}
?>
</table>
</form>
</td>
<td width=80%>
<form action='sales summary slip.php' method='post'>
<table style='border:1px solid gray' class='logbookTable'>
<tr>
	<th colspan=2>Enter Entry/Exit (from SCS)</th>
</tr>
<tr>
	<th>Entry</th>
	<td><input type=text name='entry' /></td>
</tr>
<tr>
	<th>Exit</th>
	<td><input type=text name='exit' /></td>
</tr>
<?php
if($daily_id==""){
}
else {
?>
<tr>
	<td colspan=2 align=center>
		<input type=submit value='Submit' />
		<input type=hidden name='ee_daily_id' value='<?php echo $daily_id; ?>' />
	</td>
</tr>	
<?php
}
?>
</table>
</form>
</td>
</tr>
</table>
<br>
<a href='#' onclick="window.open('generate_dr1.php')">Generate DR1</a>
<br>
<a href='#' onclick="window.open('generate_dr8.php')">Generate DR8 (Entry/Exit)</a>
