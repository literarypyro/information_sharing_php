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


	$filename="DR2C.xls";

	$oldfilename="forms/".$filename;
	$dateSlip=date("Y-m-d His");
	$newFilename="printout/DR2C_".$dateSlip.".xls";
	copy($oldfilename,$newFilename);
	$workbookname=$newFilename;
		
	$excel=loadExistingWorkbook($workbookname);

//  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");	

	$ExWs=setActiveWorksheet($excel,"",1);

	
	$sql="select * from station order by id";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;		
	
	$rowCount=4;
	
	$total_sjd=0;
	$total_svd=0;
	
	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$station=$row['id'];
		$manual_sql="SELECT count(1) as ticket_count,type FROM manually_collected inner join collected_daily on c_daily_id=collected_daily.id where date='".$daily_date."' and station='".$station."' group by type order by shift";
		
		$manual_rs=$db->query($manual_sql);
		$manual_nm=$manual_rs->num_rows;
		
		
		
		$sjt=0;
		$sjd=0;
		$svt=0;
		$svd=0;		
		
		for($k=0;$k<$manual_nm;$k++){
			$manual_row=$manual_rs->fetch_assoc();
			$type=$manual_row['type'];
			
			$ticket_count=$manual_row['ticket_count'];
			
			if($type=="sjt"){
				$sjt=$ticket_count;
			}
			else if($type=="sjd"){
				$sjd=$ticket_count;
			}
			else if($type=="svt"){
				$svt=$ticket_count;
			}
			else if($type=="svd"){
				$svd=$ticket_count;
			}
				
		}
		
		$disc_label="'".$sjd."/".$svd;
		
		$total_sjd+=$sjd*1;
		
		$total_svd+=$svd*1;
		
	
		$manually_data_sql="select * from manually_collected_by_station inner join cash_assistant on cash_assistant=cash_assistant.username where collected_date='".$daily_date."' and station='".$station."'";

		$manually_data_rs=$db->query($manually_data_sql);
		$manually_data_nm=$manually_data_rs->num_rows;
		
		$manual_ca="";
		$manual_no_forms="";
		
		
		if($manually_data_nm>0){
			$manually_data_row=$manually_data_rs->fetch_assoc();
	
			$manual_ca=substr($manually_data_row['firstName'],0,1).". ".$manually_data_row['lastName'];
			$manual_no_forms=$manually_data_row['no_of_forms'];
	
		}
		addContent(setRange("C".$rowCount,"C".$rowCount),$excel,$disc_label,"true",$ExWs);
		addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$sjt,"true",$ExWs);
		addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$svt,"true",$ExWs);
		
		addContent(setRange("F".$rowCount,"H".$rowCount),$excel,$manual_no_forms,"true",$ExWs);
		addContent(setRange("I".$rowCount,"K".$rowCount),$excel,$manual_ca,"true",$ExWs);



		
		$rowCount++;
		
		
	}
	$disc_label="'".$total_sjd."/".$total_svd;
	
	addContent(setRange("C17","C18"),$excel,$disc_label,"true",$ExWs);
		
	
	
	
	$ExWs=setActiveWorksheet($excel,"",0);	
	
	
	$sql="select * from station order by id";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;		

	$rowCount=4;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$dar_sql="select * from dar_daily where station='".$row['id']."' and date='".$daily_date."' limit 1";
		$dar_rs=$db->query($dar_sql);
		$dar_nm=$dar_rs->num_rows;
		
		$station_name=$row['station_name'];
		$start=4;
		if($dar_nm>0){

			$dar_row=$dar_rs->fetch_assoc();
			$dar_ts_sql="select * from dar where daily_id='".$dar_row['id']."'";	
			
			$dar_ts_rs=$db->query($dar_ts_sql);
			$dar_ts_nm=$dar_ts_rs->num_rows;
			
			if($dar_ts_nm>0){
				
				$unreg["sjt"]["shortage"]=0;
				$unreg["sjd"]["shortage"]=0;
				$unreg["svt"]["shortage"]=0;
				$unreg["svd"]["shortage"]=0;

				$unreg["sjt"]["overage"]=0;
				$unreg["sjd"]["overage"]=0;
				$unreg["svt"]["overage"]=0;
				$unreg["svd"]["overage"]=0;


				
				$paid["sjt"]["shortage"]=0;
				$paid["sjd"]["shortage"]=0;
				$paid["svt"]["shortage"]=0;
				$paid["svd"]["shortage"]=0;
				
				$paid["sjt"]["overage"]=0;
				$paid["sjd"]["overage"]=0;
				$paid["svt"]["overage"]=0;
				$paid["svd"]["overage"]=0;


					$styleArray = array(
						'borders' => array(
							'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
							),
						),
					);

				for($k=0;$k<$dar_ts_nm;$k++){	
					$dar_ts_row=$dar_ts_rs->fetch_assoc();
					
					$ts_sql="select last_name,first_name from ticket_seller where id='".$dar_ts_row['ticket_seller']."' limit 1";
					$ts_rs=$db->query($ts_sql);
					$ts_row=$ts_rs->fetch_assoc();
					$remarks="";
					$remarks=$dar_ts_row['remarks'];
					
					
					$ticket_seller=$ts_row['last_name'].", ".$ts_row['first_name'];
					
					if($k==0){
						$station_start=$rowCount;
						
					
					}

					addContent(setRange("B".$rowCount,"B".$rowCount),$excel,$ticket_seller,"true",$ExWs);
					
					addContent(setRange("C".$rowCount,"C".$rowCount),$excel,$dar_ts_row['remitted'],"true",$ExWs);
					
					$tis_sql="select * from ticket_sold where dar_id='".$dar_ts_row['id']."' limit 1";
					$tis_rs=$db->query($tis_sql);
					$tis_nm=$tis_rs->num_rows;
					
					$sjt_sold="";
					$sjd_sold="";
					$svt_sold="";
					$svd_sold="";
					
					$disc_label="";
					
					$shortage="";
					$overage="";
					
					if($tis_nm>0){
						$tis_row=$tis_rs->fetch_assoc();
						
						$sjt_sold=$tis_row['sjt'];
						$sjd_sold=$tis_row['sjd'];
						$svt_sold=$tis_row['svt'];
						$svd_sold=$tis_row['svd'];
						
						$disc_label="'".$sjd_sold."/".$svd_sold;
						
						addContent(setRange("D".$rowCount,"D".$rowCount),$excel,$sjt_sold,"true",$ExWs);
						addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$disc_label,"true",$ExWs);
						addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$svt_sold,"true",$ExWs);
						
						
					}
					
					$dis_sql="select * from discrepancy where dar_id='".$dar_ts_row['id']."' limit 1";
					$dis_rs=$db->query($dis_sql);
					$dis_nm=$dis_rs->num_rows;
					
					if($dis_nm>0){
						$dis_row=$dis_rs->fetch_assoc();
						
						$shortage=$dis_row['shortage'];
						$overage=$dis_row['overage'];
						
						addContent(setRange("G".$rowCount,"G".$rowCount),$excel,$shortage,"true",$ExWs);
						addContent(setRange("I".$rowCount,"I".$rowCount),$excel,$overage,"true",$ExWs);
					
					
					}
					
					$discrepancy_t_sql="select * from discrepancy_ticket where dar_id='".$dar_ts_row['id']."'";
					$discrepancy_t_rs=$db->query($discrepancy_t_sql);
					$discrepancy_t_nm=$discrepancy_t_rs->num_rows;
					
					$shortage_label="";
					$overage_label="";
						
					$short_count=0;
					$over_count=0;
					
					if($discrepancy_t_nm>0){
						for($m=0;$m<$discrepancy_t_nm;$m++){
							$discrepancy_t_row=$discrepancy_t_rs->fetch_assoc();
							
							if($discrepancy_t_row['shortage']*1==0){
							}
							else {
								if($short_count==0){
								}
								else {
									$shortage_label.=", ";	

									
								
								}
								$shortage_label.=$discrepancy_t_row['shortage']." ".strtoupper($discrepancy_t_row['ticket_type']);

								
								if(strtoupper($remarks)=="UNREG"){
								
									$unreg[$discrepancy_t_row['ticket_type']]["shortage"]+=$discrepancy_t_row['shortage'];
									
								}
								else {
									$paid[$discrepancy_t_row['ticket_type']]["shortage"]+=$discrepancy_t_row['shortage'];
								
								
								}
								
								
								$short_count++;	
							}
							if($discrepancy_t_row['overage']*1==0){
							}
							else {
								if($over_count==0){
								}
								else {
									$overage_label.=", ";	
								}

								$overage_label.=$discrepancy_t_row['overage']." ".strtoupper($discrepancy_t_row['ticket_type']);

								if(strtoupper($remarks)=="UNREG"){
								
									$unreg[$discrepancy_t_row['ticket_type']]["overage"]+=$discrepancy_t_row['overage'];
									
								}
								else {
									$paid[$discrepancy_t_row['ticket_type']]["overage"]+=$discrepancy_t_row['overage'];
								
								
								}

								
								
								$over_count++;		
							}

							
						
						}
						
						addContent(setRange("H".$rowCount,"H".$rowCount),$excel,$shortage_label,"true",$ExWs);
						addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$overage_label,"true",$ExWs);
					}					

					addContent(setRange("K".$rowCount,"K".$rowCount),$excel,$remarks,"true",$ExWs);
					
					addContent(setRange("M".$rowCount,"M".$rowCount),$excel,"1","true",$ExWs);



					$excel->getActiveSheet()->getStyle("B".$rowCount,"B".$rowCount)->applyFromArray($styleArray);
					$excel->getActiveSheet()->getStyle("C".$rowCount,"C".$rowCount)->applyFromArray($styleArray);
					$excel->getActiveSheet()->getStyle("D".$rowCount,"D".$rowCount)->applyFromArray($styleArray);
					$excel->getActiveSheet()->getStyle("E".$rowCount,"E".$rowCount)->applyFromArray($styleArray);
					$excel->getActiveSheet()->getStyle("F".$rowCount,"F".$rowCount)->applyFromArray($styleArray);
					$excel->getActiveSheet()->getStyle("G".$rowCount,"G".$rowCount)->applyFromArray($styleArray);
					$excel->getActiveSheet()->getStyle("H".$rowCount,"H".$rowCount)->applyFromArray($styleArray);
					$excel->getActiveSheet()->getStyle("I".$rowCount,"I".$rowCount)->applyFromArray($styleArray);
					$excel->getActiveSheet()->getStyle("J".$rowCount,"J".$rowCount)->applyFromArray($styleArray);
					$excel->getActiveSheet()->getStyle("K".$rowCount,"K".$rowCount)->applyFromArray($styleArray);

					
					$rowCount++;

				}
				$station_end=$rowCount*1-1;
				if($station_start>$station_end){ $station_end=$station_start; }
				
				addContent(setRange("A".$station_start,"A".$station_end),$excel,$station_name,"true",$ExWs);
				$excel->getActiveSheet()->getStyle("A".$station_start,"A".$station_end)->applyFromArray($styleArray);
				$excel->getActiveSheet()->getStyle("A".$station_start.":A".$station_end)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				

			}
		
		}


	}
		$end=$rowCount*1-1;
		
		if($start>$end){ $end=$start; $rowCount++; }
		
		addContent(setRange("A".$rowCount,"F".($rowCount*1+5)),$excel,"","true",$ExWs);

		$styleArray = array(
			'borders' => array(
				'outline' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			),
		);


		$excel->getActiveSheet()->getStyle("A".$rowCount.":F".($rowCount*1+5))->applyFromArray($styleArray);		
		
		
		
		addContent(setRange("G".$rowCount,"G".($rowCount*1+5)),$excel,"=sum(G".$start.":G".$end.")","true",$ExWs);
		$excel->getActiveSheet()->getStyle("G".$rowCount.":G".($rowCount*1+5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle("G".$rowCount.":G".($rowCount*1+5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		
		
		if($paid["sjt"]["shortage"]=="0"){ $paid_sjt=""; } else { $paid_sjt=$paid["sjt"]["shortage"]." SJT"; }
		if($paid["sjd"]["shortage"]=="0"){ $paid_sjd=""; } else { $paid_sjd=$paid["sjd"]["shortage"]." DSJT/"; }
		if($paid["svt"]["shortage"]=="0"){ $paid_svt=""; } else { $paid_svt=$paid["svt"]["shortage"]." SVT"; }
		if($paid["svd"]["shortage"]=="0"){ $paid_svd=""; } else { $paid_svd=$paid["svd"]["shortage"]." DSVT"; }
		
		$paid_disc=$paid_sjd.$paid_svd;
		

		if($unreg["sjt"]["shortage"]=="0"){ $unreg_sjt=""; } else { $unreg_sjt=$unreg["sjt"]["shortage"]." SJT"; }
		if($unreg["sjd"]["shortage"]=="0"){ $unreg_sjd=""; } else { $unreg_sjd=$unreg["sjd"]["shortage"]." DSJT/"; }
		if($unreg["svt"]["shortage"]=="0"){ $unreg_svt=""; } else { $unreg_svt=$unreg["svt"]["shortage"]." SVT"; }
		if($unreg["svd"]["shortage"]=="0"){ $unreg_svd=""; } else { $unreg_svd=$unreg["svd"]["shortage"]." DSVT"; }
		
		$unreg_disc=$paid_sjd.$paid_svd;
		
		addContent(setRange("H".$rowCount,"H".$rowCount),$excel,$paid_sjt,"true",$ExWs);
		addContent(setRange("H".($rowCount*1+1),"H".($rowCount*1+1)),$excel,$paid_disc,"true",$ExWs);
		addContent(setRange("H".($rowCount*1+2),"H".($rowCount*1+2)),$excel,$paid_svt,"true",$ExWs);

		addContent(setRange("H".($rowCount*1+3),"H".($rowCount*1+3)),$excel,$unreg_sjt,"true",$ExWs);
		addContent(setRange("H".($rowCount*1+4),"H".($rowCount*1+4)),$excel,$unreg_disc,"true",$ExWs);
		addContent(setRange("H".($rowCount*1+5),"H".($rowCount*1+5)),$excel,$unreg_svt,"true",$ExWs);
		


		$excel->getActiveSheet()->getStyle("G".($rowCount*1+5).":G".($rowCount*1+5))->applyFromArray($styleArray);	


		$excel->getActiveSheet()->getStyle("H".$rowCount.":H".$rowCount)->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("H".($rowCount*1+1).":H".($rowCount*1+1))->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("H".($rowCount*1+2).":H".($rowCount*1+2))->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("H".($rowCount*1+3).":H".($rowCount*1+3))->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("H".($rowCount*1+4).":H".($rowCount*1+4))->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("H".($rowCount*1+5).":H".($rowCount*1+5))->applyFromArray($styleArray);	

		$excel->getActiveSheet()->getStyle("I".($rowCount*1+5).":I".($rowCount*1+5))->applyFromArray($styleArray);	


		$excel->getActiveSheet()->getStyle("J".$rowCount.":J".$rowCount)->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("J".($rowCount*1+1).":J".($rowCount*1+1))->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("J".($rowCount*1+2).":J".($rowCount*1+2))->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("J".($rowCount*1+3).":J".($rowCount*1+3))->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("J".($rowCount*1+4).":J".($rowCount*1+4))->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("J".($rowCount*1+5).":J".($rowCount*1+5))->applyFromArray($styleArray);	


		
		addContent(setRange("I".$rowCount,"I".($rowCount*1+5)),$excel,"=sum(I".$start.":I".$end.")","true",$ExWs);
		$excel->getActiveSheet()->getStyle("I".$rowCount.":I".($rowCount*1+5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle("I".$rowCount.":I".($rowCount*1+5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		
		if($paid["sjt"]["overage"]=="0"){ $paid_sjt=""; } else { $paid_sjt=$paid["sjt"]["overage"]." SJT"; }
		if($paid["sjd"]["overage"]=="0"){ $paid_sjd=""; } else { $paid_sjd=$paid["sjd"]["overage"]." DSJT/"; }
		if($paid["svt"]["overage"]=="0"){ $paid_svt=""; } else { $paid_svt=$paid["svt"]["overage"]." SVT"; }
		if($paid["svd"]["overage"]=="0"){ $paid_svd=""; } else { $paid_svd=$paid["svd"]["overage"]." DSVT"; }
		
		$paid_disc=$paid_sjd.$paid_svd;
		

		if($unreg["sjt"]["overage"]=="0"){ $unreg_sjt=""; } else { $unreg_sjt=$unreg["sjt"]["overage"]." SJT"; }
		if($unreg["sjd"]["overage"]=="0"){ $unreg_sjd=""; } else { $unreg_sjd=$unreg["sjd"]["overage"]." DSJT/"; }
		if($unreg["svt"]["overage"]=="0"){ $unreg_svt=""; } else { $unreg_svt=$unreg["svt"]["overage"]." SVT"; }
		if($unreg["svd"]["overage"]=="0"){ $unreg_svd=""; } else { $unreg_svd=$unreg["svd"]["overage"]." DSVT"; }
		
		addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$paid_sjt,"true",$ExWs);
		addContent(setRange("J".($rowCount*1+1),"J".($rowCount*1+1)),$excel,$paid_disc,"true",$ExWs);
		addContent(setRange("J".($rowCount*1+2),"J".($rowCount*1+2)),$excel,$paid_svt,"true",$ExWs);

		addContent(setRange("J".($rowCount*1+3),"J".($rowCount*1+3)),$excel,$unreg_sjt,"true",$ExWs);
		addContent(setRange("J".($rowCount*1+4),"J".($rowCount*1+4)),$excel,$unreg_disc,"true",$ExWs);
		addContent(setRange("J".($rowCount*1+5),"J".($rowCount*1+5)),$excel,$unreg_svt,"true",$ExWs);

		
		
		addContent(setRange("K".$rowCount,"K".($rowCount*1+2)),$excel,"=G".$rowCount,"true",$ExWs);
		addContent(setRange("K".($rowCount*1+3),"K".($rowCount*1+5)),$excel,"UNREG","true",$ExWs);
		$excel->getActiveSheet()->getStyle("K".$rowCount.":K".($rowCount*1+2))->applyFromArray($styleArray);	
		$excel->getActiveSheet()->getStyle("K".($rowCount*1+3).":K".($rowCount*1+5))->applyFromArray($styleArray);	

		$excel->getActiveSheet()->getStyle("K".$rowCount.":K".($rowCount*1+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle("K".$rowCount.":K".($rowCount*1+2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$excel->getActiveSheet()->getStyle("K".($rowCount*1+3).":K".($rowCount*1+5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle("K".($rowCount*1+3).":K".($rowCount*1+5))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		

		
		$rowCount+=6;
		
//		$rowCount=149;
	
		addContent(setRange("A".$rowCount,"K".$rowCount),$excel,"**Personnel with distinct Amount/Quantity of Discrepancy/s","true",$ExWs);
	
		$rowCount++;
	
		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,"Note:","true",$ExWs);


		$rowCount++;
		addContent(setRange("A".$rowCount,"B".$rowCount),$excel,"Total # of vendors reported for work","true",$ExWs);
		addContent(setRange("C".$rowCount,"C".$rowCount),$excel,"( A )    =","true",$ExWs);
		
		$a_count=$rowCount;
		
		
		$excel->getActiveSheet()->getStyle("C".$rowCount.":C".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"=count(M".$start.":M".$end.")","true",$ExWs);
		
		$excel->getActiveSheet()->getStyle("D".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);


		$rowCount++;
		addContent(setRange("A".$rowCount,"B".$rowCount),$excel,"Total # of vendors with shortages","true",$ExWs);
		addContent(setRange("C".$rowCount,"C".$rowCount),$excel,"( B )    =","true",$ExWs);
		$excel->getActiveSheet()->getStyle("C".$rowCount.":C".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		
		addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"=count(G".$start.":G".$end.")","true",$ExWs);
		
		
		$excel->getActiveSheet()->getStyle("D".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

		$b_count=$rowCount;
		
		
		
		$rowCount++;
		addContent(setRange("A".$rowCount,"B".$rowCount),$excel,"Total # of vendors with overages","true",$ExWs);
		addContent(setRange("C".$rowCount,"C".$rowCount),$excel,"( C )    =","true",$ExWs);
		$excel->getActiveSheet()->getStyle("C".$rowCount.":C".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"=count(I".$start.":I".$end.")","true",$ExWs);


		$excel->getActiveSheet()->getStyle("D".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

		$c_count=$rowCount;
		
		
		
		$rowCount++;
		$rowCount++;		
		addContent(setRange("A".$rowCount,"B".($rowCount*1+1)),$excel,"Percentage of vendors with shortages =","true",$ExWs);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":B".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		addContent(setRange("C".$rowCount,"C".$rowCount),$excel,"B","true",$ExWs);
		$excel->getActiveSheet()->getStyle("C".$rowCount.":C".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		addContent(setRange("C".($rowCount*1+1),"C".($rowCount*1+1)),$excel,"A","true",$ExWs);
		$excel->getActiveSheet()->getStyle("C".($rowCount*1+1).":C".($rowCount*1+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		
		$excel->getActiveSheet()->getStyle("C".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);



		addContent(setRange("F".$rowCount,"F".$rowCount),$excel,"=D".$b_count,"true",$ExWs);

		addContent(setRange("F".($rowCount*1+1),"F".($rowCount*1+1)),$excel,"=D".$a_count,"true",$ExWs);

		
		addContent(setRange("D".$rowCount,"D".($rowCount*1+1)),$excel,"X 100%","true",$ExWs);
		$excel->getActiveSheet()->getStyle("D".$rowCount.":D".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);



		
		addContent(setRange("E".$rowCount,"E".($rowCount*1+1)),$excel,"=","true",$ExWs);

		
		$excel->getActiveSheet()->getStyle("F".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$excel->getActiveSheet()->getStyle("F".($rowCount*1+1).":F".($rowCount*1+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		




		
		addContent(setRange("G".$rowCount,"H".($rowCount*1+1)),$excel,"  X 100%    =","true",$ExWs);

		$excel->getActiveSheet()->getStyle("G".$rowCount.":H".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		
		addContent(setRange("I".$rowCount,"I".($rowCount*1+1)),$excel,"=(F".$rowCount."/F".($rowCount*1+1).")*100","true",$ExWs);


		
		addContent(setRange("J".$rowCount,"J".($rowCount*1+1)),$excel,"%","true",$ExWs);
		addContent(setRange("K".$rowCount,"K".($rowCount*1+1)),$excel,"%","true",$ExWs);
		

		$excel->getActiveSheet()->getStyle("I".$rowCount.":I".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle("J".$rowCount.":J".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle("K".$rowCount.":K".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


		

		$rowCount++;
		$rowCount++;		
		
		


		addContent(setRange("A".$rowCount,"B".($rowCount*1+1)),$excel,"Percentage of vendors with overages =","true",$ExWs);
		$excel->getActiveSheet()->getStyle("A".$rowCount.":B".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


		$excel->getActiveSheet()->getStyle("C".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

		addContent(setRange("C".$rowCount,"C".$rowCount),$excel,"C","true",$ExWs);
		$excel->getActiveSheet()->getStyle("C".$rowCount.":C".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		addContent(setRange("C".($rowCount*1+1),"C".($rowCount*1+1)),$excel,"A","true",$ExWs);
		$excel->getActiveSheet()->getStyle("C".($rowCount*1+1).":C".($rowCount*1+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


		addContent(setRange("D".$rowCount,"D".($rowCount*1+1)),$excel,"X 100%","true",$ExWs);
		$excel->getActiveSheet()->getStyle("D".$rowCount.":D".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$excel->getActiveSheet()->getStyle("E".$rowCount.":E".$rowCount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		addContent(setRange("E".$rowCount,"E".($rowCount*1+1)),$excel,"=","true",$ExWs);
		$excel->getActiveSheet()->getStyle("F".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);

		addContent(setRange("F".$rowCount,"F".$rowCount),$excel,"=D".$c_count,"true",$ExWs);
		$excel->getActiveSheet()->getStyle("F".$rowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		$excel->getActiveSheet()->getStyle("F".$rowCount.":F".$rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		
		addContent(setRange("F".($rowCount*1+1),"F".($rowCount*1+1)),$excel,"=D".$a_count,"true",$ExWs);

		$excel->getActiveSheet()->getStyle("F".($rowCount*1+1).":F".($rowCount*1+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		addContent(setRange("G".$rowCount,"H".($rowCount*1+1)),$excel,"  X 100%    =","true",$ExWs);
		$excel->getActiveSheet()->getStyle("G".$rowCount.":H".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		addContent(setRange("I".$rowCount,"I".($rowCount*1+1)),$excel,"=(F".$rowCount."/F".($rowCount*1+1).")*100","true",$ExWs);

		addContent(setRange("J".$rowCount,"J".($rowCount*1+1)),$excel,"%","true",$ExWs);
		addContent(setRange("K".$rowCount,"K".($rowCount*1+1)),$excel,"%","true",$ExWs);

		$excel->getActiveSheet()->getStyle("I".$rowCount.":I".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle("J".$rowCount.":J".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$excel->getActiveSheet()->getStyle("K".$rowCount.":K".($rowCount*1+1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	save($ExWb,$excel,$newFilename); 	
	$newFilename2=str_replace('xls','html',$newFilename);
	saveHTML($ExWb,$excel,$newFilename2); 	

	echo "DR2C Report has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";	
	echo "<br>";
	echo "DR2C (HTML) Report has been generated!  Press right click and Save As: <a href='".$newFilename2."'>Here</a>";	
	
?>