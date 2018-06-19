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
		$station=$row['id'];
		$daily_id="";
		$id_sql="select * from sales_summary where date='".$daily_date."' and station='".$station."'";

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

		$sjt_discount["qty"]="";
		$sjt_discount["amount"]="";
		$svt_discount["qty"]="";
		$svt_discount["amount"]="";
		
		$entry="";
		$exit="";
		
		
		
		$ticket_nm=$ticket_rs->num_rows;
		for($k=0;$k<$ticket_nm;$k++){
			$ticket_row=$ticket_rs->fetch_assoc();
			
				
			if($ticket_row['type']=="sj"){
				
				$sjt_regular["qty"]=$ticket_row['reg_sold'];
				$sjt_regular["amount"]=$ticket_row['reg_amount'];

				$sjt_discount["qty"]=$ticket_row['disc_sold'];
				$sjt_discount["amount"]=$ticket_row['disc_amount'];
				
			}
			else if($ticket_row['type']=="sv"){
				
				$svt_regular["qty"]=$ticket_row['reg_sold'];
				$svt_regular["amount"]=$ticket_row['reg_amount'];

				$svt_discount["qty"]=$ticket_row['disc_sold'];
				$svt_discount["amount"]=$ticket_row['disc_amount'];

			}			
			
		}
		


		$ticket_sql="select * from sales_refund where slip_id='".$daily_id."'";
		$ticket_rs=$db->query($ticket_sql);


		$sjt_regular["refund_qty"]="";
		$sjt_regular["refund_amount"]="";
		$svt_regular["refund_qty"]="";
		$svt_regular["refund_amount"]="";

		$sjt_discount["refund_qty"]="";
		$sjt_discount["refund_amount"]="";
		$svt_discount["refund_qty"]="";
		$svt_discount["refund_amount"]="";

		
		$ticket_nm=$ticket_rs->num_rows;
		for($k=0;$k<$ticket_nm;$k++){
			$ticket_row=$ticket_rs->fetch_assoc();
			
				
			if($ticket_row['type']=="sj"){
				
				$sjt_regular["refund_qty"]=$ticket_row['reg_ticket_refund'];
				$sjt_regular["refund_amount"]=$ticket_row['reg_amount_refund'];

				$sjt_discount["refund_qty"]=$ticket_row['disc_ticket_refund'];
				$sjt_discount["refund_amount"]=$ticket_row['disc_amount_refund'];
				
			}
			else if($ticket_row['type']=="sv"){
				
				$svt_regular["refund_qty"]=$ticket_row['reg_ticket_refund'];
				$svt_regular["refund_amount"]=$ticket_row['reg_amount_refund'];

				$svt_discount["refund_qty"]=$ticket_row['disc_ticket_refund'];
				$svt_discount["refund_amount"]=$ticket_row['disc_amount_refund'];


			}			
			
		}

		$sjt_regular["unsold_qty"]="";	
		$sjt_regular["unsold_amount"]="";	

		$ticket_sql="select * from sales_unsold where slip_id='".$daily_id."'";
		$ticket_rs=$db->query($ticket_sql);
		
		$ticket_nm=$ticket_rs->num_rows;
		for($k=0;$k<$ticket_nm;$k++){
			$ticket_row=$ticket_rs->fetch_assoc();
			
				
			if($ticket_row['type']=="sj"){
				$sjt_regular["unsold_qty"]=$ticket_row['reg_ticket_unsold'];
				$sjt_regular["unsold_amount"]=$ticket_row['reg_amount_unsold'];
		
			}
		}
		

		$ee_sql="select * from sales_ee where slip_id='".$daily_id."' limit 1";
		$ee_rs=$db->query($ee_sql);
		
		$ee_nm=$ee_rs->num_rows;
		if($ee_nm>0){
			$ee_row=$ee_rs->fetch_assoc();
			$total_exit=$ee_row['ticket_entry']*1+$ee_row['ticket_exit']*1;
			
		}
		
		
		if($daily_id==""){
		}
		else {
			addContent(setRange("C".$rowCount,"C".$rowCount),$excel,$sjt_regular["qty"],"true",$ExWs);
			addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$sjt_regular["amount"] ,"true",$ExWs);

			addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$sjt_regular["refund_qty"],"true",$ExWs);
			addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$sjt_regular["refund_amount"] ,"true",$ExWs);
			
			addContent(setRange("G".$rowCount,"G".$rowCount),$excel,$sjt_regular["unsold_qty"],"true",$ExWs);
			addContent(setRange("H".$rowCount,"H".$rowCount),$excel,$sjt_regular["unsold_amount"] ,"true",$ExWs);
			
			addContent(setRange("I".$rowCount,"I".$rowCount),$excel,$sjt_discount["qty"],"true",$ExWs);
			addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$sjt_discount["amount"] ,"true",$ExWs);

			
			addContent(setRange("K".$rowCount,"K".$rowCount),$excel,$sjt_discount["refund_qty"],"true",$ExWs);
			addContent(setRange("L".$rowCount,"L".$rowCount),$excel,$sjt_discount["refund_amount"] ,"true",$ExWs);
			
			

			addContent(setRange("M".$rowCount,"M".$rowCount),$excel,$svt_regular["qty"],"true",$ExWs);
			addContent(setRange("N".$rowCount,"N".$rowCount),$excel,$svt_regular["amount"] ,"true",$ExWs);


			addContent(setRange("O".$rowCount,"O".$rowCount),$excel,$svt_regular["refund_qty"],"true",$ExWs);
			addContent(setRange("P".$rowCount,"P".$rowCount),$excel,$svt_regular["refund_amount"] ,"true",$ExWs);


			addContent(setRange("Q".$rowCount,"Q".$rowCount),$excel,$svt_discount["qty"],"true",$ExWs);
			addContent(setRange("R".$rowCount,"R".$rowCount),$excel,$svt_discount["amount"] ,"true",$ExWs);

			addContent(setRange("S".$rowCount,"S".$rowCount),$excel,$svt_discount["refund_qty"],"true",$ExWs);
			addContent(setRange("T".$rowCount,"T".$rowCount),$excel,$svt_discount["refund_amount"] ,"true",$ExWs);
		
			
			addContent(setRange("X".$rowCount,"X".$rowCount),$excel,$total_exit ,"true",$ExWs);

			
		}
	
		$rowCount++;
	}
	
	save($ExWb,$excel,$newFilename); 
	$newFilename2=str_replace('xls','html',$newFilename);
	saveHTML($ExWb,$excel,$newFilename2); 	

	
	echo "DR1 Report has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";	
	echo "<br>";
	echo "DR1 (HTML) Report has been generated!  Press right click and Save As: <a href='".$newFilename2."'>Here</a>";	
	
?>
