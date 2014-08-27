<?php
$db=new mysqli("localhost","root","","transport");
$db2=new mysqli("localhost","root","","external");

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
<?php
if(isset($_POST['fieldType'])){
	$sql="update incident_report ";
	$incident_report=$_POST['incident_report'];
	switch($_POST['fieldType']){
		case "onboard_equipt":
			$sql.="set equipt='".$_POST['equipment']."' ";
			break;
		case "dotc":
			if(isset($_POST['dotc'])){
				$dotc_taken=$_POST['dotc'];

			}
			else if(isset($_POST['dotc_coordinated'])){
				$dotc_taken=$_POST['dotc_coordinated']." ".$_POST['coordinated_to'];
			}		
		
			$sql.="set action_dotc='".$dotc_taken."' ";
			break;
		case "maintenance":
			$sql.="set action_maintenance='".$_POST['maintenance_provider']."' ";
			break;
		case "level":
			$level_condition=$_POST['condition'];

			$sql.="set level='".$_POST['level']."',level_condition='".$level_condition."' ";
			break;
		case "description":
			$sql.="set description='".$_POST['description']."' ";
			break;

		case "duration":
			$sql.="set duration='".$_POST['duration']."' ";
			break;
			
			
		case "linked_to":
			$sql.="set linked_to='".$_POST['incident_link']."' ";
			break;
		
		case "incident_no":
			
			$incidentSQL="select * from incident_report where id='".$incident_report."'";
			$incidentRS=$db->query($incidentSQL);
			$incidentRow=$incidentRS->fetch_assoc();
			
			$suffixSQL="select * from equipment_type where equipment_code='".$incidentRow['incident_type']."'";
			$suffixRS=$db->query($suffixSQL);
			
			$suffixRow=$suffixRS->fetch_assoc();
			$suffix=$suffixRow['incident_code'];
		
		
		
			$sql.="set incident_no='".$_POST['incident_number']." ".$suffix."' ";
			//$_POST['incident_report']=$_POST['incident_number'];
			break;
		case "problem":
			$sql.="set incident_type='".$_POST['type']."',equipt='',";

			$incidentSQL="select * from incident_report where id='".$incident_report."'";
			$incidentRS=$db->query($incidentSQL);
			$incidentRow=$incidentRS->fetch_assoc();
			
			$suffixSQL="select * from equipment_type where equipment_code='".$_POST['type']."'";
			$suffixRS=$db->query($suffixSQL);
			$suffixRow=$suffixRS->fetch_assoc();
			$suffix=$suffixRow['incident_code'];
		
		
		
			$sql.="incident_no='".$incidentRow['id']." ".$suffix."' ";


			
			if($_POST['type']=="ser_int"){
				echo "<script language='javascript'>";
				echo "window.open('service interruption.php?incident=".$incident_report."');";
				echo "</script>";
			}
			
			
			
			
			break;
		case "cancelled":
		
			$cancelTerm=$_POST['cancel'];
			if($cancelTerm=="whole"){
				$cancel=1;
			}
			else if($cancelTerm=="half"){
				$cancel=.5;
			}
			else if($cancelTerm=="more"){
				$cancel=$_POST['cancel_more'];
			}
			$sql.="set cancel='".$cancel."' ";
			break;	
		case "date":
			$year=$_POST['year'];
			$month=$_POST['month'];
			$day=$_POST['day'];
			
			$hour=$_POST['hour'];
//			echo $hour;
			$minute=$_POST['minute'];
//			echo $minute;
			$amorpm=$_POST['amorpm'];
//			echo $amorpm;
			$equipment=$_POST['equipment'];
			if($amorpm=="pm"){
				if($hour<12){
					$hour+=12;
				}
				else {
				}
			}
			else {
				if($hour=="12"){
					$hour=0;
				}
			}
			
			$incident_date=$year."-".$month."-".$day." ".$hour.":".$minute;
			//date("Y-m-d H:i",strtotime($year."-".$month."-".$day." ".$hour.":".$minute));
	//		echo $incident_date;
			$sql.="set incident_date='".$incident_date."' ";
			break;
		}
	$sql.=" where id='".$incident_report."'";

	$rs=$db->query($sql);
	
	if($_POST['fieldType']=='onboard_equipt'){
		$update="update incident_description set equipt='".$_POST['equipment']."', subitem='".$_POST['subitem']."' where incident_id='".$incident_report."'";

		$rs=$db->query($update);
	}
	if($_POST['fieldType']=='problem'){
		$update="update incident_description set equipt='', subitem='' where incident_id='".$incident_report."'";
		$rs=$db->query($update);
	}
	else if($_POST['fieldType']=="additional_defects"){
		$update="delete from incident_defects where incident_id='".$incident_report."'";
		$rs=$db2->query($update);
		
		$update="insert into incident_defects(incident_id,equipt_id,sub_item_id) (select '".$incident_report."',equipt_id,sub_item_id from temp_multiple)";
		$rs=$db2->query($update);
		
		$update="delete from temp_multiple";
		$rs=$db2->query($update);
			
	
	
	}
	

	else if($_POST['fieldType']=="level"){
		$levelSQL="select * from level where incident_id='".$incident_report."'";
		$levelRS=$db->query($levelSQL);


		
		$levelNM=$levelRS->num_rows;

		if($_POST['level']=="2"){
			//$update="update incident_report set l2='".$_POST['order']."',l3='',l4='' where id='".$incident_report."'";
			//$rs=$db->query($update);
			
			
			if($levelNM>0){
//				$update="update level set level='2',order='".$_POST['order']."' where id='".$incident_report."'";
//				$rs=$db->query($update);
			}
			else {
//				$update="insert into level(level,order,incident_id
			
			}

		}
		else if($_POST['level']=="3"){
			//$update="update incident_report set l3='".$_POST['order']."',l2='',l4='' where id='".$incident_report."'";
		//	$rs=$db->query($update);
		
			if($levelNM>0){
			
			
			}
			else {
			
			
			}

		}
		else if($_POST['level']=="4"){
		//	$update="update incident_report set l4='".$_POST['order']."',l2='',l3='' where id='".$incident_report."'";
			//$rs=$db->query($update);
			
			if($levelNM>0){
			
			
			}
			else {
			
			}

		}

		$incidentSQL="select * from incident_report where id='".$incident_report."'";
		$incidentRS=$db->query($incidentSQL);
		$incidentRow=$incidentRS->fetch_assoc();

		$incident_date=date("Y-m-d",strtotime($incidentRow['incident_date']));

		
		$updateSQL="delete from level where incident_id='".$incident_report."'";
		$updateRS=$db->query($updateSQL);
		
		$updateSQL="insert into level(date,incident_id,level) values ";
		$updateSQL.="('".$incident_date."','".$incident_report."','".$_POST['level']."')";
		$updateRS=$db->query($updateSQL);
		
		
		
	}
	else if($_POST['fieldType']=="index"){
		$update="update incident_description set index_no='".$_POST['index_id']."', car_no='".$_POST['car']."' where incident_id='".$incident_report."'";
		$rs=$db->query($update);
		
		$update="delete from incident_cars where incident_id='".$incident_report."'";
		$rs=$db->query($update);

		if($_POST['car']==""){
		}
		else {
			$update="insert into incident_cars(incident_id,car_no) values ('".$incident_report."','".$_POST['car']."')";
			$rs=$db->query($update);

		}
		
		if($_POST['car_2']==""){
		}
		else {
			$update="insert into incident_cars(incident_id,car_no) values ('".$incident_report."','".$_POST['car_2']."')";
			$rs=$db->query($update);
		
		}
		
		if($_POST['car_3']==""){
		}
		else {
			$update="insert into incident_cars(incident_id,car_no) values ('".$incident_report."','".$_POST['car_3']."')";
			$rs=$db->query($update);
		
		}
		
		
		
	}
	
	else if($_POST['fieldType']=="location"){
		$update="update incident_description set location='".$_POST['location']."',direction='".$_POST['direction']."' where incident_id='".$incident_report."'";

		$rs=$db->query($update);

	}

	else if($_POST['fieldType']=="reported_by"){
		$update="update incident_description set reported_by='".$_POST['reported_by']."' where incident_id='".$incident_report."'";

		$rs=$db->query($update);

	}

	else if($_POST['fieldType']=="received_by"){
		$update="update incident_description set received_by='".$_POST['received_by']."' where incident_id='".$incident_report."'";

		$rs=$db->query($update);

	}

	
	echo "Data updated.";

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


textarea{ 
	border: 1px solid rgb(185, 201, 254);
	background-color: #dfe7f2;
	color: rgb(0,51,153);
	border-radius: 3px;
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

#multi_list tr th,#multi_list2 tr th {

	background-color: #33aa55;
	color: #fff;
	border: 1px solid rgb(185, 201, 254);

}

#multi_list tr:nth-child(2) td,#multi_list2 tr:nth-child(2) td  {
	background-color: rgb(185, 201, 254);
	color: rgb(0,51,153);


}
#multi_list tr:nth-child(n+2) td,#multi_list2 tr:nth-child(n+2) td{

	background-color: #dfe7f2;
	color: rgb(0,51,153);

}


select { border: 1px solid rgb(185, 201, 254); color: rgb(0,51,153); background-color: #dfe7f2;  }
</style>
<body>
<br>
<br>
<br>

<form action='edit_ccdr.php' method='post'><span class='label'>Search Incident Number</span><input class='text_input' type=text name='search_incident_number' /><input type=submit value='Search' /></form>

<?php
$db=new mysqli("localhost","root","","transport");
?>
<?php
if(isset($_POST['search_incident_number'])){
	$sql="select * from incident_report where incident_no like '".$_POST['search_incident_number']."%%' order by incident_no";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
	?>	
<form action='edit_ccdr.php' method=post>
	<span class='label'>
	Retrieve Incident Report: 
	</span>
	<select  class='text_input' name='incident_report'>
	<?php
		for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>	
			<option value="<?php echo $row['id']; ?>"><?php echo $row['incident_no']; ?></option>
	<?php
		}
	?>
	</select>
	<input type=submit value='Retrieve' />
</form>
	<?php	
	}

}
?>
<?php
?>
<?php
	if((isset($_POST['incident_report']))||(isset($_GET['ir']))){
		
		if(isset($_GET['ir'])){
			$incident_report=$_GET['ir'];
		}
		else {
			$incident_report=$_POST['incident_report'];
		
		}
		
//		$sql="select * from train_incident_view inner join level on train_incident_view.incident_id=level.incident_id where id='".$incident_report."'";
		$sql="select * from incident_report inner join level on incident_report.id=level.incident_id where incident_report.id='".$incident_report."'";
		
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		

		
		$level_condition=$row['level_condition'];
		
		$conditionSQL="select * from level_condition where id='".$level_condition."'";
		$conditionRS=$db->query($conditionSQL);
		
		$conditionRow=$conditionRS->fetch_assoc();
		
		$condition=$conditionRow['description'];
		
		$link_no="";
		$linked_to=$row['linked_to'];
		
		$linkSQL="select * from incident_report where id='".$linked_to."'";
		$linkRS=$db->query($linkSQL);
		
		$linkNM=$linkRS->num_rows;
		
		if($linkNM>0){
			$linkRow=$linkRS->fetch_assoc();
		
			$link_no=$linkRow['incident_no'];
		
		
		}
			
		$incident_no=$row['incident_no'];
		$problem_type2=$row['incident_type'];
		$equipSQL="select * from equipment_type where equipment_code='".$problem_type2."'";
		$equipRS=$db->query($equipSQL);
		$row2=$equipRS->fetch_assoc();
		$problem_type=$row2['equipment_name'];


		$level=$row['level'];
		
		$levelClause=="";
		if($level==2){
			$levelClause.=" (".getOrdinal($row['order']).")";
		
		}
		else if($level==3){
			$levelClause.=" (".getOrdinal($row['order']).")";
		
		}
		else if($level==4){
			$levelClause.=" (".getOrdinal($row['order']).")";
		
		}		
		$cancel=$row['cancel'];
		
		
		$date=date("Y-m-d",strtotime($row['incident_date']));
		$time=date("H:ia",strtotime($row['incident_date']));
		$duration=$row['duration'];
		$equipt=$row['equipt'];
		
		$onboard_equipt="";
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
		
		$car[0]="";
		$car[1]="";
		$car[2]="";

		
		$carSQL="select * from incident_cars where incident_id='".$incident_report."'";
		$carRS=$db->query($carSQL);
		$carNM=$carRS->num_rows;
		
		if($carNM>0){
			for($b=0;$b<$carNM;$b++){
				$carRow=$carRS->fetch_assoc();
				$car[$b]=$carRow['car_no'];
			}			
			
			$carClause=$car[0];
			if($car[1]==""){
			}
			else {
				$carClause.=", ".$car[1];
			}
			
			if($car[2]==""){
			}
			else {
				$carClause.=", ".$car[2];
			}
			
		}
		
		
		
		
		
		
		
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
		if(isset($_POST['incident_report'])){
			if($level_condition=='3'){
				echo "<script language='javascript'>";
				echo "window.open('service interruption.php?incident=".$incident_report."');";
				echo "</script>";
		//		header("Location: service interruption.php?incident=".$incident_code);
			}		
		}	
		
		
		$db2=new mysqli("localhost","root","","external");
		$defectsSQL="select * from incident_defects where incident_id='".$incident_report."'";
		
		$defectsRS=$db2->query($defectsSQL);
		$defectsNM=$defectsRS->num_rows;
		
		
		$additional_defects="";
		if($defectsNM>0){
			for($n=0;$n<$defectsNM;$n++){
				$defectsRow=$defectsRS->fetch_assoc();

				$equiptSQL="select * from equipment where id='".$defectsRow['equipt_id']."' limit 1";
				$equiptRS=$db->query($equiptSQL);
				$equiptRow=$equiptRS->fetch_assoc();
				
				$eq_name=$equiptRow['equipment_name'];
				

				if($defectsRow['sub_item_id']==""){
				}
				else {
					$subitemSQL="select * from sub_item where id='".$defectsRow['sub_item_id']."'";
					$subitemRS=$db->query($subitemSQL);
					$subitemNM=$subitemRS->num_rows;
					
					if($subitemNM>0){
						$subitemRow=$subitemRS->fetch_assoc();
						$sub_item=$subitemRow['sub_item'];
					}
				}
					
				$additional_defects.="<tr><td>".$eq_name."</td><td>".$sub_item."</td></tr>";
			}
		}

		
		
		
	}



?>




<table  width=80% class='ccdr'>
<tr id='ccdr_heading'><th colspan=3>Control Center Daily Report</th></tr>
<tr><th width=20%>Incident Number:</th><td width=70%><?php echo $incident_no; ?></td></tr>
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

//	if($serviceNM>0){
		echo " [<a href='#' onclick='window.open(\"service interruption.php?incident=".$incident_report."\")'>Report</a>]";
	
//	}

}

?>
</td>

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
</td>
</tr>
<tr>
<th>Additional Defects
</th>
<td>
		<?php echo "<table name='multi_list2' id='multi_list2' width=80%>";
		echo "<tr><th>Equipment</th><th>Sub-item</th></tr>";
		?>
		<?php	
		echo $additional_defects;
		echo "</table>";
	
		?>

</td>
</tr>


<tr>
<th>Linked Incident (?)</th>
<td>
<?php
if($link_no==""){
}
else {
?>
See <a href='#' onclick='window.open("incident details.php?ir=<?php echo $linked_to; ?>","_blank")'><?php echo $link_no; ?></a>

<?php
}
?>
</td>
</tr>
<tr>
<th>Index No./Car No.</th>


<td>
<?php
if($carNo==""){
	echo $indexNo;
}
else {
	echo $indexNo." / ".$carClause;

}

?>


</td>

<tr>
<th>Cancelled Loops</th>
<td>
<?php echo $cancel; ?>

</td>
</tr>

</tr>
<tr><th>Level:</th><td><?php echo $level; echo $levelClause; echo ". ".$condition; ?></td>
</tr>



<tr><th>Date:</th><td><?php echo $date; ?></td></tr>
<tr><th>Time:</th><td><?php echo $time; ?></td></tr>
<tr><th>Incident Duration:</th><td><?php echo $duration; ?></td></tr>

<tr><th>Location/Direction:</th><td><?php echo str_replace("D","Depot",$direction); echo " ".$location; ?></td></tr>




<tr><th>Description:</th><td><?php echo $description; ?></td></tr>
		
		

</table>
<br>
<table  class='ccdr' width=80% border=1>
<tr id='ccdr_heading'><th colspan=3>Reporting</th></tr>
<tr><th width=20%>Reported By</th><td width=70%><?php echo $reported_by; ?></td></tr>
<tr><th>Received By:</th><td><?php echo $received_by; ?></td>
</tr>

</table>
<br>
<table  class='ccdr' width=80% border=1>
<tr id='ccdr_heading'><th colspan=3>Action Taken</th></tr>
<tr><th width=20%>DOTC:</th><td width=70%><?php echo $dotc_action; ?></td>
</tr>
<tr><th>Maintenance Provider:</th><td><?php echo $maintenance_action; ?></td>

</tr>
</table>
<br>
<br>
</body>