<script type='text/javascript' src='jquery-2.0.3.js'></script>
<script language='javascript'>
$('document').ready(function(){
	$('p').css('color','blue');
});



</script>
<?php
$db=new mysqli("localhost","root","","transport");

$sql="select * from train_availability limit 6";
$rs=$db->query($sql);
$nm=$rs->num_rows;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();

	$item[$i]=$row;
	
}

//echo json_encode($item);
$value="NIÑO";

echo iconv('ISO-8859-1','UTF-8', $value)
?>
<p>
This is a test text.


</p>


