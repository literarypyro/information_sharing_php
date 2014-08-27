<?php
$db=new mysqli("localhost","root","","transport");
?>
<?php
function getOrdinal($number){
$ends = array('th','st','nd','rd','th','th','th','th','th','th');
if (($number %100) >= 11 && ($number%100) <= 13)
   $abbreviation = $number. 'th';
else
   $abbreviation = $number. $ends[$number % 10];

   
 return $abbreviation;  

}
?>
<style type='text/css'>
.ccdr tr:nth-child(odd)
{
background-color: #dfe7f2;
color: #000000;
}
.ccdr tr th:first-child {
	color: rgb(0,51,153);

}

.ccdr td, .ccdr th {
border: 1px solid rgb(185, 201, 254);
padding: 0.3em;
}
.ccdr {
border: 1px solid rgb(185, 201, 254);

}
.ccdr #ccdr_heading {
background-color:rgb(185, 201, 254);
color: rgb(0,51,153);
}
body {
	margin-left:30px;
	margin-right:30px;

}
ul.nav li {
	list-style-type:none;
	display: inline;
	padding-left: 0;
	margin-left: 0;

	
	padding: 5px;
	spacing: 1.75px;
	color: black;
	
	
	min-width: 8em;
	margin-right: 0.5em;
	
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	-webkit-box-shadow: 3px 3px 3px 3px rgba(43, 43, 77, 0.5);
	-moz-box-shadow: 3px 3px 3px 3px rgba(43, 43, 77, 0.5);
	box-shadow: 3px 3px 3px 3px rgba(43, 43, 77, 0.5);
}

textarea{ 
	border: 1px solid rgb(185, 201, 254);
	background-color: #dfe7f2;
	color: rgb(0,51,153);
	border-radius: 3px;
}
ul.nav li a{
	text-decoration: none;

}
#edit_form th {
	background-color: rgb(185, 201, 254);
	color: rgb(0,51,153);

}
#edit_form input[type="text"] {
	height:25px; 
	font-weight:bold; 
	font-size:15px; 
	font-family:courier; 
	border: 1px solid rgb(185, 201, 254);
	background-color: #dfe7f2;
	color: rgb(0,51,153);
	border-radius: 3px;

}

.label {
	background-color: rgb(185, 201, 254);
	color: rgb(0,51,153);
	spacing: 2px;
	padding: 5px;
}

.text_input {
	height:25px; 
	font-weight:bold; 
	font-size:15px; 
	font-family:courier; 
	border: 1px solid rgb(185, 201, 254);
	background-color: #dfe7f2;
	color: rgb(0,51,153);
	border-radius: 3px;

}


.rowHeading {
	color: rgb(0,51,153);
	font-weight:bold;
}

select { border: 1px solid rgb(185, 201, 254); color: rgb(0,51,153); background-color: #dfe7f2;  }
</style>
<?php
	if((isset($_POST['incident_report']))||(isset($_GET['ir']))){
		
		if(isset($_GET['ir'])){
			$incident_report=$_GET['ir'];
		}
		else {
			$incident_report=$_POST['incident_report'];
		
		}
		
		$sql="select * from incident_report where id='".$incident_report."'";

		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
			
			
		$level_condition=$row['level_condition'];		
		$incident_no=$row['incident_no'];
		$problem_type2=$row['incident_type'];
		$equipSQL="select * from equipment_type where equipment_code='".$problem_type2."'";
		$equipRS=$db->query($equipSQL);
		$row2=$equipRS->fetch_assoc();
		$problem_type=$row2['equipment_name'];
	
		

		$level=$row['level'];
		
		$levelClause=="";
		if($level==2){
			$levelClause.=" (".$row['level'].getOrdinal($row['level']).")";
		
		}
		else if($level==3){
			$levelClause.=" (".$row['level'].getOrdinal($row['level']).")";
		
		}
		else if($level==4){
			$levelClause.=" (".$row['level'].getOrdinal($row['level']).")";
		
		}		
		$cancel=$row['cancel'];		
		
		
		$date=date("Y-m-d",strtotime($row['incident_date']));
		$time=date("H:ia",strtotime($row['incident_date']));
		$duration=$row['duration'];
		$equipt=$row['equipt'];
		
		if($problem_type2=="others"){
			$equipSQL="select * from other_problem where id='".$equipt."'";

			$equipRS=$db->query($equipSQL);
			$row2=$equipRS->fetch_assoc();
			$onboard_equipt=$row2['problem'];		
		}
		else {		
			$equipSQL="select * from equipment where id='".$equipt."'";

			$equipRS=$db->query($equipSQL);
			$row2=$equipRS->fetch_assoc();
			$onboard_equipt=$row2['equipment_name'];
		}
		$description=$row['description'];
		$dotc_action=$row['action_dotc'];
		$maintenance_action=$row['action_maintenance'];
		
		$category=$row['category'];

		$categoryName="";

		if($category==""){
			$categorySQL="select * from category where category_code='".$category."'";
			$categoryRS=$db->query($categorySQL);
			
			$categoryRow=$categoryRS->fetch_assoc();
			
			$categoryName=$categoryRow['category'];

		
		}
		

		$irSQL="select * from incident_description where incident_id='".$incident_report."'";
		$irRS=$db->query($irSQL);
		
			
		$irRow=$irRS->fetch_assoc();
		
		
		$indexNo=$irRow['index_no'];
		$carNo=$irRow['car_no'];
		$location=$irRow['location'];
		$direction=$irRow['direction'];
		
		$reported_by=$irRow['reported_by'];
		$received_by="";
		
		$tdSQL="select * from train_driver where id='".$irRow['received_by']."'";
		$tdRS=$db->query($tdSQL);
		$tdNM=$tdRS->num_rows;
		if($tdNM>0){
			$tdRow=$tdRS->fetch_assoc();
			$received_by=$tdRow['lastName'].", ".$tdRow['firstName'];
			
		}
		
		
		
		
		if($direction=="S"){
			$direction="";
		}
		
		
		$subClause="";
		
		$subItemSQL="select * from sub_item where id='".$irRow['subitem']."'";
		$subItemRS=$db->query($subItemSQL);
		$subItemNM=$subItemRS->num_rows;
		
		if($subItemNM>0){
			$subItemRow=$subItemRS->fetch_assoc();

			$subClause=" / ".$subItemRow['sub_item'];			
		
		}
		
		
		$db=new mysqli("localhost","root","","transport");
		
		$serviceSQL="select * from service_interruption where incident_id='".$incident_report."'";
		$serviceRS=$db->query($serviceSQL);
		$serviceNM=$serviceRS->fetch_assoc();
	}



?>




<table  width=80% class='ccdr'>
<tr id='ccdr_heading'><th colspan=3>Incident Details</th></tr>
<tr><th width=20%>Incident Number:</th><td width=70%><?php echo $incident_no; ?></td><td width=10%>&nbsp;</td></tr>
<tr><th>Type of Problem:</th>
<td>
<?php echo $problem_type; ?>
<?php
if($categoryName==""){
}
else {
	echo " / ".$categoryName;

}
?>
<?php
if($level_condition=="3"){
	if($serviceNM>0){
		echo " [<a href='#' onclick='window.open(\"service interruption.php?incident=".$incident_report."\")'>Report</a>]";
	
	}

}

?>
</td><td>&nbsp;</td></tr>

<tr><th>On-board Equipt/Accessories:</th>


<td><?php echo $onboard_equipt; ?>
<?php
echo $subClause; 
?>

<span style="visibility:hidden">
<select name='equipment_copy' id='equipment_copy' hidden>
<option></option>
<?php 
$db=new mysqli("localhost","root","","transport");
$sql="select * from equipment order by equipment_name";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
$row=$rs->fetch_assoc();
?>
<option value='<?php echo $row['id']; ?>'><?php echo $row['equipment_name']; ?></option>
<?php
}
?>
<option value='others'>OTHERS</option>
</select>
</span>
</td><td>&nbsp;</td></tr>

<tr>
<th>Index No./Car No.</th>


<td>
<?php
if($carNo==""){
	echo $indexNo;
}
else {
	echo $indexNo." / ".$carNo;

}

?>


</td><td>&nbsp;</td></tr>

<tr>
<th>Cancelled Loops</th>
<td>
<?php echo $cancel; ?>

</td>
<td>
&nbsp;
</td>
</tr>






<tr><th>Level:</th><td><?php echo $level; echo $levelClause; ?></td><td><!--<a href='#edit_form' onclick='fillEdit("level")'>Edit</a>-->&nbsp;</td></tr>



<tr><th>Date:</th><td><?php echo $date; ?></td><td>&nbsp;</td></tr>
<tr><th>Time:</th><td><?php echo $time; ?></td><td>&nbsp;</td></tr>
<tr><th>Incident Duration:</th><td><?php echo $duration; ?></td><td>&nbsp;</td></tr>

<tr><th>Location/Direction:</th><td><?php echo $direction; echo " ".$location; ?></td><td>&nbsp;</td></tr>




<tr><th>Description:</th><td><?php echo $description; ?></td><td>&nbsp;</td></tr>
		
		

</table>
<br>
<table  class='ccdr' width=80% border=1>
<tr id='ccdr_heading'><th colspan=3>Reporting</th></tr>
<tr><th width=20%>Reported By</th><td width=70%><?php echo $reported_by; ?></td><td width=10%>&nbsp;</td></tr>
<tr><th>Received By:</th><td><?php echo $received_by; ?></td><td>&nbsp;</td></tr>

</table>
<br>
<table  class='ccdr' width=80% border=1>
<tr id='ccdr_heading'><th colspan=3>Action Taken</th></tr>
<tr><th width=20%>DOTC:</th><td width=70%><?php echo $dotc_action; ?></td><td width=10%>&nbsp;</td></tr>
<tr><th>Maintenance Provider:</th><td><?php echo $maintenance_action; ?></td><td>&nbsp;</td></tr>


</table>