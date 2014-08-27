<?php
session_start();
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$db=new mysqli("localhost","root","","station");
if(isset($_POST['dar_index'])){
	$element=$_POST['formElement'];

	
	if($element=="ticket_sold"){
	
		$search="select * from ticket_sold where dar_id='".$_POST['dar_index']."' limit 1";
		$searchRS=$db->query($search);
		
		$searchNM=$searchRS->num_rows;
		
		if($searchNM>0){
	
			$update="update ticket_sold set sjt='".$_POST['sjt']."',sjd='".$_POST['sjd']."',svt='".$_POST['svt']."',svd='".$_POST['svd']."' where dar_id='".$_POST['dar_index']."'";
			$updateRS=$db->query($update);	
		}
		else {
			$update="insert into ticket_sold(sjt,sjd,svt,svd,dar_id) values ('".$_POST['sjt']."','".$_POST['sjd']."','".$_POST['svt']."','".$_POST['svd']."','".$_POST['dar_index']."')";
			$updateRS=$db->query($update);	
		
		}
	
	}
	else if($element=="cash_discrepancy"){

		if($_POST['discrep_value']==""){
		}
		else {
			$overage=0;
			$shortage=0;
			
			if($_POST['discrep_type']=="overage"){
				$overage=$_POST['discrep_value'];
			
			}
			else if($_POST['discrep_type']=="shortage"){
				$shortage=$_POST['discrep_value'];
		
			}
			
			$search="select * from ticket_sold where dar_id='".$_POST['dar_index']."' limit 1";
			$searchRS=$db->query($search);
			
			$searchNM=$searchRS->num_rows;
			
			if($searchNM>0){
				$update="update discrepancy set shortage='".$shortage."',overage='".$overage."' where dar_id='".$_POST['dar_index']."'";
				$updateRS=$db->query($update);	
				
			}
			else {
				$update="insert into discrepancy(shortage,overage,dar_id) values ('".$shortage."','".$overage."','".$_POST['dar_index']."')";	
				$updateRS=$db->query($update);	
			}
		}
	}
	else if($element=="ticket_discrepancy"){
		$ticket[0]="sjt";
		$ticket[1]="sjd";
		$ticket[2]="svt";
		$ticket[3]="svd";

		$remove="delete from discrepancy_ticket where dar_id='".$_POST['dar_index']."'";
		$removeRS=$db->query($remove);
		
		for($i=0;$i<count($ticket);$i++){
			$overage=0;
			$shortage=0;
			
			
			if($_POST[$ticket[$i]]==""){
			}
			else {
				if($_POST[$ticket[$i]."_discrep_type"]=="overage"){
					$overage=$_POST[$ticket[$i]];
				
				}
				else if($_POST[$ticket[$i]."_discrep_type"]=="shortage"){
					$shortage=$_POST[$ticket[$i]];
			
				}
				
			
				$update="insert into discrepancy_ticket(overage,shortage,ticket_type,dar_id) values ('".$overage."','".$shortage."','".$ticket[$i]."','".$_POST['dar_index']."')";
				$updateRS=$db->query($update);
				
				
			
			}
			
	
		}
	
	}
	else {
		$update="update dar set ".$element."='".$_POST[$element]."' where id='".$_POST['dar_index']."'";
		$updateRS=$db->query($update);	

	}
	
	
	
	
	

}
?>
<?php
require("monitoring menu.php"); 
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
	self.location="daily_accomplishment.php";


}
function fillEdit(element,dar_id,reference_id){
	var elementHTML="<br>";
	elementHTML+="<form action='daily_accomplishment.php' method='post' >";
	elementHTML+="<table style='border:1px solid red'>";
	elementHTML+="<tr>";
	elementHTML+="<td>Reference ID</td>";
	elementHTML+="<td>"+reference_id+"</td>";
	
	elementHTML+="</tr>";		
	elementHTML+="<tr>";
	elementHTML+="<td>Enter "+element.toUpperCase()+"</td>";
	
	if(element=="ticket_seller"){
		
		elementHTML+="<td id='ticket_seller_fill' name='ticket_seller_fill'></td>";
		
	}
	else if(element=="cash_discrepancy"){
		elementHTML+="<td>"; 
		
		elementHTML+="<select id='discrep_type' name='discrep_type'>";
		elementHTML+="<option value='shortage'>Shortage</option>";
		elementHTML+="<option value='overage'>Overage</option>";
		elementHTML+="</select>";
		
		elementHTML+=" <input type=text name='discrep_value' id='discrep_value' />";
		
		elementHTML+="</td>";
	}
	else if((element=="ticket_discrepancy")||(element=="ticket_sold")){
		elementHTML+="<td>&nbsp;</td>";	
		
		
	}
	else {
		elementHTML+="<td><input type=text name='"+element+"' /></td>";
	}
	
	elementHTML+="</tr>";		

	if(element=="ticket_discrepancy"){
		elementHTML+="<tr>";
		elementHTML+="<td>SJT</td>";
		elementHTML+="<td>"; 
		
		elementHTML+="<select id='sjt_discrep_type' name='sjt_discrep_type'>";
		elementHTML+="<option value='shortage'>Shortage</option>";
		elementHTML+="<option value='overage'>Overage</option>";
		elementHTML+="</select>";
		
		elementHTML+=" <input type=text name='sjt' id='sjt' />";
		
		elementHTML+="</td>";		
		elementHTML+="</tr>";
		
		elementHTML+="<tr>";
		elementHTML+="<td>DSJT</td>";
		elementHTML+="<td>"; 
		
		elementHTML+="<select id='sjd_discrep_type' name='sjd_discrep_type'>";
		elementHTML+="<option value='shortage'>Shortage</option>";
		elementHTML+="<option value='overage'>Overage</option>";
		elementHTML+="</select>";
		
		elementHTML+=" <input type=text name='sjd' id='sjd' />";
		
		elementHTML+="</td>";	
	
		elementHTML+="</tr>";

		elementHTML+="<tr>";
		elementHTML+="<td>SVT</td>";
		elementHTML+="<td>"; 
		
		elementHTML+="<select id='svt_discrep_type' name='svt_discrep_type'>";
		elementHTML+="<option value='shortage'>Shortage</option>";
		elementHTML+="<option value='overage'>Overage</option>";
		elementHTML+="</select>";
		
		elementHTML+=" <input type=text name='svt' id='svt' />";
		
		elementHTML+="</td>";	
	
		elementHTML+="</tr>";

		elementHTML+="<tr>";
		elementHTML+="<td>DSVT</td>";
		elementHTML+="<td>"; 
		
		elementHTML+="<select id='svd_discrep_type' name='svd_discrep_type'>";
		elementHTML+="<option value='shortage'>Shortage</option>";
		elementHTML+="<option value='overage'>Overage</option>";
		elementHTML+="</select>";
		
		elementHTML+=" <input type=text name='svd' id='svd' />";
		
		elementHTML+="</td>";	
	
		elementHTML+="</tr>";
		
	}
	else if(element=="ticket_sold"){
		elementHTML+="<tr>";
		elementHTML+="<td>SJT</td>";
		elementHTML+="<td>"; 
		elementHTML+=" <input type=text name='sjt' id='sjt' />";		
		
		
		
		
		elementHTML+="</td>";		
		elementHTML+="</tr>";
		
		elementHTML+="<tr>";
		elementHTML+="<td>DSJT</td>";
		elementHTML+="<td>"; 
		elementHTML+=" <input type=text name='sjd' id='sjd' />";
		elementHTML+="</td>";	
		elementHTML+="</tr>";

		elementHTML+="<tr>";
		elementHTML+="<td>SVT</td>";
		elementHTML+="<td>"; 
		elementHTML+=" <input type=text name='svt' id='svt' />";
		elementHTML+="</td>";	
	
		elementHTML+="</tr>";

		elementHTML+="<tr>";
		elementHTML+="<td>DSVT</td>";
		elementHTML+="<td>"; 
		elementHTML+=" <input type=text name='svd' id='svd' />";
		
		elementHTML+="</td>";	
	
		elementHTML+="</tr>";
		
	}
	
	
	
	
	elementHTML+="<tr>";
	elementHTML+="<td colspan=2 align=center>";
	elementHTML+="<input type=hidden name='formElement' id='formElement' value='"+element+"' />";	
	elementHTML+="<input type='hidden' name='dar_index' value='"+dar_id+"' />";
	elementHTML+="<input type=submit value='Edit' />";
	elementHTML+="</td>"; 
	elementHTML+="</tr>";


	elementHTML+="</table>";
	elementHTML+="</form>";
	
	document.getElementById('fillDar').innerHTML=elementHTML;		
	
		if(element=="ticket_seller"){
		getTicketSeller();
	
	
	}
	
	
	
	
	
}
function getTicketSeller(){
	makeajax("processing.php?ticket_seller=Y","fillTicketSeller");	


}
function fillTicketSeller(ajaxHTML){
	var driverHTML="";
	if(ajaxHTML=="None available"){
	}
	else {

		driverHTML="<select name='ticket_seller' id='ticket_seller'>";

		var driverTerms=ajaxHTML.split("==>");
		var count=(driverTerms.length)*1-1;
		
		for(var n=0;n<count;n++){
			var parts=driverTerms[n].split(";");
			driverHTML+="<option value='"+parts[0]+"'>";
			driverHTML+=parts[1];
			driverHTML+="</option>";
		
		}
		driverHTML+="</select>";
		
	}
	document.getElementById('ticket_seller_fill').innerHTML=driverHTML;	

}
</script>
<br>
<br>
<form action='daily_accomplishment.php' method='post'>
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
<br>
<br>
<?php
if(isset($_SESSION['month'])){
	$daily_id="";
	
	$daily_id=$_SESSION['dar_id'];
	$station=$_SESSION['station'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];
	$year=$_SESSION['year'];

	$daily_date=$year."-".$month."-".$day;
	$daily_name=date("F d, Y",strtotime($daily_date));
	
	$sql="select * from station where id='".$station."' limit 1";
	$rs=$db->query($sql);	
	$row=$rs->fetch_assoc();
	
	$station_name=$row['station_name'];	
}


?>
<?php
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
	

	
	$id_sql="select * from dar_daily where date='".$daily_date."' and station='".$station."'";
	$id_rs=$db->query($id_sql);
	
	$id_nm=$id_rs->num_rows;
	
	if($id_nm>0){
		$id_row=$id_rs->fetch_assoc();
		$daily_id=$id_row['id'];
	}
	else {
		$update="insert into dar_daily(date,station) values ('".$daily_date."','".$station."')";
		$updateRS=$db->query($update);
		$daily_id=$db->insert_id;	
	}

		
	$_SESSION['dar_id']=$daily_id;
	$_SESSION['station']=$station;
	$_SESSION['month']=$_POST['month'];
	$_SESSION['day']=$_POST['day'];
	$_SESSION['year']=$_POST['year'];
	
	
	$shift1="";
	$shift2="";
	$shift3="";
	
	$shift_sql="select * from dar_supervisor where daily_id='".$daily_id."' limit 1";
	$shift_rs=$db->query($shift_sql);
	$shift_nm=$shift_rs->num_rows;
	if($shift_nm>0){
		$shift_row=$shift_rs->fetch_assoc();
		$shift1=$shift_row['s1'];
		$shift2=$shift_row['s2'];
		$shift3=$shift_row['s3'];
	}
}			

if($daily_id==""){
}
else {

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
<table width=100% border=1px style='border-collapse:collapse;'>
<tr>
<th colspan=6>Station Supervisors</th>
</tr>
<tr>
<th>S1</th>
<td><?php echo $shift1; ?></td>
<th>S2</th>
<td><?php echo $shift2; ?></td>
<th>S3</th>
<td><?php echo $shift3; ?></td>
</tr>

</table>
<?php
if($daily_id==""){
}
else {
?>
<a href='#' onclick="window.open('dar_ss_entry.php?daily_id=<?php echo $daily_id; ?>','_blank')">Edit</a>
<?php
}
?>
<br>
<br>
<table width=100% border=1px style='border-collapse:collapse;'>
<tr>
<th rowspan=2>Reference ID</th>

<th rowspan=2>Name</th>
<th rowspan=2>Shift</th>
<th rowspan=2>OT</th>
<th rowspan=2>A/D No.</th>
<th rowspan=2>Total Amount Remitted</th>
<th colspan=4>Number of Tickets Sold</th>

<th rowspan=2>Total Amount of Refund</th>

<th colspan=2>Discrepancy</th>

<th colspan=2>Ticket Discrepancies</th>



<th rowspan=2>Remarks</th>
<th rowspan=2>Number of Human Errors</th>

</tr>
<tr>
<th>SJT</th>
<th>SVT</th>
<th>DSJT</th>
<th>DSVT</th>

<th>Shortage</th>
<th>Overage</th>

<th>Shortage</th>
<th>Overage</th>

</tr>
<?php
if($daily_id==""){
}
else {
	$daily_sql="select * from dar where daily_id='".$daily_id."'";	
	
	$daily_rs=$db->query($daily_sql);
	
	$daily_nm=$daily_rs->num_rows;

	if($daily_nm>0){
		$reference_stamp=date("Ymd");

		for($i=0;$i<$daily_nm;$i++){
			$reference_id=$reference_stamp."_".$i;		
			
		
			$daily_row=$daily_rs->fetch_assoc();
	
			$ts_sql="select * from ticket_seller where id='".$daily_row['ticket_seller']."' limit 1";
			$ts_rs=$db->query($ts_sql);
				
			$ts_row=$ts_rs->fetch_assoc();

			$ticket_name=$ts_row['last_name'].", ".$ts_row['first_name'];	
			$shift=$daily_row['shift'];
			$ad_no=$daily_row['ad_no'];
			$remitted=$daily_row['remitted'];
			
			$refund=$daily_row['refund'];
			$remarks=$daily_row['remarks'];
			$sked=$daily_row['sked_type'];
			
			$svt="";
			$sjt="";	
			$svd="";
			$sjd="";
	

//			$ticket_error_sql="select count(1) as error_count from ticket_error_daily inner join ticket_error on ticket_error_daily.id=ticket_error.ticket_daily_id where date='".$daily_date."' and station='".$station."' and ticket_seller='".$daily_row['ticket_seller']."' and machine_no='".$ad_no."'";
			$ticket_error_sql="select count(1) as error_count from ticket_error_daily inner join ticket_error on ticket_error_daily.id=ticket_error.ticket_daily_id where date='".$daily_date."' and station='".$station."' and ticket_seller='".$daily_row['ticket_seller']."' and error_category='1'";

			$ticket_error_rs=$db->query($ticket_error_sql);
			$ticket_error_nm=$ticket_error_rs->num_rows;
			$ticket_error_count=0;
			
			if($ticket_error_nm>0){
				$te_row=$ticket_error_rs->fetch_assoc();
				
				$ticket_error_count=$te_row['error_count'];
			}	
	
	
	
			$sold_sql="select * from ticket_sold where dar_id='".$daily_row['id']."' limit 1";
			$sold_rs=$db->query($sold_sql);
			$sold_nm=$sold_rs->num_rows;
			
			if($sold_nm>0){
				$sold_row=$sold_rs->fetch_assoc();
				$svt=$sold_row['svt'];
				$sjt=$sold_row['sjt'];	
				$svd=$sold_row['svd'];
				$sjd=$sold_row['sjd'];
		
			}

			$shortage="";
			$overage="";
			
			$discrepancy_sql="select * from discrepancy where dar_id='".$daily_row['id']."' limit 1";
			$discrepancy_rs=$db->query($discrepancy_sql);
			$discrepancy_nm=$discrepancy_rs->num_rows;
			
			if($discrepancy_nm>0){
				$discrepancy_row=$discrepancy_rs->fetch_assoc();
				$shortage=$discrepancy_row['shortage'];
				$overage=$discrepancy_row['overage'];
			}
			
			$discrepancy_t_sql="select * from discrepancy_ticket where dar_id='".$daily_row['id']."'";
			$discrepancy_t_rs=$db->query($discrepancy_t_sql);
			$discrepancy_t_nm=$discrepancy_t_rs->num_rows;
			
			$shortage_label="";
			$overage_label="";
				
			$short_count=0;
			$over_count=0;
			
			if($discrepancy_t_nm>0){
				for($m=0;$m<$discrepancy_t_nm;$m++){
					$discrepancy_t_row=$discrepancy_t_rs->fetch_assoc();
					
					if($discrepancy_t_row['shortage']*1==0){
					}
					else {
						if($short_count==0){
						}
						else {
							$shortage_label.=", ";	

							
						
						}
						$shortage_label.=$discrepancy_t_row['shortage']." ".strtoupper($discrepancy_t_row['ticket_type']);

						
						$short_count++;	
					}
					if($discrepancy_t_row['overage']*1==0){
					}
					else {
						if($over_count==0){
						}
						else {
							$overage_label.=", ";	
						}

						$overage_label.=$discrepancy_t_row['overage']." ".strtoupper($discrepancy_t_row['ticket_type']);
						
						
						$over_count++;		
					}

					
				
				}
			}
			
			
			
			
			
?>			
		<tr>	
			<td><?php echo $reference_id; ?></td>
			<td><?php echo $ticket_name; ?> <a href='#' onclick="fillEdit('ticket_seller','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $shift; ?> <a href='#' onclick="fillEdit('shift','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php if($sked_type=="ot"){ echo "X"; } ?></td>
			<td align=center><?php echo $ad_no; ?> <a href='#' onclick="fillEdit('ad_no','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $remitted; ?> <a href='#' onclick="fillEdit('remitted','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $sjt; ?> <a href='#' onclick="fillEdit('ticket_sold','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $svt; ?> <a href='#' onclick="fillEdit('ticket_sold','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $sjd; ?> <a href='#' onclick="fillEdit('ticket_sold','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $svd; ?> <a href='#' onclick="fillEdit('ticket_sold','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $refund; ?> <a href='#' onclick="fillEdit('refund','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $shortage; ?> <a href='#' onclick="fillEdit('cash_discrepancy','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $overage; ?> <a href='#' onclick="fillEdit('cash_discrepancy','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $shortage_label; ?> <a href='#' onclick="fillEdit('ticket_discrepancy','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $overage_label; ?> <a href='#' onclick="fillEdit('ticket_discrepancy','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td><?php echo $remarks; ?> <a href='#' onclick="fillEdit('remarks','<?php echo $daily_row['id']; ?>','<?php echo $reference_id; ?>')">Edit</a></td>
			<td align=center><?php echo $ticket_error_count; ?></td>
			<td><a href='#' onclick="deleteRow('<?php echo $daily_row['id']; ?>','dar')" >X</a></td>
		</tr>
<?php			
		}

	}

}
?>
</table>
<br>
<div id='fillDar' name='fillDar'>



</div>
<br>
<a href='#' onclick="window.open('dar_entry.php?daily_id=<?php echo $daily_id; ?>','_blank')">Add New Entry</a>
<br>
<br>
<br>
<a href='#' onclick="window.open('generate_dr2c.php')">Generate DR2C</a>


<?php
}
?>

