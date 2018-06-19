<?php
session_start();
?>
<?php
$db=new mysqli("localhost","root","","station");
?>
<?php
if(isset($_GET['removeRow'])){
	$processing_word="Data Deleted.";
	
	$tableType=$_GET['tableType'];
	$delete_id=$_GET['removeRow'];
	
	if($tableType=="dar"){
		$dar_id=$delete_id;
		
		$update="delete from ticket_sold where dar_id='".$dar_id."'";
		$updateRS=$db->query($update);
		
		$update="delete from discrepancy where dar_id='".$dar_id."'";
		$updateRS=$db->query($update);

		$update="delete from discrepancy_ticket where dar_id='".$dar_id."'";
		$updateRS=$db->query($update);
		
		$update="delete from dar where id='".$dar_id."'";
		$updateRS=$db->query($update);
	
	}
	else if($tableType=="defective_equipment"){
		$equipt=$delete_id;
		
		$update="delete from issued_no where e_incident_id='".$equipt."'";
		$updateRS=$db->query($update);
		
		$update="delete from reported_to where e_incident_id='".$equipt."'";
		$updateRS=$db->query($update);
		
		
		$update="delete from repair where e_incident_id='".$equipt."'";
		$updateRS=$db->query($update);
	
		$update="delete from equipment_incident where id='".$equipt."'";
		$updateRS=$db->query($update);
	}

	else if($tableType=="incident_report"){
		$incident_id=$delete_id;
		
		$update="delete from action_taken where incident_id='".$incident_id."'";
		$updateRS=$db->query($update);
		
		$update="delete from reported_to where e_incident_id='".$incident_id."'";
		$updateRS=$db->query($update);
		
		
		$update="delete from repair where e_incident_id='".$incident_id."'";
		$updateRS=$db->query($update);
	
		$update="delete from incident where id='".$incident_id."'";
		$updateRS=$db->query($update);
		
		
		
	
	}

	else if($tableType=="complaint_report"){
		$incident_id=$delete_id;
		
		$update="delete from investigation where complaint='".$incident_id."'";
		$updateRS=$db->query($update);
		
		$update="delete from complaint where id='".$incident_id."'";
		$updateRS=$db->query($update);
		
		
		
	
	}
	

	
	else if($tableType=="repair_equipment"){
		$repair_id=$delete_id;
		
		$update="delete from repair where id='".$repair_id."'";
		$updateRS=$db->query($update);
	
	}
	else if($tableType=="manually_collected"){
		$manually_id=$delete_id;
		
		$update="delete from ".$tableType."	where id='".$manually_id."'";
		$updateRS=$db->query($update);
	
	}
	else if($tableType=="decorum"){
		$decorum_id=$delete_id;
	
		$update="delete from decorum_violation where d_person_id='".$decorum_id."'";
		$updateRS=$db->query($update);
	
		$update="delete from decorum_person where id='".$decorum_id."'";
		$updateRS=$db->query($update);
	
	
	
	}	
	else if($tableType=="ticket_encoding"){
		$ticket_encoding_id=$delete_id;
		
		$sql="select * from ticket_to_monitoring where ticket_error_id='".$ticket_encoding_id."' limit 1";
		$rs=$db->query($sql);
		
		$nm=$rs->num_rows;
		
		if($nm>0){
		
			$row=$rs->fetch_assoc();
			if($row['monitoring_id']==""){
			}
			else {

				$equipt=$row['monitoring_id'];
				
				$update="delete from issued_no where e_incident_id='".$equipt."'";
				$updateRS=$db->query($update);
				
				$update="delete from reported_to where e_incident_id='".$equipt."'";
				$updateRS=$db->query($update);
				
				
				$update="delete from repair where e_incident_id='".$equipt."'";
				$updateRS=$db->query($update);
			
				$update="delete from equipment_incident where id='".$equipt."'";
				$updateRS=$db->query($update);
			}
		}		
		
		$update="delete from ticket_error where id='".$ticket_encoding_id."'";
		$updateRS=$db->query($update);
			

		
		
	}	
	else if($tableType=="refund_ticket"){
		$refund_ticket_id=$delete_id;
		$refund_id=$_GET['refund_id'];
		$station=$_GET['station'];
		
		$link="refund_ticket.php?refund_id=".$refund_id."&station=".$station;
		
		$update="delete from refund where id='".$refund_ticket_id."'";
		$updateRS=$db->query($update);
	
		$processing_word=$link;
	}
	else if($tableType=="refund_person"){
		$refund_id=$delete_id;
		
		$update="delete from refund where refund_id='".$refund_id."'";
		$updateRS=$db->query($update);
		
		$update="delete from refund_ticket_seller where id='".$refund_id."'";
		$updateRS=$db->query($update);
		
		
		
	}
	else if($tableType=="teemr_error_entry"){
		$teemr_error_id=$delete_id;
		$teemr_daily=$_GET['teemr_daily'];
		$teemr_error_person_id=$_GET['teemr_error'];
		
		
		$update="delete from teemr_error_entry where id='".$teemr_error_id."'";
		$updateRS=$db->query($update);

		$link="teemr_error_entry_1.php?ticket_error_id=".$teemr_error_person_id."&ticket_daily_id=".$teemr_daily;
		
		$processing_word=$link;
		
	
	}
	
	
	echo $processing_word;



}

if(isset($_GET['cash_assist'])){
	$sql="select * from cash_assistant where role='cash assistant' order by lastName";
	$rs=$db->query($sql);

	$nm=$rs->num_rows;
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			echo $row['username'].";";
			echo $row['lastName'].", ".$row['firstName']."==>";
		}
	
	}
	else {
		echo "No data available";
	}	
}

if(isset($_GET['equipt_machine'])){
	$station=$_GET['station_id'];
	$equipt=$_GET['equipt_id'];
	
	$sql="select * from machine_no_a where station='".$station."' and equipt='".$equipt."'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			$start=$row['from'];
			$end=$row['to'];
			
			if($end>=$start){
				for($k=$start;$k<=$end;$k++){
					echo $k.";".$k."==>";					
				}
			}
			
			
		
		}
	}
	else {
		echo "No data available";
	}
}

if(isset($_GET['equipt_machine_b'])){
	$station=$_GET['station_id'];
	$equipt=$_GET['equipt_id'];
	
	$sql="select * from machine_no_b where station='".$station."' and equipt='".$equipt."'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			echo $row['machine_no'].";".$row['machine_no']."==>";					
			
			
		
		}
	}
	else {
		echo "No data available";
	}
}



if(isset($_GET['ticket_seller'])){
	$sql="select * from ticket_seller order by last_name";
	$rs=$db->query($sql);

	$nm=$rs->num_rows;
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			echo $row['id'].";";
			echo $row['last_name'].", ".$row['first_name']."==>";
		}
	
	}
	else {
		echo "No data available";
	}	
}

if(isset($_GET['nature_incident'])){
	$sql="select * from nature_incident";
	$rs=$db->query($sql);

	$nm=$rs->num_rows;
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			echo $row['id'].";";
			echo $row['description']."==>";
		}
	
	}
	else {
		echo "No data available";
	}	
}
if(isset($_GET['nature_complaint'])){
	$sql="select * from nature_complaint";
	$rs=$db->query($sql);

	$nm=$rs->num_rows;
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			echo $row['id'].";";
			echo $row['description']."==>";
		}
	
	}
	else {
		echo "No data available";
	}	
}


if(isset($_GET['reason_error'])){
	$sql="select * from errors";
	$rs=$db->query($sql);

	$nm=$rs->num_rows;
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			echo $row['code'].";";
			echo $row['name']."==>";
		}
	
	}
	else {
		echo "No data available";
	}	
}
if(isset($_GET['getStation'])){
	$sql="select * from station";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
				
			echo $row['id'].";";
			echo $row['station_name']."==>";
		
		}
	}
	else {
		echo "No data available";

	}
}

if(isset($_GET['removeViolation'])){
	$update="delete from decorum_violation where id='".$_GET['removeViolation']."'";
	$updateRS=$db->query($update);

	echo "Data deleted.";
}

if(isset($_GET['tagDecorum'])){
	$person_id=$_GET['person_id'];
	$item_id=$_GET['item_id'];
	
	$update="insert into decorum_violation(item_id,d_person_id) values ('".$item_id."','".$person_id."')";
	$updateRS=$db->query($update);

	echo "Data inserted.";
}






if(isset($_GET['trainDriver'])){
	$sql="select * from train_driver where position in ('TD','STDO') order by lastName";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			echo $row['id'].";";
			echo str_replace("Ñ","_ENYE_",$row['lastName']).", ".$row['firstName']."==>";
		}
	
	}
	else {
		echo "No data available";
	}
}

if(isset($_GET['supervisor'])){
	$sql="select * from train_driver where position in ('STDO') order by lastName";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			echo $row['id'].";";
			echo $row['lastName'].", ".$row['firstName']."==>";
		}
	
	}
	else {
		echo "No data available";
	}
}


if(isset($_GET['scrollRolling'])){
	$sql="select * from equipment_type where equipment_code='".$_GET['scrollRolling']."'";
	$rs=$db->query($sql);
	
	$row=$rs->fetch_assoc();
	
	$incident_code=$row['incident_code'];
	
	$sql="select * from equipment where type='".$incident_code."' order by equipment_name";
//	if(($incident_code=="RS")||($incident_code=="PWR")){
	if($incident_code=="PWR"){
		$sql="select * from equipment where category='".$_GET['category']."' order by equipment_name";
		
	}
	
	
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
				
			echo $row['id'].";";
			echo $row['equipment_name']."==>";
		
		}
	}
	else {
		echo "No data available";
	
	}

}

if(isset($_GET['scrollOthers'])){
	$sql="select * from other_problem order by problem";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
				
			echo $row['id'].";";
			echo $row['problem']."==>";
		
		}
	}
	else {
		echo "No data available";
	
	}

}



if(isset($_GET['scrollSubItem'])){
	$sql="select * from sub_item where equipment_id='".$_GET['scrollSubItem']."' order by sub_item";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	if($nm>0){
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
				
			echo $row['id'].";";
			echo $row['sub_item']."==>";
		
		}
	}
	else {
		echo "No data available";

	}
}

if(isset($_GET['getCars'])){
	
	$sql="select * from train_incident_report inner join train_availability on train_incident_report.train_ava_id=train_availability.id where incident_id='".$_GET['getCars']."'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	if($nm>0){
	
		for($i=0;$i<$nm;$i++){
			$row=$rs->fetch_assoc();
			
			echo $row['car_a'].";";
			echo $row['car_b'].";";
			echo $row['car_c'].";";
		
		}
	}
	else {
		echo "No data available";
	}

}


if(isset($_GET['deleteSwitch'])){
	$sql="delete from train_switch where id='".$_GET['deleteSwitch']."'";
	
	$rs=$db->query($sql);
	echo "Data deleted.";
	


}
if(isset($_GET['checkCar'])){
	$year=$_SESSION['year'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];
	
	$availability_date_code=date("Y-m-d",strtotime($year."-".$month."-".$day));

	$sql="select * from train_availability inner join train_ava_time on train_availability.id=train_ava_time.train_ava_id where (car_a='".$_GET['checkCar']."' or car_b='".$_GET['checkCar']."' or car_c='".$_GET['checkCar']."') and remove_time is null and status='active' and date like '".$availability_date_code."%%'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		if($_GET['checkCar']==""){
			echo "No car";
		}
		else  {
		echo $_GET['car'];
		}
	}
	else {
		echo "No car";
	}
}


if(isset($_GET['removeIncident'])){
	$incident_no=$_GET['removeIncident'];
	clearIncidentRecords($incident_no);
	$update="delete from incident_report where id='".$incident_no."'";
	$rs=$db->query($update);

}




function clearIncidentRecords($incident){
	$db=new mysqli("localhost","root","","transport");

	$update="delete from incident_description where incident_id='".$incident."'";
	$rs=$db->query($update);
	
	$update="delete from incident_no where incident_id='".$incident."'";
	$rs=$db->query($update);

	$update="delete from level where incident_id='".$incident."'";
	$rs=$db->query($update);

	$update="delete from service_interruption where incident_id='".$incident."'";
	$rs=$db->query($update);

	$update="delete from incident_cars where incident_id='".$incident."'";
	$rs=$db->query($update);
	
}

if(isset($_GET['removeClearance'])){
	
	$clearance_id=$_GET['removeClearance'];
	$clearance_date=$_GET['removeDate'];

	
	$update="delete from clearance where clearance_no='".$clearance_id."' and date='".$clearance_date."'";
	$rs=$db->query($update);
	echo "Data deleted";

}

if(isset($_GET['removeInterruption'])){
	$interruption_id=$_GET['removeInterruption'];
	
	$update="delete from service_interruption where id='".$interruption_id."'";
	$rs=$db->query($update);
	
	echo "Data deleted";
}
if(isset($_GET['ph_trams'])){
	


}

?>
