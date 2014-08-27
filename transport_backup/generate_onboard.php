<?php


?>
<?php
function getEquiptCount($equipt,$onboard_date){
		
	$db=new mysqli("localhost","root","","transport");	
		
	$sql="select * from incident_description where incident_id in (select incident_id from train_union where trainDate like '".$onboard_date."%%') and equipt='".$equipt."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

}
function getRemarks($equipt,$onboard_date){
	$db=new mysqli("localhost","root","","transport");	

	$remarks="";	
		
	$sql2="select * from incident_description inner join incident_report on incident_id=incident_report.id where incident_id in (select incident_id from train_union where trainDate like '".$ccdr_date."%%') and incident_description.equipt='".$equipt."'";
	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;
	for($n=0;$n<$nm2;$n++){
		$row2=$rs2->fetch_assoc();
		if($n==0){

			$remarks.="Car # ".$row2['car_no']." - See IN ".$row2['incident_no']; 
			
		}
		else {
			$remarks.=", Car # ".$row2['car_no']." - See IN ".$row2['incident_no']; 
		}
	}
	return $remarks;



}
?>
<?php
	$filename="OnoardEquipt.xls";
	
	$oldfilename="forms/".$filename;
	$dateSlip=date("Y-m-d His");
	$newFilename="printout/Onboard_".$dateSlip.".xls";
	copy($oldfilename,$newFilename);
	$workSheetName="Onboard Equipment";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);
	
  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");
	
	$rowCount=0;	
	
	
	
	
	
	
	
?>