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
$db=new mysqli("localhost","root","","transport");

if(isset($_GET['year'])){
	$year=$_GET['year'];
	$level=$_GET['level'];

	$filename="Statistics Report 2.xls";

	$oldfilename="forms/".$filename;
	$dateSlip=date("Y-m-d His");
	$newFilename="printout/Stats Report_".$dateSlip.".xls";
	copy($oldfilename,$newFilename);

	$workSheetName="Statistics Report";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");


	
	$db=new mysqli("localhost","root","","transport");
	$sql="select * from equipment where id in ('114','102','110','11','113','104','108','109','103','124','67','111','112','105','81','118','119','64','115','89','120','123','121','116','2','122','117','105','81','118','119','64','115','89','120','123','121','116','2','122','117') order by equipment_name";

	$rs=$db->query($sql);

	$nm=$rs->num_rows;




	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();

		$equipt[$i]['id']=$row['id'];
		$equipt[$i]['equipment']=$row['equipment_name'];
		for ($k=1;$k<=12;$k++){
			$equipt_count["Equipt_".$row['id']]["Month_".$k]=0;
			
		}
	}
	



	$rowCount=4;
			$styleArray = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
					),
				),
			);	
			$styleArray2 = array(
				'borders' => array(
					'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
					),
				),
			);	
	for($k=1;$k<=12;$k++){
		$prefix=chr((65*1+$k));
		$excel->getActiveSheet()->getStyle($prefix.$rowCount.":".$prefix.$rowCount)->applyFromArray($styleArray);


		addContent(setRange($prefix.$rowCount,$prefix.$rowCount),$excel,date("F",strtotime(date("Y")."-".$k."-01")),"true",$ExWs);

		

		
	}
	
	
	for($i=1;$i<=12;$i++){	
		$month_heading=date("F",strtotime($year."-".$i."-01"));
		$date_limit=date("t",strtotime($year."-".$i."-01"));
		
		$start_date=date("Y-m-d",strtotime($year."-".$i."-01"));
		$end_date=date("Y-m-d",strtotime($year."-".$i."-".$date_limit));
		
		$sql="select *,count(1) as equipt_count from incident_report where level='".$level."' and incident_date between '".$start_date." 00:00:00' and '".$end_date." 23:59:59' and equipt in ('114','102','110','11','113','104','108','109','103','124','67','111','112') group by equipt";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		
		for($k=0;$k<$nm;$k++){
			$row=$rs->fetch_assoc();
			$equipt_count["Equipt_".$row['equipt']]["Month_".$i]=$row['equipt_count'];
			
		}

		$sql="select *,count(1) as equipt_count from incident_report inner join external.incident_defects on incident_report.id=external.incident_defects.incident_id where level='".$level."' and incident_date between '".$start_date." 00:00:00' and '".$end_date." 23:59:59' and external.incident_defects.equipt_id in ('114','102','110','11','113','104','108','109','103','124','67','111','112') group by equipt"; 
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		
		for($k=0;$k<$nm;$k++){
			$row=$rs->fetch_assoc();
			$equipt_count["Equipt_".$row['equipt_id']]["Month_".$i]+=$row['equipt_count'];
		}		

	}



	$sql="select * from equipment where id in ('114','102','110','11','113','104','108','109','103','124','67','111','112','105','81','118','119','64','115','89','120','123','121','116','2','122','117','105','81','118','119','64','115','89','120','123','121','116','2','122','117') order by equipment_name";

	$rs=$db->query($sql);

	$nm=$rs->num_rows;


	$rowCount++;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();

		$prefix="A";
		$excel->getActiveSheet()->getStyle($prefix.$rowCount.":".$prefix.$rowCount)->applyFromArray($styleArray2);


		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$row['equipment_name'],"true",$ExWs);

		for($k=1;$k<=12;$k++){
			$prefix=chr((65*1+$k));
			
			$excel->getActiveSheet()->getStyle($prefix.$rowCount.":".$prefix.$rowCount)->applyFromArray($styleArray2);
			
			addContent(setRange($prefix.$rowCount,$prefix.$rowCount),$excel,$equipt_count["Equipt_".$equipt[$i]['id']]["Month_".$k],"true",$ExWs);
		

		}


		$rowCount++;	
	}















	saveProtected($ExWb,$excel,$newFilename); 	
	echo "Statistics Report has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";




}
?>
