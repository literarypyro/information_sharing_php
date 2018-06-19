<?php

if(isset($_POST['field_2'])){
	$db=new mysqli("localhost","root","","station_temp");
	$daily_id=$_POST['daily_id'];
	
	$ticket_seller=$_POST['field_2'];
	$shift=$_POST['field_3'];
	
	$ad_no=$_POST['field_4'];

	$remitted=$_POST['field_37'];
	$refund=$_POST['field_38'];

	$remarks=$_POST['field_35'];
/*
	$sjt=$_POST['field_7'];
	$svt=$_POST['field_8'];
	$sjd=$_POST['field_9'];
	$svd=$_POST['field_10'];

*/	
    //Tickets Sold
	$sjd_sc=$_POST['field_11'];
	$sjd_pwd=$_POST['field_12'];
	
	$svd_sc=$_POST['field_20'];
	$svd_pwd=$_POST['field_21'];


	$sjt_quantity=$_POST['field_5'];
	$sjd_quantity=$sjd_sc+$sjd_pwd;
	$svt_quantity=$_POST['field_16'];
	$svd_quantity=$svd_sc+$svd_pwd;
	
	$sjt_amount=$_POST['field_6'];
	$sjt_fare=$_POST['field_7'];
	$sjt_timeover=$_POST['field_8'];
	
	
	$sjd_amount=$_POST['field_13'];
	$svt_amount=$_POST['field_17'];
	$svd_amount=$_POST['field_22'];
	
	//End Block

	//Tickets Refunded
	$sjt_refund_qty=$_POST['field_9'];
	$sjd_refund_qty=$_POST['field_14'];
	$svt_refund_qty=$_POST['field_18'];
	$svd_refund_qty=$_POST['field_23'];

	$sjt_refund_amount=$_POST['field_10'];
	$sjd_refund_amount=$_POST['field_15'];
	$svt_refund_amount=$_POST['field_19'];
	$svd_refund_amount=$_POST['field_24'];
	//End Block
	
	//Ticket Discrepancies
	$sjt_shortage=$_POST['field_26'];
	$sjd_shortage=$_POST['field_27'];
	$svt_shortage=$_POST['field_28'];
	$svd_shortage=$_POST['field_29'];
	
	$sjt_overage=$_POST['field_31'];
	$sjd_overage=$_POST['field_32'];
	$svt_overage=$_POST['field_33'];
	$svd_overage=$_POST['field_34'];
	//End Block

	$overage=$_POST['field_30'];
	$shortage=$_POST['field_25'];
	$sked_type="Regular";
	$action_type=$_POST['action'];
	//$sked_type=$_POST['sked_type'];	
	
	if($action_type=="add"){
		$dar_entry_update="insert into dar(ticket_seller,shift,ad_no,remitted,refund,remarks,daily_id,sked_type) ";
		$dar_entry_update.=" values ('".$ticket_seller."','".$shift."','".$ad_no."','".$remitted."','".$refund."',\"".$remarks."\",'".$daily_id."','".$sked_type."')";
		$dar_entry_update_rs=$db->query($dar_entry_update);
		$dar_entry_id=$db->insert_id;
		
		$dar_sales_update="insert into sales(dar_id,quantity,amount,fare_adjustment,timeover,ticket) ";	
		$dar_sales_update.=" values ";
		$dar_sales_update.="('".$dar_entry_id."','".$sjt_quantity."','".$sjt_amount."','".$sjt_fare."','".$sjt_timeover."','sjt'),";
		$dar_sales_update.="('".$dar_entry_id."','".$sjd_quantity."','".$sjd_amount."','','','sjd'),";
		$dar_sales_update.="('".$dar_entry_id."','".$svt_quantity."','".$svt_amount."','','','svt'),";
		$dar_sales_update.="('".$dar_entry_id."','".$svd_quantity."','".$svd_amount."','','','svt')";
		$rs=$db->query($dar_sales_update);
		
		$dar_refund_update="insert into refund(dar_id,quantity,amount,ticket) ";	
		$dar_refund_update.=" values ";
		$dar_refund_update.="('".$dar_entry_id."','".$sjt_refund_qty."','".$sjt_refund_amount."','sjt'),";
		$dar_refund_update.="('".$dar_entry_id."','".$sjd_refund_qty."','".$sjd_refund_amount."','sjd'),";
		$dar_refund_update.="('".$dar_entry_id."','".$svt_refund_qty."','".$svt_refund_amount."','svt'),";
		$dar_refund_update.="('".$dar_entry_id."','".$svd_refund_qty."','".$svd_refund_amount."','svd')";
		$rs=$db->query($dar_refund_update);
		
		$dar_discount_update="insert into discounted_sales(dar_id,sc,pwd,ticket) ";	
		$dar_discount_update.=" values ";
		$dar_discount_update.="('".$dar_entry_id."','".$sjd_sc."','".$sjd_pwd."','sjd'),";
		$dar_discount_update.="('".$dar_entry_id."','".$svd_sc."','".$svd_pwd."','svd')";
		$rs=$db->query($dar_discount_update);
		
		$dar_disc_update="insert into discrepancy_ticket(dar_id,ticket_type,shortage,overage) ";	
		$dar_disc_update.=" values ";
		$dar_disc_update.="('".$dar_entry_id."','sjt','".$sjt_shortage."','".$sjt_overage."'),";
		$dar_disc_update.="('".$dar_entry_id."','sjd','".$sjd_shortage."','".$sjd_overage."'),";
		$dar_disc_update.="('".$dar_entry_id."','svt','".$svt_shortage."','".$svt_overage."'),";
		$dar_disc_update.="('".$dar_entry_id."','svd','".$svd_shortage."','".$svd_overage."')";
		$rs=$db->query($dar_disc_update);
		
		
		$discrepancy_entry_update="insert into discrepancy(shortage,overage,dar_id) values ('".$shortage."','".$overage."','".$dar_entry_id."')";
		$discrepancy_entry_update_rs=$db->query($discrepancy_entry_update);

		$dar["dar_id"]=$dar_entry_id;
		$dar["rec_id"]=$_POST['rec_id'];
		$dar['action']=$_POST['action'];
		
		echo json_encode($dar);
	}	
	else if($action_type=="edit"){
		$dar_entry_id=str_replace("dar_id_","",$_POST['dar_id']);
		
		$dar_entry_update="update dar set ticket_seller='".$ticket_seller."',shift='".$shift."',ad_no='".$ad_no."',remitted=".$remitted.",refund=".$refund.",remarks=\"".$remarks."\" where id='".$dar_entry_id."'";
		$dar_entry_update_rs=$db->query($dar_entry_update);

		$dar_sales_update="delete from sales where dar_id='".$dar_entry_id."'";
		$rs=$db->query($dar_sales_update);


		$dar_sales_update="insert into sales(dar_id,quantity,amount,fare_adjustment,timeover,ticket) ";	
		$dar_sales_update.=" values ";
		$dar_sales_update.="('".$dar_entry_id."','".$sjt_quantity."','".$sjt_amount."','".$sjt_fare."','".$sjt_timeover."','sjt'),";
		$dar_sales_update.="('".$dar_entry_id."','".$sjd_quantity."','".$sjd_amount."','','','sjd'),";
		$dar_sales_update.="('".$dar_entry_id."','".$svt_quantity."','".$svt_amount."','','','svt'),";
		$dar_sales_update.="('".$dar_entry_id."','".$svd_quantity."','".$svd_amount."','','','svt')";
		$rs=$db->query($dar_sales_update);
		
		$dar_sales_update="delete from refund where dar_id='".$dar_entry_id."'";
		$rs=$db->query($dar_sales_update);

		$dar_refund_update="insert into refund(dar_id,quantity,amount,ticket) ";	
		$dar_refund_update.=" values ";
		$dar_refund_update.="('".$dar_entry_id."','".$sjt_refund_qty."','".$sjt_refund_amount."','sjt'),";
		$dar_refund_update.="('".$dar_entry_id."','".$sjd_refund_qty."','".$sjd_refund_amount."','sjd'),";
		$dar_refund_update.="('".$dar_entry_id."','".$svt_refund_qty."','".$svt_refund_amount."','svt'),";
		$dar_refund_update.="('".$dar_entry_id."','".$svd_refund_qty."','".$svd_refund_amount."','svd')";
		$rs=$db->query($dar_refund_update);
		
		$dar_sales_update="delete from discounted_sales where dar_id='".$dar_entry_id."'";
		$rs=$db->query($dar_sales_update);

		$dar_discount_update="insert into discounted_sales(dar_id,sc,pwd,ticket) ";	
		$dar_discount_update.=" values ";
		$dar_discount_update.="('".$dar_entry_id."','".$sjd_sc."','".$sjd_pwd."','sjd'),";
		$dar_discount_update.="('".$dar_entry_id."','".$svd_sc."','".$svd_pwd."','svd')";
		$rs=$db->query($dar_discount_update);
		
		$dar_sales_update="delete from discrepancy_ticket where dar_id='".$dar_entry_id."'";
		$rs=$db->query($dar_sales_update);

		$dar_disc_update="insert into discrepancy_ticket(dar_id,ticket_type,shortage,overage) ";	
		$dar_disc_update.=" values ";
		$dar_disc_update.="('".$dar_entry_id."','sjt','".$sjt_shortage."','".$sjt_overage."'),";
		$dar_disc_update.="('".$dar_entry_id."','sjd','".$sjd_shortage."','".$sjd_overage."'),";
		$dar_disc_update.="('".$dar_entry_id."','svt','".$svt_shortage."','".$svt_overage."'),";
		$dar_disc_update.="('".$dar_entry_id."','svd','".$svd_shortage."','".$svd_overage."')";
		$rs=$db->query($dar_disc_update);



		$discrepancy_entry_update="update discrepancy set shortage='".$shortage."',overage='".$overage."' where dar_id='".$dar_entry_id."')";
		$discrepancy_entry_update_rs=$db->query($discrepancy_entry_update);

		$dar["dar_id"]=$dar_entry_id;
		$dar["rec_id"]=$_POST['rec_id'];
		$dar['action']=$_POST['action'];
		echo json_encode($dar);
		
	}
	else if($action_type=="remove"){
		$dar_entry_id=str_replace("dar_id_","",$_POST['dar_id']);
		
		$dar_entry_update="delete from dar where id='".$dar_entry_id."'";
		$dar_entry_update_rs=$db->query($dar_entry_update);

		$dar_sales_update="delete from sales where dar_id='".$dar_entry_id."'";
		$rs=$db->query($dar_sales_update);

	
		$dar_sales_update="delete from refund where dar_id='".$dar_entry_id."'";
		$rs=$db->query($dar_sales_update);

		
		$dar_sales_update="delete from discounted_sales where dar_id='".$dar_entry_id."'";
		$rs=$db->query($dar_sales_update);

		
		$dar_sales_update="delete from discrepancy_ticket where dar_id='".$dar_entry_id."'";
		$rs=$db->query($dar_sales_update);


		$discrepancy_entry_update="delete from discrepancy where dar_id='".$dar_entry_id."')";
		$discrepancy_entry_update_rs=$db->query($discrepancy_entry_update);
	}
}	

if(isset($_GET['ts'])){
	$db=new mysqli("localhost","root","","station");
		
	$sql="select * from ticket_seller order by last_name";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
//	$ticket_sellers="[";	
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$lastName=$row['last_name'];
		$firstName=$row['first_name'];
		
		$name=$lastName.", ".$firstName;
		$ts_id=$row['id'];
		
//		$ticket_sellers .= " { \"label\": \"$name\", \"value\": \"$ts_id\"},";
		$ticket_sellers[$i]['name']=$name;
		$ticket_sellers[$i]['id']=$ts_id;
		
		
		
	}
	//$ticket_sellers=substr($ticket_sellers,0,-1); 	
	$ticket_sellers["ts_count"]=$nm;
	//$ticket_sellers.="]";
	echo json_encode($ticket_sellers);
}
?>