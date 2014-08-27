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

	$filename="DR5.xls";

	$oldfilename="forms/".$filename;
	$dateSlip=date("Y-m-d His");
	$newFilename="printout/DR5_".$dateSlip.".xls";
	copy($oldfilename,$newFilename);
	
	
	
	
	/*
	$sql="select * from station order by id";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;	
	*/
	
	
	$workSheetName="DR5";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");


	$decorum_sql="SELECT *,decorum_person.id as decor_person FROM decorum_person inner join decorum_daily on daily_id=decorum_daily.id where date='".$daily_date."' order by station";
		

	$decorum_rs=$db->query($decorum_sql);
	$nm=$decorum_rs->num_rows;

	
	$rowCount=24;
	for($i=0;$i<$nm;$i++){	
		for($n=1;$n<=12;$n++){
			$decorum[$n]="";
		}	
		$decorum_row=$decorum_rs->fetch_assoc();
	
		$decor_person=$decorum_row['decor_person'];
		$decor_name=$decorum_row['name'];
		$decor_position=$decorum_row['position'];
		$decor_shift=$decorum_row['shift'];
		$decor_station=$decorum_row['station'];
		$decor_remarks=$decorum_row['remarks'];	
	
		$decorum_violation="select * from decorum_violation where d_person_id='".$decor_person."'";
		$decorum_violation_rs=$db->query($decorum_violation);
		
		$decorum_violation_nm=$decorum_violation_rs->num_rows;

		for($n=0;$n<$decorum_violation_nm;$n++){
			$decorum_violation_row=$decorum_violation_rs->fetch_assoc();
			
			$decorum[$decorum_violation_row['item_id']]="X";	
		
		}

		addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$decor_name,"true",$ExWs);
		addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$decor_position,"true",$ExWs);
		addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$decor_station,"true",$ExWs);
		addContent(setRange("G".$rowCount,"G".$rowCount),$excel,$decor_shift,"true",$ExWs);
		

		addContent(setRange("H".$rowCount,"H".$rowCount),$excel,$decorum[1],"true",$ExWs);
		addContent(setRange("I".$rowCount,"I".$rowCount),$excel,$decorum[2],"true",$ExWs);
		addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$decorum[3],"true",$ExWs);
		addContent(setRange("K".$rowCount,"K".$rowCount),$excel,$decorum[4],"true",$ExWs);


		addContent(setRange("L".$rowCount,"L".$rowCount),$excel,$decorum[5],"true",$ExWs);
		addContent(setRange("M".$rowCount,"M".$rowCount),$excel,$decorum[6],"true",$ExWs);
		addContent(setRange("N".$rowCount,"N".$rowCount),$excel,$decorum[7],"true",$ExWs);
		addContent(setRange("O".$rowCount,"O".$rowCount),$excel,$decorum[8],"true",$ExWs);
		
		addContent(setRange("P".$rowCount,"P".$rowCount),$excel,$decorum[9],"true",$ExWs);
		addContent(setRange("Q".$rowCount,"Q".$rowCount),$excel,$decorum[10],"true",$ExWs);
		addContent(setRange("R".$rowCount,"R".$rowCount),$excel,$decorum[11],"true",$ExWs);
		addContent(setRange("S".$rowCount,"S".$rowCount),$excel,$decorum[12],"true",$ExWs);

		addContent(setRange("T".$rowCount,"T".$rowCount),$excel,$decor_remarks,"true",$ExWs);
		
		
		$rowCount++;
	}	
	
	
	save($ExWb,$excel,$newFilename); 	
	echo "DR5 Report has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";	
	
	
?>