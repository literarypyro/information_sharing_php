<?php
function retrieveDb(){
	$db=new mysqli("localhost","root","","station");
	return $db;
}
?>