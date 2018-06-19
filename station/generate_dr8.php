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


	
	$filename="DR8.xls";

	$oldfilename="forms/".$filename;
	$dateSlip=date("Y-m-d His");
	$newFilename="printout/DR8_".$dateSlip.".xls";
	copy($oldfilename,$newFilename);
	
	
	$workSheetName="DR1";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");


	addContent(setRange("A2","G2"),$excel,date("l, F d, Y",strtotime($daily_date)),"true",$ExWs);


	
	$rowCount=8;
	
	$sql="select * from station2 order by id";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	for($i=0;$i<$nm;$i++){	
		$row=$rs->fetch_assoc();
		$station=$row['id'];
		$daily_id="";
		$id_sql="select * from sales_summary where date='".$daily_date."' and station='".$station."'";

		$id_rs=$db->query($id_sql);
		
		$id_nm=$id_rs->num_rows;
		
		if($id_nm>0){
			$id_row=$id_rs->fetch_assoc();
			$daily_id=$id_row['id'];
		}	
		
		$entry="";
		$exit="";
		
//		addContent(setRange("B".$rowCount,"B".$rowCount),$excel,$row['station_name'],"true",$ExWs);
		
		
		$ee_sql="select * from sales_ee where slip_id='".$daily_id."' limit 1";
		$ee_rs=$db->query($ee_sql);
		
		$ee_nm=$ee_rs->num_rows;
		if($ee_nm>0){
			$ee_row=$ee_rs->fetch_assoc();
			$entry=$ee_row['ticket_entry'];
			$exit=$ee_row['ticket_exit'];
			
		}
		
		
		if($daily_id==""){
		}
		else {
			addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$entry,"true",$ExWs);
			addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$exit ,"true",$ExWs);

			
		}
	
		$rowCount++;
	}
	
	save($ExWb,$excel,$newFilename); 	
	$newFilename2=str_replace('xls','html',$newFilename);
	saveHTML($ExWb,$excel,$newFilename2); 	

	echo "DR8 Report has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";	
	echo "<br>";
	echo "DR8 (HTML) Report has been generated!  Press right click and Save As: <a href='".$newFilename2."'>Here</a>";	

?>
