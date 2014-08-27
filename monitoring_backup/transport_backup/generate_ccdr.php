<?php
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");

?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
if(isset($_GET['ccdr'])){
	$ccdr_date=$_GET['ccdr'];

	$filename="CCDR.xls";

	$oldfilename="forms/".$filename;
	$dateSlip=date("Y-m-d His");
	$newFilename="printout/CCDR_".$dateSlip.".xls";
	copy($oldfilename,$newFilename);
	$workSheetName="CCDR";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");
	
	$rowCount=0;	
	
	
	$db=new mysqli("localhost","root","","transport");

	$sql="select * from incident_report inner join incident_description on incident_report.id=incident_description.incident_id where incident_date like '".$ccdr_date."%%' order by incident_date";
	$rs=$db->query($sql);

	$nm=$rs->num_rows;
	
	$rowCount+=14;
	$page_counter=1;


	addContent(setRange("L9","N9"),$excel,date("F d, Y",strtotime($sccdr_date)),"true",$ExWs);

	addContent(setRange("L8","N8"),$excel,date("l",strtotime($sccdr_date)),"true",$ExWs);	
	
	$db=new mysqli("localhost","root","","transport");
	$timeTableSQL="select *,timetable_day.id as timeId from timetable_day inner join timetable_code on timetable_day.timetable_code=timetable_code.id where train_date like '".$sccdr_date."%%'";

	$timeTableRS=$db->query($timeTableSQL);
	$timeTableNM=$timeTableRS->num_rows;
	if($timeTableNM>0){
		$timeTableRow=$timeTableRS->fetch_assoc();
		addContent(setRange("L10","N10"),$excel,$timeTableRow['code'],"true",$ExWs);
		
		
	}	


	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		
		$hourStamp=date("Hi",strtotime($row['incident_date']));
		$incident_no=$row['incident_no'];
		$duration=$row['duration'];
		
		$description="";
		if($incident_type=="rolling"){
			$description="Index #".$row['index_no'].", ".$row['car_no'];
		
		}
		
		$description.=$row['description'];
		$action_dotc=$row['action_dotc'];
		$action_maintenance=$row['action_maintenance'];
		$level=$row['level'];
		
		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$incident_no,"true",$ExWs);
		addContent(setRange("B".$rowCount,"B".$rowCount),$excel,"'".$hourStamp,"true",$ExWs);
		addContent(setRange("C".$rowCount,"C".$rowCount),$excel,$duration,"true",$ExWs);
		addContent(setRange("D".$rowCount,"F".$rowCount),$excel,$description,"true",$ExWs);
		addContent(setRange("G".$rowCount,"I".$rowCount),$excel,$action_dotc,"true",$ExWs);
		addContent(setRange("J".$rowCount,"M".$rowCount),$excel,$action_maintenance,"true",$ExWs);

		addContent(setRange("N".$rowCount,"N".$rowCount),$excel,$level,"true",$ExWs);
		
		
		
		
		
		if($page_counter==14){	
			$page_counter=1;	
			
			$rowCount+=14;
			
		
		}
		else {
			$page_counter++;
			$rowCount++;	
		}
	
	}
	

	save($ExWb,$excel,$newFilename); 	
	echo "CCDR has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";




}
?>