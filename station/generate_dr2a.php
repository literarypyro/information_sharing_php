<?php
session_start();
?>
<?php
require("db.php");
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");

?>
<?php
	
	$db=retrieveDb();	
	
	$year=$_SESSION['year'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];	
	$daily_date=date("Y-m-d",strtotime($year."-".$month."-".$day));	

	$filename="DR2A.xls";

	$oldfilename="forms/".$filename;
	$dateSlip=date("Y-m-d His");
	$newFilename="printout/DR2A_".$dateSlip.".xls";
	copy($oldfilename,$newFilename);
	
	$sql="select * from station order by id";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;	

	$workSheetName="DR2A";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");


	
	$rowCount=5;
	for($i=0;$i<$nm;$i++){	
		$row=$rs->fetch_assoc();
		$station=$row['id'];
		$daily_id="";
		
		$id_sql="select * from ticket_error_daily where date='".$daily_date."' and station='".$station."'";

		$id_rs=$db->query($id_sql);
		
		$id_nm=$id_rs->num_rows;
		
		$teemr_no="";
		
		$receiving_ca="";
		
		if($id_nm>0){
			$id_row=$id_rs->fetch_assoc();
			$daily_id=$id_row['id'];
			
			$teemr_no=$id_row['no_of_teemr'];
			
			$ca_sql="select * from cash_assistant where username='".$id_row['receiving_ca']."' limit 1";
			$ca_rs=$db->query($ca_sql);
			$ca_nm=$ca_rs->num_rows;
			
			if($ca_nm>0){
				$ca_row=$ca_rs->fetch_assoc();
				
				$receiving_ca=substr($ca_row['firstName'],0,1).". ".$ca_row['lastName'];
			
			}
			
			
			
			
			
		}				

		$teemr="select * from teemr_error_entry where ticket_daily_id='".$daily_id."' and error_code like 'JC%%' ";
		$teemr_rs=$db->query($teemr);
		$teemr_nm=$teemr_rs->num_rows;
		
		$jc_error=$teemr_nm;
		
		$teemr="select * from teemr_error_entry where ticket_daily_id='".$daily_id."' and error_code='MTC' ";
		$teemr_rs=$db->query($teemr);
		
		$teemr_nm=$teemr_rs->num_rows;
		
		$mtc_error=$teemr_nm;
		
		if($daily_id==""){
		}
		else {
			addContent(setRange("B".$rowCount,"B".$rowCount),$excel,$jc_error,"true",$ExWs);
			addContent(setRange("C".$rowCount,"C".$rowCount),$excel,$mtc_error,"true",$ExWs);

			addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$teemr_no,"true",$ExWs);
			addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$receiving_ca,"true",$ExWs);
			
		}	
		
		
		$rowCount++;
	}	
	
	
	save($ExWb,$excel,$newFilename); 	
	$newFilename2=str_replace('xls','html',$newFilename);
	saveHTML($ExWb,$excel,$newFilename2); 	
	
	echo "DR2A Report has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";	
	echo "<br>";
	echo "DR2A (HTML) Report has been generated!  Press right click and Save As: <a href='".$newFilename2."'>Here</a>";	
	
	
?>