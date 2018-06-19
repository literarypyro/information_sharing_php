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

	$year=$_POST['year'];
	$month=$_POST['month'];
	$day=$_POST['day'];

	$daily_date=date("Y-m-d",strtotime($year."-".$month."-".$day));	


	
	$filename="DR1.xls";

	$oldfilename="forms/".$filename;
	$dateSlip=date("Y-m-d His");
	$newFilename="printout/DR1_".$dateSlip.".xls";
	copy($oldfilename,$newFilename);
	
	
	$workSheetName="DR1";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");
	
	$rowCount=9;
	
	$sql="select * from station2 order by id";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	for($i=0;$i<$nm;$i++){	
		$row=$rs->fetch_assoc();

		$id_sql="select * from dar_daily where date='".$daily_date."' and station='".$station."'";
		$id_rs=$db->query($id_sql);
		
		$id_nm=$id_rs->num_rows;
		
		if($id_nm>0){
			$id_row=$id_rs->fetch_assoc();
			$daily_id=$id_row['id'];
		}	

		
		addContent(setRange("B".$rowCount,"B".$rowCount),$excel,$row['station_name'],"true",$ExWs);
		
		$ticket_sql="select * from sales_entry where slip_id='".$daily_id."'";
		$ticket_rs=$db->query($ticket_sql);

		$sjt_regular["qty"]="";
		$sjt_regular["amount"]="";
		$svt_regular["qty"]="";
		$svt_regular["amount"]="";

		
		$ticket_nm=$ticket_rs->num_rows;
		for($k=0;$k<$ticket_nm;$k++){
			$ticket_row=$ticket_rs->fetch_assoc();
			
				
			if($ticket_row['type']=="sj"){
				$sjt_regular["qty"]=$ticket_row['reg_sold'];
				$sjt_regular["amount"]=$ticket_row['reg_amount'];

			}
			
			
		}
		
		
		if($daily_id==""){
			addContent(setRange("C".$rowCount,"C".$rowCount),$excel,$sjt_regular("qty"),"true",$ExWs);
			addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$sjt_regular("amount") ,"true",$ExWs);
		}
		else {
		
		}
	
		$rowCount++;
	}
	
	save($ExWb,$excel,$newFilename); 	
	echo "DR1 Report has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";	
?>
