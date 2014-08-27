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
	
	$tickets=array("sjt","sjd","svt","svd");
	
	$error_sql="select * from errors limit 9";
	$error_rs=$db->query($error_sql);
	$error_nm=$error_rs->num_rows;
	

	
	$year=$_SESSION['year'];
	$month=$_SESSION['month'];
	$day=$_SESSION['day'];

	$daily_date=date("Y-m-d",strtotime($year."-".$month."-".$day));	


	
	$filename="Reasons for Refund.xls";

	$oldfilename="forms/".$filename;
	$dateSlip=date("Y-m-d His");
	$newFilename="printout/Refund_Reason_".$dateSlip.".xls";
	copy($oldfilename,$newFilename);
	
	
	$workSheetName="Refund Reason";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");

	$rowCount=5;
	
	$sql="select * from station2 order by id";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	for($i=0;$i<$nm;$i++){	
		$row=$rs->fetch_assoc();
		$station=$row['id'];
		$daily_id="";
		$id_sql="select * from refund_daily where date='".$daily_date."' and station='".$station."'";

		$id_rs=$db->query($id_sql);
		
		$id_nm=$id_rs->num_rows;
		
		if($id_nm>0){
			$id_row=$id_rs->fetch_assoc();
			$daily_id=$id_row['id'];
		}	


		for($n=0;$n<$error_nm;$n++){
			$error_row=$error_rs->fetch_assoc();
			$error[$n]=$error_row['code'];
		}
		
		
		for($m=0;$m<count($tickets);$m++){
			for($n=0;$n<count($error);$n++){
				$refund_ticket[$tickets[$m]][$error[$n]]="0";
				$refund_ticket[$tickets[$m]]['amount']=0;
			}
		}		
		
		$refund_view="select * from refund_view where refund_daily_id='".$daily_id."'";
		$refund_view_rs=$db->query($refund_view);
		
		$refund_view_nm=$refund_view_rs->num_rows;
		
		
		
		for($k=0;$k<$refund_view_nm;$k++){
			$refund_view_row=$refund_view_rs->fetch_assoc();
			$refund_ticket[$refund_view_row['ticket_type']]['amount']+=$refund_view_row['refund_amount']*1;
			
			$refund_ticket[$refund_view_row['ticket_type']][$refund_view_row['reason']]=$refund_view_row['ticket_count'];
			
		}
			
		
		
		
		if($daily_id==""){
		}
		else {
			addContent(setRange("C".$rowCount,"C".$rowCount),$excel,$refund_ticket['sjt']['JC0003']*1,"true",$ExWs);
			addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$refund_ticket['sjt']['JC0004']*1,"true",$ExWs);
			addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$refund_ticket['sjt']['JC0005']*1,"true",$ExWs);
			addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$refund_ticket['sjt']['JC0006']*1,"true",$ExWs);
			addContent(setRange("G".$rowCount,"G".$rowCount),$excel,$refund_ticket['sjt']['JC0007']*1,"true",$ExWs);
			addContent(setRange("H".$rowCount,"H".$rowCount),$excel,$refund_ticket['sjt']['JC0008']*1,"true",$ExWs);
			addContent(setRange("I".$rowCount,"I".$rowCount),$excel,$refund_ticket['sjt']['TD/SI']*1,"true",$ExWs);
			addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$refund_ticket['sjt']['NRED']*1,"true",$ExWs);
			addContent(setRange("K".$rowCount,"K".$rowCount),$excel,$refund_ticket['sjt']['DRT']*1,"true",$ExWs);

			addContent(setRange("M".$rowCount,"M".$rowCount),$excel,$refund_ticket['sjt']['amount']*1,"true",$ExWs);
			
			
			addContent(setRange("N".$rowCount,"N".$rowCount),$excel,$refund_ticket['sjd']['JC0003']*1,"true",$ExWs);
			addContent(setRange("O".$rowCount,"O".$rowCount),$excel,$refund_ticket['sjd']['JC0004']*1,"true",$ExWs);
			addContent(setRange("P".$rowCount,"P".$rowCount),$excel,$refund_ticket['sjd']['JC0005']*1,"true",$ExWs);
			addContent(setRange("Q".$rowCount,"Q".$rowCount),$excel,$refund_ticket['sjd']['JC0006']*1,"true",$ExWs);
			addContent(setRange("R".$rowCount,"R".$rowCount),$excel,$refund_ticket['sjd']['JC0007']*1,"true",$ExWs);
			addContent(setRange("S".$rowCount,"S".$rowCount),$excel,$refund_ticket['sjd']['JC0008']*1,"true",$ExWs);
			addContent(setRange("T".$rowCount,"T".$rowCount),$excel,$refund_ticket['sjd']['TD/SI']*1,"true",$ExWs);
			addContent(setRange("U".$rowCount,"U".$rowCount),$excel,$refund_ticket['sjd']['NRED']*1,"true",$ExWs);
			addContent(setRange("V".$rowCount,"V".$rowCount),$excel,$refund_ticket['sjd']['DRT']*1,"true",$ExWs);

			addContent(setRange("X".$rowCount,"X".$rowCount),$excel,$refund_ticket['sjd']['amount']*1,"true",$ExWs);
			
			
			addContent(setRange("Z".$rowCount,"Z".$rowCount),$excel,$refund_ticket['svt']['JC0003']*1,"true",$ExWs);
			addContent(setRange("AA".$rowCount,"AA".$rowCount),$excel,$refund_ticket['svt']['JC0004']*1,"true",$ExWs);
			addContent(setRange("AB".$rowCount,"AB".$rowCount),$excel,$refund_ticket['svt']['JC0005']*1,"true",$ExWs);
			addContent(setRange("AC".$rowCount,"AC".$rowCount),$excel,$refund_ticket['svt']['JC0006']*1,"true",$ExWs);
			addContent(setRange("AD".$rowCount,"AD".$rowCount),$excel,$refund_ticket['svt']['JC0007']*1,"true",$ExWs);
			addContent(setRange("AE".$rowCount,"AE".$rowCount),$excel,$refund_ticket['svt']['JC0008']*1,"true",$ExWs);
			addContent(setRange("AF".$rowCount,"AF".$rowCount),$excel,$refund_ticket['svt']['TD/SI']*1,"true",$ExWs);
			addContent(setRange("AG".$rowCount,"AG".$rowCount),$excel,$refund_ticket['svt']['NRED']*1,"true",$ExWs);
			addContent(setRange("AH".$rowCount,"AH".$rowCount),$excel,$refund_ticket['svt']['DRT']*1,"true",$ExWs);

			addContent(setRange("AJ".$rowCount,"AJ".$rowCount),$excel,$refund_ticket['svt']['amount']*1,"true",$ExWs);
			

			addContent(setRange("AK".$rowCount,"AK".$rowCount),$excel,$refund_ticket['svd']['JC0003']*1,"true",$ExWs);
			addContent(setRange("AL".$rowCount,"AL".$rowCount),$excel,$refund_ticket['svd']['JC0004']*1,"true",$ExWs);
			addContent(setRange("AM".$rowCount,"AM".$rowCount),$excel,$refund_ticket['svd']['JC0005']*1,"true",$ExWs);
			addContent(setRange("AN".$rowCount,"AN".$rowCount),$excel,$refund_ticket['svd']['JC0006']*1,"true",$ExWs);
			addContent(setRange("AO".$rowCount,"AO".$rowCount),$excel,$refund_ticket['svd']['JC0007']*1,"true",$ExWs);
			addContent(setRange("AP".$rowCount,"AP".$rowCount),$excel,$refund_ticket['svd']['JC0008']*1,"true",$ExWs);
			addContent(setRange("AQ".$rowCount,"AQ".$rowCount),$excel,$refund_ticket['svd']['TD/SI']*1,"true",$ExWs);
			addContent(setRange("AR".$rowCount,"AR".$rowCount),$excel,$refund_ticket['svd']['NRED']*1,"true",$ExWs);
			addContent(setRange("AS".$rowCount,"AS".$rowCount),$excel,$refund_ticket['svd']['DRT']*1,"true",$ExWs);
			
			addContent(setRange("AU".$rowCount,"AU".$rowCount),$excel,$refund_ticket['svd']['amount']*1,"true",$ExWs);
		
		
			
		}
	
		$rowCount++;
	}
	
	save($ExWb,$excel,$newFilename); 	
	echo "Reasons for Refund Report has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";	
?>
