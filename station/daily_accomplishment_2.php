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
<link href="layout/landbank/logbook style.css" rel="stylesheet" type="text/css"  id='stylesheet' />

<script language='javascript' src='ajax.js'></script>
<link href='js/jquery-ui.min.css' rel='stylesheet' />
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script language='javascript'>
/*
var edit_mode=true;
var row_record="";

function focusField(element){
	//$(element).closest('tr').find('td input:text').css('border','1px solid red');
	//$(element).css('border','2px solid black');

}
function addNewEntry(){
	
	var newRow="<tr>";
	//<td><input style='width:100%;border:1px solid red' name='field_1' id='field_1'  onkeyup='recordAndNext(event,this,1)' type='text' /></td><td><input style='width:100%;border:1px solid red'  name='field_2' id='field_2' onkeyup='recordAndNext(event,this,2)' type='text' /></td><td><input type='text' style='width:100%;border:1px solid red'  id='field_3' name='field_3' onkeyup='recordAndNext(event,this,3)' /></td></tr>";
	
	for(var i=1;i<=38;i++){
		newRow+="<td><input style='width:100%;border:1px solid red' name='field_"+i+"' id='field_"+i+"'  onkeyup='recordAndNext(event,this,"+i+")' type='text' /></td>";
	}

	newRow+="<td><a href='#' onclick='removeRow(this)' >X</a></td>";
	newRow+="</tr>";
	
	$('#entry').append(newRow);
		
	$('#action').val('add');
	$('#field_1').focus();
	$('#field_1').css('border','2px solid black');
	
	$('#add_link').css('visibility','hidden');
	$('#finish_button').css('visibility','visible');
}
function recordAndNext(event,element,sequence){
	alert("Activated");
	if(event.keyCode==13){
		if(sequence==35){
			row_record=$('#entry tr').index($(element).closest('tr'));
			record(element);
			addNewEntry();
		}
		else {
			$(element).css('border','1px solid red');
			
			$('#field_'+(sequence+1)).focus();
			
			$('#field_'+(sequence+1)).css('border','2px solid black');
			
		}
	}
	else if(event.keyCode==113){
		if(edit_mode==true){
			edit_mode=false;
			$(element).css('border','2px solid blue');
			
			
		}
		else {
			edit_mode=true;
			$(element).css('border','2px solid black');
			
		}
	}
	else if(event.keyCode==39){
		if(edit_mode==false){
			if(sequence==35){
			}
			else {
				$(element).css('border','1px solid red');
				
				$('#field_'+(sequence+1)).focus();
				
				$('#field_'+(sequence+1)).css('border','2px solid blue');
				
			}

		}
	}
	else if(event.keyCode==37){
		if(edit_mode==false){
			if(sequence==1){
			}
			else {
				$(element).css('border','1px solid red');
				
				$('#field_'+(sequence-1)).focus();
				
				$('#field_'+(sequence-1)).css('border','2px solid blue');
			}
			
		}
	}
	else if(event.keyCode==38){
		if(edit_mode==false){
		
			if($(element).closest('tr').is(':nth-child(5)')){
			}
			else {
				edit($(element).closest('tr').prev().find('td:nth-child('+sequence+')'));
			}
		}	
	}
	else if(event.keyCode==40){
		
		if(edit_mode==false){

			if($(element).closest('tr').is(':last-child')){
			}
			else {
				edit($(element).closest('tr').next().find('td:nth-child('+sequence+')'));
			}
		}	
	}
	else {
		var a=new Array();
		var b=new Array();

		a=[5,11,12,16,20,21];
		b=[6,7,8,13,17,22];
		c=[10,15,19,24];

		if($.inArray(sequence,a)>-1){
			var sum=0;
			for(var i=0;i<a.length;i++){
				sum+=$('#field_'+a[i]).val()*1;
			}
			$('#field_36').val(sum);
		}
		else if($.inArray(sequence,b)>-1){
			var sum=0;
			for(var i=0;i<b.length;i++){
				sum+=$('#field_'+b[i]).val()*1;
			}
			$('#field_37').val(sum);
		}
		else if($.inArray(sequence,c)>-1){
			var sum=0;
			for(var i=0;i<c.length;i++){
				sum+=$('#field_'+c[i]).val()*1;
			}
			$('#field_38').val(sum);
		}
	}
}

function record(element){
	postToAjax(element);
	unEditable();	
}	

function unEditable(){	
	for(var i=1;i<=38;i++){
		if($('#field_'+i).val()==""){
			$('#field_'+i).val("&nbsp;")
		}
			
		$('#field_'+i).closest("tr").find("td:nth-child("+i+")").click(function(){  esddit($(this)); });
		$('#field_'+i).closest("tr").find("td:nth-child("+i+")").html($('#field_'+i).val());
		
	}

//	var field_1=$('#field_1').val();
//	var field_2=$('#field_2').val();
//	var field_3=$('#field_3').val();
//	$('#field_3').closest("tr").html("<td onclick='edit(this)'>"+field_1+"</td><td onclick='edit(this)'>"+field_2+"</td><td onclick='edit(this)'>"+field_3+"</td>");
}
function edit(element){
	alert("Activated");
	$('#action').val('edit');	
	row_record=$('#entry tr').index($(element).closest('tr'));
//	alert($(element).closest('tr').attr('class'));
	record(element);	
	startEdit(element);
//	$("#field_1").closest("tr").remove();
}

function startEdit(element){
	var insertHTML="";
	var field_value="";
	
	for(var i=1;i<=38;i++){
		var tdHTML="";
		field_value=$(element).closest("tr").find("td:nth-child("+i+")").html();
		//insertHTML+="<td><input style='width:100%;border:1px solid red' name='field_"+i+"' id='field_"+i+"' value='"+field_value+"' onkeyup='recordAndNext(event,this,"+i+")' type='text' /></td>";
		tdHTML+="<input style='width:100%;border:1px solid red' name='field_"+i+"' id='field_"+i+"' value='"+field_value+"' onkeyup='recordAndNext(event,this,"+i+")' type='text' />";
	
		$(element).closest("tr").find("td:nth-child("+i+")").html(tdHTML);
	}
	$(element).find("input:text").focus();	
	$(element).find("input:text").css('border','2px solid black');
	
//	$(element).closest("tr").html(insertHTML);	
}

function finish(element){
	//record();
	$("#field_1").closest("tr").remove();
	$('#add_link').css('visibility','visible');
	$(element).css('visibility','hidden');
}

function postToAjax(element){
	var dar_data=$("#dar_form").serialize();
	if($('#action').val()=="edit"){
		//alert("This"+$(element).closest('tr').attr('class'));
		//dar_data += "&dar_id=" + encodeURIComponent($(element).closest('tr').attr('class'));
		dar_data += "&dar_id=" + $(element).closest('tr').attr('class');
		//dar_data += "&rec_id="+row_record;
		//$('#dar_id').val($(element).closest('tr').attr('class'));
	}
	else {
		dar_data += "&dar_id=" + $(element).closest('tr').attr('class');

		dar_data += "&rec_id="+row_record;
	}
	$.ajax({
	  type: "POST",
	  url: "processing_2.php",
	  data: dar_data
	}).done(function(data){
		if(data==""){
		}
		else {
//			$('tr:nth-child('+row_record+')').closest('#record_id').val(data);
//			alert(window.row_record);
			var jrec=JSON.parse(data);
			
			if(jrec.action=='add'){
				var rec_row=jrec.rec_id*1+1;
				$('#entry').find('tr:nth-child('+rec_row+')').attr('class',jrec.dar_id);
				//$('#entry').find('tr:nth-child('+(rec_row)+')').css('border','5px solid green');
			}
			
			alert(jrec.dar_id);
			alert(jrec.action);
		}
	});
}	
function removeRow(element){
	var check=confirm("This will delete the record. Continue?");
	if(check){
		$(element).closest("tr").remove();
	}
}	
*/
</script>
<style type='text/css'>
	
	
	
</style>

<script src='dar_dynamic.js'></script>
	
<script language='javascript'>
/*	
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
		elementHTML+="<input type=text name='svt' id='svt' />";
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
*/
</script>
<!--
-->
<br>
<br>
<form action='daily_accomplishment_2.php' method='post'>
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
<br>
<table width=100% border=1px style='border-collapse:collapse;' class='logbookTable'>
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
<form name='dar_form' id='dar_form' method='post' action='processing_2.php'>
<table width=100% border=1px style='border-collapse:collapse;' class='logbookTable' name='entry' id='entry'>
<tr>
<th rowspan=4>Reference ID</th>
<th rowspan=4>Name</th>
<th rowspan=4>Shift</th>
<th rowspan=4>A/D No.</th>
<th colspan=6>Single Journey</th>
<th colspan=5>Discounted Single Journey</th>
<th colspan=4>Stored Value</th>
<th colspan=5>Discounted Stored Value</th>

<th colspan=10>Discrepancy</th>
<!--
<th colspan=2>Ticket Discrepancies</th>
-->
<th rowspan=4>Remarks</th>

<th rowspan=4>Total Qty. of Ticket Sold</th>
<th rowspan=4>Total Sales</th>
<th rowspan=4>Total Amount of Refund</th>

</tr>
<tr>
<th colspan=4>Sales</th>
<th colspan=2>Refund</th>
<th colspan=3>Sales</th>
<th colspan=2>Refund</th>
<th colspan=2>Sales</th>
<th colspan=2>Refund</th>
<th colspan=3>Sales</th>
<th colspan=2>Refund</th>
<th colspan=5>Shortage</th>
<th colspan=5>Overage</th>
</tr>
<tr>
<th rowspan=2>Qty.</th>
<th rowspan=2>Amount</th>
<th rowspan=2>Fare Adjustment</th>
<th rowspan=2>Time Over</th>
<th rowspan=2>Qty</th>
<th rowspan=2>Amount</th>
<th colspan=2>Qty</th>
<th rowspan=2>Amount</th>
<th rowspan=2>Qty</th>
<th rowspan=2>Amount</th>
<th rowspan=2>Qty.</th>
<th rowspan=2>Amount</th>
<th rowspan=2>Qty</th>
<th rowspan=2>Amount</th>
<th colspan=2>Qty</th>
<th rowspan=2>Amount</th>
<th rowspan=2>Qty</th>
<th rowspan=2>Amount</th>
<th rowspan=2>Cash</th>
<th colspan=4>Ticket</th>

<th rowspan=2>Cash</th>
<th colspan=4>Ticket</th>
</tr>
<tr>
<th>SC</th>
<th>PWD</th>
<th>SC</th>
<th>PWD</th>
<th>SJT</th>
<th>SJD</th>
<th>SVT</th>
<th>SVD</th>
<th>SJT</th>
<th>SJD</th>
<th>SVT</th>
<th>SVD</th>


</tr>
<?php
if($daily_id==""){
}
else {
	$db2=new mysqli("localhost","root","","station_temp");

	$daily_sql="select * from dar where daily_id='".$daily_id."'";	
	
	$daily_rs=$db2->query($daily_sql);
	
	$daily_nm=$daily_rs->num_rows;

	if($daily_nm>0){
		$reference_stamp=date("Ymd");

		for($i=0;$i<$daily_nm;$i++){
			$reference_id=$reference_stamp."_".$i;		
		
			$daily_row=$daily_rs->fetch_assoc();
			$dar_id=$daily_row['id'];
			
			$ts_sql="select * from ticket_seller where id='".$daily_row['ticket_seller']."' limit 1";
			$ts_rs=$db->query($ts_sql);
				
			$ts_row=$ts_rs->fetch_assoc();

//			$ticket_name=$ts_row['last_name'].", ".$ts_row['first_name'];	
			$ticket_name=$daily_row['ticket_seller'];	

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

			$ticket_error_sql="select ticket_error_daily.id as ticket_daily_id,ticket_error.id as ticket_error_id from ticket_error_daily inner join ticket_error on ticket_error_daily.id=ticket_error.ticket_daily_id where date='".$daily_date."' and station='".$station."' and ticket_seller='".$daily_row['ticket_seller']."'";
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
				}
			}
/*
			$ticket_error_sql="select count(1) as error_count from ticket_error_daily inner join ticket_error on ticket_error_daily.id=ticket_error.ticket_daily_id where date='".$daily_date."' and station='".$station."' and ticket_seller='".$daily_row['ticket_seller']."' and error_category='1'";
			$ticket_error_rs=$db->query($ticket_error_sql);
			$ticket_error_nm=$ticket_error_rs->num_rows;
			$ticket_error_count=0;
			
			if($ticket_error_nm>0){
				$te_row=$ticket_error_rs->fetch_assoc();
				
				$ticket_error_count=$te_row['error_count'];
			}	
*/	
	
/*
			$sold_sql="select * from ticket_sold where dar_id='".$daily_row['id']."' limit 1";
			$sold_rs=$db2->query($sold_sql);
			$sold_nm=$sold_rs->num_rows;
			
			if($sold_nm>0){
				$sold_row=$sold_rs->fetch_assoc();
				$svt=$sold_row['svt'];
				$sjt=$sold_row['sjt'];	
				$svd=$sold_row['svd'];
				$sjd=$sold_row['sjd'];
		
			}
*/
			$sold_sql="select * from sales where dar_id='".$daily_row['id']."'";
			$sold_rs=$db2->query($sold_sql);
			$sold_nm=$sold_rs->num_rows;
			
			if($sold_nm>0){
				for($m=0;$m<$sold_nm;$m++){
					$sold_t_row=$sold_rs->fetch_assoc();
					
					$sold_quantity[$sold_t_row['ticket']]=$sold_t_row['quantity'];
					$sold_amount[$sold_t_row['ticket']]=$sold_t_row['amount'];
					
					$sold_fare[$sold_t_row['ticket']]=$sold_t_row['fare_adjustment'];
					$sold_timeover[$sold_t_row['ticket']]=$sold_t_row['timeover'];
				}
			}			

			$sold_sql="select * from discounted_sales where dar_id='".$daily_row['id']."'";
			$sold_rs=$db2->query($sold_sql);
			$sold_nm=$sold_rs->num_rows;
			if($sold_nm>0){
				for($m=0;$m<$sold_nm;$m++){
					$sold_d_row=$sold_rs->fetch_assoc();
					$disc_sc[$sold_d_row['ticket']]=$sold_d_row['sc'];
					$disc_pwd[$sold_d_row['ticket']]=$sold_d_row['pwd'];
			
			
				}
			}			
			
			$refund_sql="select * from refund where dar_id='".$daily_row['id']."'";
			$refund_rs=$db2->query($sold_sql);
			$refund_nm=$sold_rs->num_rows;
			
			if($refund_nm>0){
				for($m=0;$m<$refund_nm;$m++){
					$refund_t_row=$refund_rs->fetch_assoc();
					$refund_quantity[$refund_t_row['ticket']]=$refund_t_row['quantity'];
					$refund_amount[$refund_t_row['ticket']]=$refund_t_row['amount'];
				}
			}
			
			$shortage="";
			$overage="";
			
			$discrepancy_sql="select * from discrepancy where dar_id='".$daily_row['id']."' limit 1";
			$discrepancy_rs=$db2->query($discrepancy_sql);
			$discrepancy_nm=$discrepancy_rs->num_rows;
			
			if($discrepancy_nm>0){
				$discrepancy_row=$discrepancy_rs->fetch_assoc();
				$shortage=$discrepancy_row['shortage'];
				$overage=$discrepancy_row['overage'];
			}
			
			$discrepancy_t_sql="select * from discrepancy_ticket where dar_id='".$daily_row['id']."'";
			$discrepancy_t_rs=$db2->query($discrepancy_t_sql);
			$discrepancy_t_nm=$discrepancy_t_rs->num_rows;
			
			$shortage_label="";
			$overage_label="";
				
			$short_count=0;
			$over_count=0;
			
			if($discrepancy_t_nm>0){
				for($m=0;$m<$discrepancy_t_nm;$m++){
					$discrepancy_t_row=$discrepancy_t_rs->fetch_assoc();
					$shortage_t[$discrepancy_t_row['ticket_type']]=$discrepancy_t_row['shortage'];
					$overage_t[$discrepancy_t_row['ticket_type']]=$discrepancy_t_row['overage'];

				}
			}
			
?>			
		<tr class='dar_id_<?php echo $dar_id; ?>'>	
			<td onclick='processClick(this)'><?php echo $reference_id; ?></td>
			<td onclick='processClick(this)'><?php echo $ticket_name; ?></td>
			<td onclick='processClick(this)' align=center><?php echo $shift; ?></td>
			<td onclick='processClick(this)' align=center><?php echo $ad_no; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $sold_quantity['sjt']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $sold_amount['sjt']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $sold_fare['sjt']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $sold_timeover['sjt']; ?> </td>

			<td onclick='processClick(this)' align=center><?php echo $refund_quantity['sjt']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $refund_amount['sjt']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $disc_sc['sjd']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $disc_pwd['sjd']; ?> </td>

			<td onclick='processClick(this)' align=center><?php echo $sold_amount['sjd']; ?> </td>

			<td onclick='processClick(this)' align=center><?php echo $refund_quantity['sjd']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $refund_amount['sjd']; ?> </td>

			<td onclick='processClick(this)' align=center><?php echo $sold_quantity['svt']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $sold_amount['svt']; ?> </td>

			<td onclick='processClick(this)' align=center><?php echo $refund_quantity['svt']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $refund_amount['svt']; ?> </td>

			<td onclick='processClick(this)' align=center><?php echo $disc_sc['svd']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $disc_pwd['svd']; ?> </td>

			<td onclick='processClick(this)' align=center><?php echo $sold_amount['svd']; ?> </td>

			<td onclick='processClick(this)' align=center><?php echo $refund_quantity['svd']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $refund_amount['svd']; ?> </td>

			<td onclick='processClick(this)' align=center><?php echo $shortage; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $shortage_t['sjt']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $shortage_t['sjd']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $shortage_t['svt']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $shortage_t['svd']; ?> </td>

			<td onclick='processClick(this)' align=center><?php echo $overage; ?></td>
			<td onclick='processClick(this)' align=center><?php echo $overage_t['sjt']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $overage_t['sjd']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $overage_t['svt']; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $overage_t['svd']; ?> </td>

			<td onclick='processClick(this)'><?php echo $remarks; ?></td>
			<td>&nbsp;</td>	
			
			<td onclick='processClick(this)' align=center><?php echo $remitted; ?> </td>
			<td onclick='processClick(this)' align=center><?php echo $refund; ?></td>

			<td><a href='#' onclick="removeRow(this)" >X</a></td>
		</tr>
<?php			
		}
	}
}
?>
</table>
<input type='hidden' name='daily_id' id='daily_id' value='<?php echo $daily_id; ?>' />
<input type='hidden' name='action' id='action' />
<!--
<input type='hidden' name='dar_id' id='dar_id' />
-->
</form>
<br>
<div id='fillDar' name='fillDar'></div>
<br>
<!--
<a href='#' onclick="window.open('dar_entry.php?daily_id=<?php //echo $daily_id; ?>','_blank')">Add New Entry</a>
-->
<a href='#' onclick='addNewEntry()' id='add_link' name='add_link' >Add</a>
<input style='visibility:hidden' type='button' id='finish_button' name='finish_button' value='Finish Entry' onclick='finish(this)' />

<br>
<br>
<br>
<a href='#' onclick="window.open('generate_dr2c.php')">Generate DR2C</a>
<input type='text' id='test' />

<?php
}
?>
<script language='javascript'>
/*		
$(function(){
	$('#field_2').autocomplete(
	{	
	   source: function(request, response){
			$.ajax({
				url: "processing_2.php",
				type: "GET",
				data: "ts=Y", //Filtering criteria is specified for the server to filter the results. I'm not exactly sure what needs to be done since you have not posted server code.
				success: function (data) {
					response(data);
				}
			});
		},
		minLength: 3,
		select: function( event, ui ) {
//			$(this).closest('td').html("Boo!");
			alert(ui.item.label);
		}
	});
});		
*/		
		
$(function(){
	$('#test').on('keyup',function(){
//		alert("A");
			
	});
	
	//$('#field_2').keydown(function(){
	//	alert($('#field_2').val());
	//	$('#field_2').css('border','3px solid red');
	//	$(this).autocomplete(
		/*
		{
			source: function(request, response){
				$.ajax({
					url: "processing_2.php",
					type: "POST",
					data: { ts: "Y" }, //Filtering criteria is specified for the server to filter the results. I'm not exactly sure what needs to be done since you have not posted server code.
					success: function (data) {
						response(data);
					}
				});
			},
			minLength: 3,
			select: function( event, ui ) {
	//			$(this).closest('td').html("Boo!");
				alert(ui.item.label);
			}
		}
		
		*/
	//	{ source: ["Colorado","Denver"] });
//});
	$('#test').autocomplete({
		
		source: ["Colorado","Denver"] 		
		/*
		source: function(request, response){
			$.ajax({
				url: "processing_2.php?ts=Y",
				type: "GET",
				success: function (data) {
					response(data);
				}
			});
		},
		minLength: 3,
		select: function( event, ui ) {
			alert(ui.item.label);
		}
*/

	});	
});		
function enableAuto(){}
</script>
