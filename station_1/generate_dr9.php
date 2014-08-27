<?php
session_start();
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");

?>
<?php
	
	$db=new mysqli("localhost","root","","station");
	
	
	$year=$_SESSION['year'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];	
	$daily_date=date("Y-m-d",strtotime($year."-".$month."-".$day));	

	$filename="DR9.xls";

	$oldfilename="forms/".$filename;
	$dateSlip=date("Y-m-d His");
	$newFilename="printout/DR9_".$dateSlip.".xls";
	copy($oldfilename,$newFilename);
	
	$workSheetName="DR9";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");

	
	$sql="select * from equipment_incident where date='".$daily_date."'";
	$rs=$db->query($sql);
	
	$nm=$rs->num_rows;
	
	$rowCount=7;
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$reported_cc=$row['time_cc'];
		$station=$row['station'];
		$problem=$row['details'];
		
		$cc_date=date("m/d/Y",strtotime($reported_cc));
		$cc_tt=date("Hi",strtotime($reported_cc));
		
		
		$issued_sql="select * from issued_no where e_incident_id='".$row['id']."' limit 1";
		$issued_rs=$db->query($issued_sql);
		
		$issued_nm=$issued_rs->num_rows;
		if($issued_nm>0){
			$issued_row=$issued_rs->fetch_assoc();
			
			$record_no=$issued_row['record'];
			$incident_no=$issued_row['incident_cdc'];
			
		
		}
		
		$reported_to_sql="select * from reported_to where e_incident_id='".$row['id']."' limit 1";
		$reported_to_rs=$db->query($reported_to_sql);
		
		$reported_to_nm=$reported_to_rs->num_rows;
		if($reported_to_nm>0){
			$reported_to_row=$reported_to_rs->fetch_assoc();

			$reported_office=$reported_to_row['office'];
			$reported_personnel=$reported_to_row['personnel'];
		
		}	
	
		$repair_sql="select * from repair where e_incident_id='".$row['id']."' limit 1";
		$repair_rs=$db->query($repair_sql);
		
		$repair_nm=$repair_rs->num_rows;
		if($repair_nm>0){
			$repair_row=$repair_rs->fetch_assoc();	
			
			$repair_time=$repair_row['repair_time'];
			$repair_date=date("m/d/Y",strtotime($repair_time));
			$repair_tt=date("Hi",strtotime($repair_time));
			$repair_no=$repair_row['repair_no'];
			
			$repair_remarks=$repair_row['remarks_repair'];
		
		
		}	
		
		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$station,"true",$ExWs);
		addContent(setRange("B".$rowCount,"B".$rowCount),$excel,$cc_date,"true",$ExWs);
		addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$cc_tt,"true",$ExWs);
		
		addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$problem,"true",$ExWs);
		
		addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$record_no,"true",$ExWs);
		addContent(setRange("G".$rowCount,"G".$rowCount),$excel,$incident_no,"true",$ExWs);
	
		addContent(setRange("H".$rowCount,"H".$rowCount),$excel,$reported_office,"true",$ExWs);
		addContent(setRange("I".$rowCount,"I".$rowCount),$excel,$reported_personnel,"true",$ExWs);

		addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$repair_date,"true",$ExWs);
		addContent(setRange("K".$rowCount,"K".$rowCount),$excel,$repair_tt,"true",$ExWs);
		addContent(setRange("L".$rowCount,"L".$rowCount),$excel,$repair_no,"true",$ExWs);
		addContent(setRange("M".$rowCount,"M".$rowCount),$excel,$repair_remarks,"true",$ExWs);
	
	
		$rowCount++;
	}	

	save($ExWb,$excel,$newFilename); 	
	echo "DR9 Report has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";	
	
	
?>