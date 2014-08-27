<?php
if(isset($_POST['station'])){
	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];
	
	$sales_date=$year."-".$month."-".$day;
	$station=$_POST['station'];
	
	$db=new mysqli("localhost","root","","station");
	
	$ticket[0]="sjt";
	$ticket[1]="sjd";
	$ticket[2]="svt";
	$ticket[3]="svd";
	$type=$_POST['type'];
	for($i=0;$i<count($ticket);$i++){
		if($_POST[$ticket[$i]."_sales_1"]==""){
		}
		else {
			$sql="select * from actual_sales where date='".$sales_date."' and station='".$station."' and type='".$ticket[$i]."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm>0){
				$row=$rs->fetch_assoc();
				$sales_id=$row['id'];
				
				$update="update actual_sales set quantity='".$_POST[$ticket[$i].'_sales_1']."',amount='".$_POST[$ticket[$i]."_sales_2"]."' where id='".$sales_id."'";
				$urs=$db->query($update);
			}
			else {
				$update="insert into actual_sales(date,station,type,quantity,amount)";
				$update.=" values ('".$sales_date."','".$station."','".$ticket[$i]."','".$_POST[$ticket[$i].'_sales_1']."','".$_POST[$ticket[$i].'_sales_2']."')";
				$urs=$db->query($update);
			}
			
		}
		if($_POST["tim_sales_1"]==""){
		}
		else {
			$sql="select * from actual_sales where date='".$sales_date."' and station='".$station."' and type='TIM'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;	

			if($nm>0){
				$row=$rs->fetch_assoc();
				$sales_id=$row['id'];
				
				$update="update actual_sales set quantity='".$_POST['tim_sales_1']."',amount='".$_POST["tim_sales_2"]."' where id='".$sales_id."'";
				$urs=$db->query($update);
			}
			else {
				$update="insert into actual_sales(date,station,type,quantity,amount)";
				$update.=" values ('".$sales_date."','".$station."','TIM','".$_POST['tim_sales_1']."','".$_POST['tim_sales_2']."')";
				$urs=$db->query($update);
			}
			
		}

		if($_POST["fare_sales_1"]==""){
		}
		else {
			$sql="select * from actual_sales where date='".$sales_date."' and station='".$station."' and type='FA'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;	

			if($nm>0){
				$row=$rs->fetch_assoc();
				$sales_id=$row['id'];
				
				$update="update actual_sales set quantity='".$_POST['fare_sales_1']."',amount='".$_POST["fare_sales_2"]."' where id='".$sales_id."'";
				$urs=$db->query($update);
			}
			else {
				$update="insert into actual_sales(date,station,type,quantity,amount)";
				$update.=" values ('".$sales_date."','".$station."','FA','".$_POST['fare_sales_1']."','".$_POST['fare_sales_2']."')";
				$urs=$db->query($update);
			}
			
		}
		
		if($_POST[$ticket[$i]."_refund_1"]==""){
			
		}
		else {
			$sql="select * from refund where date='".$sales_date."' and station='".$station."' and type='".$ticket[$i]."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			$refund_id="";
			if($nm>0){
				$row=$rs->fetch_assoc();
				$sales_id=$row['id'];
				
				$update="update refund set quantity='".$_POST[$ticket[$i].'_refund_1']."',amount='".$_POST[$ticket[$i]."_refund_2"]."' where id='".$sales_id."'";
				$urs=$db->query($update);
				$refund_id=$sales_id;
			}
			else {
				$update="insert into refund(date,station,type,quantity,amount)";
				$update.=" values ('".$sales_date."','".$station."','".$ticket[$i]."','".$_POST[$ticket[$i].'_refund_1']."','".$_POST[$ticket[$i].'_refund_2']."')";
				$urs=$db->query($update);
				$refund_id=$db->insert_id;
			}		
			
			for($n=1;$n<=10;$n++){
				if(($_POST[$ticket[$i]."_error_".$n]=="")||($_POST[$ticket[$i]."_error_".$n]==0)){
				}
				else {
					$search="select * from refund_reason where refund_id='".$refund_id."' and error_id='".$n."'";		
					$seaRS=$db->query($search);
					$seaNM=$seaRS->num_rows;
					if($seaNM>0){
						$update="update refund_reason set quantity='".$_POST[$ticket[$i]."_error_".$n]."' where refund_id='".$refund_id."' and error_id='".$n."'";
						$urs=$db->query($update);	
					}
					else {
						$update="insert into refund_reason(quantity,refund_id,error_id) values ('".$_POST[$ticket[$i]."_error_".$n]."','".$refund_id."','".$n."')";
						$urs=$db->query($update);	
						
					}
				}
			}
		}


		if($_POST[$ticket[$i]."_unsold_1"]==""){
			
		}
		else {
			$sql="select * from unsold where date='".$sales_date."' and station='".$station."' and type='".$ticket[$i]."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			$refund_id="";
			if($nm>0){
				$row=$rs->fetch_assoc();
				$sales_id=$row['id'];
				
				$update="update unsold set quantity='".$_POST[$ticket[$i].'_refund_1']."',amount='".$_POST[$ticket[$i]."_refund_2"]."' where id='".$sales_id."'";
				$urs=$db->query($update);
				$refund_id=$sales_id;
			}
			else {
				$update="insert into unsold(date,station,type,quantity,amount)";
				$update.=" values ('".$sales_date."','".$station."','".$ticket[$i]."','".$_POST[$ticket[$i].'_refund_1']."','".$_POST[$ticket[$i].'_refund_2']."')";
				$urs=$db->query($update);
				$refund_id=$db->insert_id;
			}		
			
		}
		
	}
	
	
	
}	
?>
<script language='javascript'>
function enableRefund(refundVal,type){
	if(refundVal>0){
		for(i=1;i<=10;i++){
			document.getElementById(type+"_error_"+i).disabled=false;
		
		}
	}
	else {
		for(i=1;i<=10;i++){
			document.getElementById(type+"_error_"+i).disabled=true;
		}
	}
}
</script>
<a href='monitoring menu.php'>Go Back to Monitoring Menu</a>
<form action='sales and refund.php' method='post'>
<table>
<tr>

<td>Date:</td><td colspan=4>

<select name='month'>
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
</td><tr>

<tr>
<td colspan=5>Station 
<select name='station'>
<?php
$db=new mysqli("localhost","root","","station");
$sql="select * from station order by id*1";
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
</td>
</tr>
<tr>
<td>&nbsp;</td>
<th colspan=2>Sales</th>
<th colspan=2>Refund</th>
<th colspan=2>Unsold</th>

</tr>

<tr>
<td>
SJT
</td>
<td>Qty. <input type=text size=3 name='sjt_sales_1'/></td>
<td>Amount <input type=text name='sjt_sales_2'  /></td>
<td>Qty. <input type=text  size=3 name='sjt_refund_1'  onkeyup='enableRefund(this.value,"sjt")'  /></td>
<td>Amount <input type=text name='sjt_refund_2' /></td>
<td>Qty. <input type=text  size=3 name='sjt_unsold_1'   /></td>
<td>Amount <input type=text name='sjt_unsold_2' /></td>

</tr>
<tr>
<td>
SJD
</td>
<td>Qty. <input type=text size=3 name='sjd_sales_1' /></td>
<td>Amount <input type=text name='sjd_sales_2'  /></td>
<td>Qty. <input type=text size=3 name='sjd_refund_1' onkeyup='enableRefund(this.value,"sjd")'  /></td>
<td>Amount <input type=text name='sjd_refund_2' /></td>
<td>Qty. <input type=text  size=3 name='sjd_unsold_1'   /></td>
<td>Amount <input type=text name='sjd_unsold_2' /></td>

</tr>
<tr>
<td>
SVT
</td>
<td>Qty. <input type=text size=3 name='svt_sales_1' /></td>
<td>Amount <input type=text name='svt_sales_2'  /></td>
<td>Qty. <input type=text size=3 name='svt_refund_1' onkeyup='enableRefund(this.value,"svt")'  /></td>
<td>Amount <input type=text name='svt_refund_2' /></td>
<td>Qty. <input type=text  size=3 name='svt_unsold_1'   /></td>
<td>Amount <input type=text name='svt_unsold_2' /></td>

</tr>
<tr>
<td>
SVD
</td>
<td>Qty. <input type=text  size=3 name='svd_sales_1' /></td>
<td>Amount <input type=text name='svd_sales_2'  /></td>
<td>Qty. <input type=text  size=3 name='svd_refund_1' onkeyup='enableRefund(this.value,"svd")' /></td>
<td>Amount <input type=text name='svd_refund_2' /></td>
<td>Qty. <input type=text  size=3 name='svd_unsold_1'   /></td>
<td>Amount <input type=text name='svd_unsold_2' /></td>

</tr>
<tr>
<td>TIM</td>
<td>Qty. <input type=text  size=3 name='tim_sales_1' /></td>
<td>Amount <input type=text name='tim_sales_2'  /></td>
<td colspan=2>&nbsp;</td>
</tr>
<tr>
<td>Fare<br> Adjustment</td>
<td>Qty. <input type=text  size=3 name='fare_sales_1' /></td>
<td>Amount <input type=text name='fare_sales_2'  /></td>
<td colspan=2>&nbsp;</td>
</tr>


</table>
<br>
<table>
<tr>
<th colspan=10>Reasons for Refund:</th>
</tr>

<tr>
<td>
Ticket
</td>
<td>JC0003</td>
<td>JC0004</td>
<td>JC0005</td>
<td>JC0006</td>
<td>JC0007</td>
<td>Blk</td>
<td>NRED</td>
<td>AS</td>
<td>Unreg.</td>
<td>Others</td>
</tr>
<tr>
<td>
SJT</td>
<td><input name='sjt_error_1' id='sjt_error_1' type=text size=3 disabled /></td>
<td><input name='sjt_error_2' id='sjt_error_2' type=text size=3 disabled  /></td>
<td><input name='sjt_error_3' id='sjt_error_3' type=text size=3 disabled  /></td>
<td><input name='sjt_error_4' id='sjt_error_4' type=text size=3 disabled  /></td>
<td><input name='sjt_error_5' id='sjt_error_5' type=text size=3 disabled  /></td>
<td><input name='sjt_error_6' id='sjt_error_6' type=text size=3 disabled  /></td>
<td><input name='sjt_error_7' id='sjt_error_7' type=text size=3 disabled  /></td>
<td><input name='sjt_error_8' id='sjt_error_8' type=text size=3 disabled  /></td>
<td><input name='sjt_error_9' id='sjt_error_9' type=text size=3 disabled  /></td>
<td><input name='sjt_error_10' id='sjt_error_10' type=text size=3 disabled  /></td>
</tr>
<tr>
<td>
SJD</td>
<td><input name='sjd_error_1' id='sjd_error_1' type=text size=3 disabled  /></td>
<td><input name='sjd_error_2' id='sjd_error_2' type=text size=3 disabled  /></td>
<td><input name='sjd_error_3' id='sjd_error_3' type=text size=3 disabled  /></td>
<td><input name='sjd_error_4' id='sjd_error_4' type=text size=3 disabled  /></td>
<td><input name='sjd_error_5' id='sjd_error_5' type=text size=3 disabled  /></td>
<td><input name='sjd_error_6' id='sjd_error_6' type=text size=3 disabled  /></td>
<td><input name='sjd_error_7' id='sjd_error_7' type=text size=3 disabled  /></td>
<td><input name='sjd_error_8' id='sjd_error_8' type=text size=3 disabled  /></td>
<td><input name='sjd_error_9' id='sjd_error_9' type=text size=3 disabled  /></td>
<td><input name='sjd_error_10' id='sjd_error_10' type=text size=3 disabled  /></td>
</tr>
<tr>
<td>
SVT</td>
<td><input name='svt_error_1' id='svt_error_1' type=text size=3 disabled  /></td>
<td><input name='svt_error_2' id='svt_error_2' type=text size=3 disabled  /></td>
<td><input name='svt_error_3' id='svt_error_3' type=text size=3  disabled /></td>
<td><input name='svt_error_4' id='svt_error_4' type=text size=3 disabled  /></td>
<td><input name='svt_error_5' id='svt_error_5' type=text size=3 disabled  /></td>
<td><input name='svt_error_6' id='svt_error_6' type=text size=3 disabled  /></td>
<td><input name='svt_error_7' id='svt_error_7' type=text size=3 disabled  /></td>
<td><input name='svt_error_8' id='svt_error_8' type=text size=3  disabled /></td>
<td><input name='svt_error_9' id='svt_error_9' type=text size=3  disabled /></td>
<td><input name='svt_error_10' id='svt_error_10' type=text size=3  disabled /></td>
</tr>
<tr>
<td>
SVD</td>
<td><input name='svd_error_1' id='svd_error_1' type=text size=3 disabled  /></td>
<td><input name='svd_error_2' id='svd_error_2' type=text size=3 disabled  /></td>
<td><input name='svd_error_3' id='svd_error_3' type=text size=3 disabled  /></td>
<td><input name='svd_error_4' id='svd_error_4' type=text size=3 disabled  /></td>
<td><input name='svd_error_5' id='svd_error_5' type=text size=3 disabled  /></td>
<td><input name='svd_error_6' id='svd_error_6' type=text size=3 disabled  /></td>
<td><input name='svd_error_7' id='svd_error_7' type=text size=3  disabled /></td>
<td><input name='svd_error_8' id='svd_error_8' type=text size=3  disabled /></td>
<td><input name='svd_error_9' id='svd_error_9' type=text size=3  disabled /></td>
<td><input name='svd_error_10' id='svd_error_10' type=text size=3 disabled  /></td>
</tr>
<tr>
<td align=center colspan=10><input type=submit value='Submit' /></td>

</tr>


</table>
</form>







