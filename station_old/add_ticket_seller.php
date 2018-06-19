<?php
$db=new mysqli("localhost","root","","station");

if((isset($_POST['ts_id']))&&($_POST['ts_id']!=="")){
	
	$first_name=strtoupper($_POST['first_name']);
	$last_name=strtoupper($_POST['last_name']);
	$middle_name=strtoupper($_POST['middle_name']);

	$position=$_POST['position'];
	$employee_no=$_POST['employee_no'];

	$ts_id=$_POST['ts_id'];
	
	$sql="select * from ticket_seller where id='".$ts_id."'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows; 
	
	if($nm>0){
		echo "A Ticket Seller with the same TS ID exists.";
	}
	else {
		$update="insert into ticket_seller(first_name,last_name,middle_name,position,employee_number,id) values ";
		$update.="(\"".$first_name."\",\"".$last_name."\",\"".$middle_name."\",'".$position."','".$employee_no."','".$ts_id."')";
		$updateRS=$db->query($update);

		
		echo "Ticket Seller has been added.";
	
	}
	


}
?>
<?php
require("monitoring menu.php");
?>
<link href="layout/landbank/logbook style.css" rel="stylesheet" type="text/css"  id='stylesheet' />

<br>
<br>
<form action='add_ticket_seller.php' method='post'>
<table border='1px' style='border-collapse:collapse;' class='logbookTable'>
<tr>
<th colspan=2>
Add Ticket Seller/Supervisor
</th>
</tr>
<tr>
<th>First Name</th>
<td><input type='text' name='first_name' size=30 /></td>
</tr>
<tr>
<th>Last Name</th>
<td><input type='text' name='last_name' size=30 /></td>
</tr>
<tr>
<th>Middle Name</th>
<td><input type='text' name='last_name' size=20 /></td>
</tr>
<tr>
<th>Position</th>
<td>
<select name='position'>
<option>TICKET SELLER II</option>
<option>TICKET SELLER III</option>
<option>SENIOR TRANSPORTATION DEVELOPMENT OFFICER</option>
</select>
</td>
</tr>
<tr>
<th>Employee Number</th>
<td>
<input type='text' name='employee_no' />
</td>
</tr>
<tr>
<th>Ticket Seller ID</th>
<td>
<input type='text' name='ts_id' />
</td>
</tr>
<tr>
<td colspan=2 align=center><input type='submit' value='Submit' /></td>
</tr>

</table>
</form>