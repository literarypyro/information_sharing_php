<!--Modified by Manolo Jr.
Date: 21 July 2014
Modify: Login screen
Program: Transport CCDR
-->

<?php
session_start();
?>
<?php
//session_destroy();
?>
<?php
//header("Location: monitoring menu.php");
$_SESSION['page']="submit.php";
require("header.php"); //added mjun@
?>

<style type='text/css'>

h1{ 
	color: #444; 
	font: 20px Century; 
	-webkit-font-smoothing: antialiased; 
	text-shadow: 0px 1px black; 
	border-bottom: 1px solid #FBCC2A; 
	margin: 10px 0 2px 0; 
	padding: 5px 0 6px 0; 
}

</style>
<LINK href="css/program design 3.css" rel="stylesheet" type="text/css"> 
<title>Login</title>
<body>
<h1>DOTC-MRT3 Control Center Operation</h1>
<code style="height:200px; ">
	<div style="float:left;" align="left">
		<img src="mrt-logo.png">
	</div>
	<div style="float:center;" align="center">
		<form action="submit.php" method="post">
		<table>
			<tr><th colspan=2 align="center">Log-in to the System:</th></tr>
			<tr>
				<td align="right">username : </td>
				<td><input type='text' name='username' placeholder="username" style="width:200px; font-family:verdana;" /></td>
			</tr>
			<tr>
				<td align="right">password : </td>
				<td><input type='password' name='password' placeholder="password" style="width:200px; font-family:verdana;" /></td>
			</tr>
			<tr>
				<td colspan=2 align="center"><input type=submit value='Submit' />
			</tr>
		</table>
		
		<!--</form>
		<table align=center >	
		<tr><th colspan=2>For new account:</th></tr><tr><th colspan=2><a  href='createAccount.php'>
		 Create a new Account 
		</a></th></tr>
 		</table> -->
		<br>
		<br>
		<br>
				
	</div>
</code>

<style>

	body{ background-image:url(images/page-bg.png); width:800px; height:auto; margin-left:auto; margin-right:auto; margin-top:60px; font-family: verdana; } 

	  code{ background-color: #f9f9f9; border: 1px solid #D0D0D0;  display: block; margin: 14px 0 14px 0; padding: 12px 10px 12px 10px; font-family:verdana;} 

	/* input{ height:30px; font-weight:bold; font-size:18px; width:200px; font-family:courier; border: 1px solid #C6C6C6; width:200px; text-align:left;} */
	
	/* input[type=text] { border: solid 1px #85b1de; font-size: 15px; font-family:verdana; padding:3px; } */
	input[type=text]:hover{ background:#F0F0F0; }
	input[type=text]:focus{ background:#F9F9F9; border: solid 1px #373737; } 

	input[type=password]{ background:#F9F9F9; border: solid 1px #736357; }
	input[type=password]:hover{ background:#F0F0F0; }
	input[type=button]:hover{ background:GOLD; } 

	.r{ text-align:right; }
	.c{ text-align:center; }
	.f10{ font-size:11px; } 

	input[type=submit]:hover{ background:gold; }
	
</style>
</body>


<?php
if(isset($_POST['password'])){
	if($_POST['password']=="123456"){
		
		if($_POST['unit']=="ssu"){
			header("Location: ssu/index.php");	

		}
		else if($_POST['unit']=="station"){
			header("Location: station/index.php");	
		
		
		
		}
		else if($_POST['unit']=="transport"){
			header("Location: transport/index.php");	
		
		
		
		}
		else if($_POST['unit']=="gm"){
			header("Location: monitoring/index.php");	
		
		
		
		}
	
	}
}
?>  






<!--<link rel="stylesheet" type="text/css" href="layout/login.css">
<table class='loginTable' align=center >
<tr>
<td valign=top align=center>
<br>
<br>
<br>
<br>
<br>
<br>
<form enctype="multipart/form-data" action='index.php' method='post'>
<table  align=center  >
<tr><th colspan=2>Log-In Here:</th></tr>
<tr>
	<td>Enter Division/Unit:</td>
	<td>
	<select name='unit'>
	<option value='station'>Station</option>
	<option value='transport'>Transport</option>
	<option value='ssu'>Safety and Security Unit</option>
	<option value='gm'>Office of the General Manager</option>
	</select>
	</td>
</tr>
<tr>
	<td>Enter Password:</td>
	<td><input type='password' name='password'  /></td>
</tr>
<tr>
	<td colspan=2 align=center><input type=submit value='Submit' /></td>
</tr>
</table>
</form>
</td>
</tr>
</table>-->

