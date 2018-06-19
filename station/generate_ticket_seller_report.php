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

if(isset($_GET['ticket_seller'])){
	$db=retrieveDb();
	$ticket_seller=$_GET['ticket_seller'];
	$from_date=$_GET['from_date'];
	$to_date=$_GET['to_date'];

	
	if($from_date==$to_date){
		$date_label=date("F d, Y", strtotime($from_date));
		
	
	}
	else {
		$date_label="From ".date("F d",strtotime($from_date))." to ".date("F d, Y",strtotime($to_date));
	}
	
	
	$filename="Ticket Seller Report.xls";

	$oldfilename="forms/".$filename;
	$dateSlip=date("Y-m-d His");
	$newFilename="printout/TS Report_".$dateSlip.".xls";
	copy($oldfilename,$newFilename);
	
	
	$workSheetName="Ticket Seller Report";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");
	
	$ticket_seller_sql="select * from ticket_seller where id='".$ticket_seller."' limit 1";
	$ticket_seller_rs=$db->query($ticket_seller_sql);
		
	$ticket_seller_row=$ticket_seller_rs->fetch_assoc();	
	
	$last_name=$ticket_seller_row['last_name'];
	$first_name=$ticket_seller_row['first_name'];	
	
	$sql="select *,dar.id as dar_id from dar_daily inner join dar on dar_daily.id=dar.daily_id where date between '".$from_date." 00:00:00' and '".$to_date." 23:59:59' and ticket_seller='".$ticket_seller."' and sked_type='reg' order by date "; 
	$rs=$db->query($sql);

	$nm=$rs->num_rows;	

	addContent(setRange("A2","I2"),$excel,$date_label,"true",$ExWs);

	$rowCount=7;

	$recordCount=1;
	
	$start=$rowCount;
	$end=$rowCount+($nm-1);

	if($end<$start){ $end=$start; }
	
	$styleArray = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
			),
		),
	);
	


	addContent(setRange("B".$start,"B".$end),$excel,$last_name.", ".$first_name,"true",$ExWs);
	
	$excel->getActiveSheet()->getStyle("B".$start.":B".$end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle("B".$start.":B".$end)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	
	$excel->getActiveSheet()->getStyle("B".$start.":B".$end)->applyFromArray($styleArray);

	
	for($i=0;$i<$nm;$i++){
		$sjt="";
		$sjd="";
		$svt="";
		$svd="";
		
		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$recordCount,"true",$ExWs);
		
		$excel->getActiveSheet()->getStyle("A".$rowCount,"A".$rowCount)->applyFromArray($styleArray);

	
		$row=$rs->fetch_assoc();
		
		$sold_sql="select * from ticket_sold where dar_id='".$row['dar_id']."' limit 1";
		$sold_rs=$db->query($sold_sql);
		
		$sold_nm=$sold_rs->num_rows;
		
		if($sold_nm>0){
			$sold_row=$sold_rs->fetch_assoc();
			
			$sjt=$sold_row['sjt'];
			$sjd=$sold_row['sjd'];
			$svt=$sold_row['svt'];
			$svd=$sold_row['svd'];
		
		
		}		
		
	$ticket_error_sql="select ticket_error_daily.id as ticket_daily_id,ticket_error.id as ticket_error_id from ticket_error_daily inner join ticket_error on ticket_error_daily.id=ticket_error.ticket_daily_id where date='".$row['date']."' and station='".$row['station']."' and ticket_seller='".$ticket_seller."'";
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

		addContent(setRange("C".$rowCount,"C".$rowCount),$excel,date("m/d",strtotime($row['date'])),"true",$ExWs);
		addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$row['station'],"true",$ExWs);

		addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$sjt,"true",$ExWs);
		addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$sjd,"true",$ExWs);
		addContent(setRange("G".$rowCount,"G".$rowCount),$excel,$svt,"true",$ExWs);
		addContent(setRange("H".$rowCount,"H".$rowCount),$excel,$svd,"true",$ExWs);
		
		addContent(setRange("I".$rowCount,"I".$rowCount),$excel,$ticket_error_count,"true",$ExWs);
		

		
		$excel->getActiveSheet()->getStyle("C".$rowCount.":C".$rowCount)->applyFromArray($styleArray);
		$excel->getActiveSheet()->getStyle("D".$rowCount.":D".$rowCount)->applyFromArray($styleArray);
		$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->applyFromArray($styleArray);
		$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->applyFromArray($styleArray);
		$excel->getActiveSheet()->getStyle("G".$rowCount.":G".$rowCount)->applyFromArray($styleArray);
		$excel->getActiveSheet()->getStyle("H".$rowCount.":H".$rowCount)->applyFromArray($styleArray);
		$excel->getActiveSheet()->getStyle("I".$rowCount.":I".$rowCount)->applyFromArray($styleArray);

		
		$rowCount++;	
		$recordCount++;
	}	
	
	if($start==$rowCount){ $rowCount++; }
	
	addContent(setRange("A".$rowCount,"D".$rowCount),$excel,"Total","true",$ExWs);

	
	addContent(setRange("E".$rowCount,"E".$rowCount),$excel,"=sum(E".$start.":E".$end.")","true",$ExWs);
	addContent(setRange("F".$rowCount,"F".$rowCount),$excel,"=sum(F".$start.":F".$end.")","true",$ExWs);
	addContent(setRange("G".$rowCount,"G".$rowCount),$excel,"=sum(G".$start.":G".$end.")","true",$ExWs);
	addContent(setRange("H".$rowCount,"H".$rowCount),$excel,"=sum(H".$start.":H".$end.")","true",$ExWs);
	addContent(setRange("I".$rowCount,"I".$rowCount),$excel,"=sum(I".$start.":I".$end.")","true",$ExWs);
	
	
	$excel->getActiveSheet()->getStyle("A".$rowCount.":D".$rowCount)->applyFromArray($styleArray);
	$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->applyFromArray($styleArray);
	$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->applyFromArray($styleArray);
	$excel->getActiveSheet()->getStyle("G".$rowCount.":G".$rowCount)->applyFromArray($styleArray);
	$excel->getActiveSheet()->getStyle("H".$rowCount.":H".$rowCount)->applyFromArray($styleArray);
	$excel->getActiveSheet()->getStyle("I".$rowCount.":I".$rowCount)->applyFromArray($styleArray);
	
	$excel->getActiveSheet()->getStyle("A".$rowCount.":D".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	save($ExWb,$excel,$newFilename); 	
	echo "Ticket Seller Report has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";	
	
	
}	
?>	