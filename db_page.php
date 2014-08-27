<!-- host,username,password,dbname transport-->

<?php
function retrieveRecordsDb(){
	//Database for Records
//	$db=new mysqli('mdcruz-sdu','transport','123456','tranport');
	
	$db=new mysqli("localhost","root","","transport");
	//$db=new mysqli ( 'sduserver','records','123456','records');

	//$db=new mysqli('192.168.1.128','server_user','123456','records');
	return $db;
}

function retrieveUsersDb(){
	//Database for Users
	

	//db2=new mysqli('sduserver','records','123456','user_management');
	//$db2=new mysqli('sduserver','records','123456','user_management');
	
//	$db2=new mysqli('mdcruz-sdu','records','123456','user_transport');
	
	$db2=new mysqli('localhost','root','','user_transport');

	//$db2=new mysqli('192.168.1.128','server_user','123456','user_management');
	return $db2;
}
?>