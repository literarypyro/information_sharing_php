<?php
$db=new mysqli("localhost","root","","station");

?>

<?php
require("monitoring menu.php"); 
?>
<link href="layout/landbank/logbook style.css" rel="stylesheet" type="text/css"  id='stylesheet' />
<br>
<br>

<form action='ticket_seller_report.php' method=post>
<table style='border: 1px solid gray' class='logbookTable'>
<tr>
<th>Ticket Seller</th>
<td>

<select name='ticket_seller'>
<?php

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
</tr>


<tr><th colspan=2>Date Range</th></tr>
<tr>
<th>From</th>

<td>


<select name='from_month'>
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
<select name='from_day'>
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
<select name='from_year'>
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
<td>

</tr>

<tr>
<th>To</th>
<td>
<select name='to_month'>
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
<select name='to_day'>
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
<select name='to_year'>
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
<th colspan=2><input type='submit' value='Submit' /></th>
</tr>
</table>
</form>
<br>
<br>
<?php if(isset($_POST['ticket_seller'])){
?>

<table border=1 style='border-collapse:collapse;' width=100% class='logbookTable'>
<tr>
<th rowspan=2>Name of Ticket Seller</th>
<th rowspan=2>Date</th>
<th rowspan=2>Station</th>
<th colspan=4>Number of Ticket Sold</th>
<th rowspan=2>Number of Human Errors</th>
</tr>
<tr>
<th>SJ Ticket</th>
<th>Discounted SJ Ticket</th>
<th>SV Ticket</th>
<th>Discounted SV Ticket</th>
</tr>
<?php

$from_month=$_POST['from_month'];
$from_day=$_POST['from_day'];
$from_year=$_POST['from_year'];

$from_date=date("Y-m-d",strtotime($from_year."-".$from_month."-".$from_day));

$to_month=$_POST['to_month'];
$to_day=$_POST['to_day'];
$to_year=$_POST['to_year'];

$to_date=date("Y-m-d",strtotime($to_year."-".$to_month."-".$to_day));

$ticket_seller=$_POST['ticket_seller'];

$ticket_seller_sql="select * from ticket_seller where id='".$ticket_seller."' limit 1";
$ticket_seller_rs=$db->query($ticket_seller_sql);
	
$ticket_seller_row=$ticket_seller_rs->fetch_assoc();

$last_name=$ticket_seller_row['last_name'];
$first_name=$ticket_seller_row['first_name'];

$sql="select *,dar.id as dar_id from dar_daily inner join dar on dar_daily.id=dar.daily_id where date between '".$from_date." 00:00:00' and '".$to_date." 23:59:59' and ticket_seller='".$ticket_seller."' and sked_type='reg' order by date "; 
$rs=$db->query($sql);

$nm=$rs->num_rows;

$sjt_total=0;
$sjd_total=0;
$svt_total=0;
$svd_total=0;

$te_total=0;

for($i=0;$i<$nm;$i++){
	$sjt="&nbsp;";
	$sjd="&nbsp;";
	$svt="&nbsp;";
	$svd="&nbsp;";
	
	$row=$rs->fetch_assoc();
	
	$sold_sql="select * from ticket_sold where dar_id='".$row['dar_id']."' limit 1";
	$sold_rs=$db->query($sold_sql);
	
	$sold_nm=$sold_rs->num_rows;
	
	if($sold_nm>0){
		$sold_row=$sold_rs->fetch_assoc();
		
		$sjt=$sold_row['sjt'];
		$sjd=$sold_row['sjd'];
		$svt=$sold_row['svt'];
		$svd=$sold_row['svd'];

		$sjt_total+=$sjt*1;
		$sjd_total+=$sjd*1;
		$svt_total+=$svt*1;
		$svd_total+=$svd*1;
	}
	
	
	
	
	$ticket_error_sql="select ticket_error_daily.id as ticket_daily_id,ticket_error.id as ticket_error_id from ticket_error_daily inner join ticket_error on ticket_error_daily.id=ticket_error.ticket_daily_id where date='".$row['date']."' and station='".$row['station']."' and ticket_seller='".$ticket_seller."'";
	$ticket_error_rs=$db->query($ticket_error_sql);
	$ticket_error_nm=$ticket_error_rs->num_rows;
	$ticket_error_count=0;
	if($ticket_error_nm>0){
		$te_row=$ticket_error_rs->fetch_assoc();

		$ticket_sum_sql="select sum(quantity) as sum_quantity from teemr_error_entry where ticket_error_id='".$te_row['ticket_error_id']."' and ticket_daily_id='".$te_row['ticket_daily_id']."'";		
		$ticket_sum_rs=$db->query($ticket_sum_sql);
		$ticket_sum_nm=$ticket_sum_rs->num_rows;
		
		if($ticket_sum_nm>0){
			$ticket_sum_row=$ticket_sum_rs->fetch_assoc();
		
			$ticket_error_count=$ticket_sum_row['sum_quantity'];
	
			$te_total+=$ticket_error_count*1;
		
		}

		
	}
	



	
	
	

?>
<tr>
<?php if($i==0){
?>
<td rowspan=<?php echo $nm; ?>><?php echo $last_name.", ".$first_name; ?></td>
<?php
}
?>
<td align=center><?php echo date("m/d",strtotime($row['date'])); ?></td>
<td align=center><?php echo $row['station']; ?></td>
<td align=center><?php echo $sjt; ?></td>
<td align=center><?php echo $sjd; ?></td>
<td align=center><?php echo $svt; ?></td>
<td align=center><?php echo $svd; ?></td>
<td align=center><?php echo $ticket_error_count; ?></td>
</tr>
<?php
}
?>
<tr>
<th colspan=3>Total</th>
<td align=center><?php echo $sjt_total; ?></td>
<td align=center><?php echo $sjd_total; ?></td>
<td align=center><?php echo $svt_total; ?></td>
<td align=center><?php echo $svd_total; ?></td>
<td align=center><?php echo $te_total; ?></td>
</tr>



</table>
<br>
<a href='#' onclick="window.open('generate_ticket_seller_report.php?from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>&ticket_seller=<?php echo $ticket_seller; ?>')">Generate Ticket Seller Report</a>

<?php
}
?>
