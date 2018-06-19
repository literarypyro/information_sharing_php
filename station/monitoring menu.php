<style type='text/css'>
body{
	font-family:Helvetica;
	margin:0px;
}
.nav {
	font-family:Calibri;
	letter-spacing:0.5px;
	padding: 0;
	margin: 0;
	list-style: none;
}

.nav li {
	background-color: #222;
	float: left;
	position: relative;
	text-align:center;
/*	margin-right: 0.5em;
	/*
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	-webkit-box-shadow: 3px 3px 3px 3px rgba(43, 43, 77, 0.5);
	-moz-box-shadow: 3px 3px 3px 3px rgba(43, 43, 77, 0.5);
	box-shadow: 3px 3px 3px 3px rgba(43, 43, 77, 0.5);
	*/
  }

.nav > li {
	border-right:1px solid #444;
  }

.nav > li:hover{
	background-color:rgb(170,50,60);
}
.nav > li:hover >a{
	color:#fff;
	text-shadow:0px 0px 30px #fff;
} 
  
.nav > li > a {
	font-size:15px;
}
.nav li a {
color: #aaa; 
text-decoration: none;
font-weight: bold;
display:block;
height:100%;
padding:0px 25px;
  }
.nav > li > a{
	line-height:50px;
}
.nav li a:hover, .nav li ul li a:hover{
	color: #fff; 
}
  
.nav li ul {
	display: none;
	position: absolute; 
	top: 1em;
	left: 0;
	margin-left:-.2em;
}


.nav li > ul {
	margin:0px;
	padding:0px;
	top: auto;
	left: auto;
	background:red;
	box-shadow:0px 0px 10px 0px #000;
}

.nav li:hover ul { 
	display: block;
}

.nav li ul li {
	width:320px;
	text-align:left;
  	background-color: #666;
	border-bottom:1px solid #888;
	transition:0.05s;
}
.nav li ul li:last-child{
	border-bottom:none;
}


.nav li ul li a {
	color:#eee;
	padding: 0.8em; 
	font-size:14px;
}

.nav li ul li:hover {
	background-color:rgba(207,207,207,0.5);
}
.nav li ul li:hover a{
	color:#FFF;
}


.nav-wrapper{
background-color:#222;
height:50px;
margin:auto;
box-shadow:0px 0px 10px 0px #222;
}

.TitleHeader table{
font-size:18px;
vertical-align:middle;
font-family:"Lithos Pro";
padding:5px 0px;
background:#222;
border-bottom:1px solid #444;
color:#DDD;
margin:0px;
text-shadow:0px 0px 15px white;
}
</style>
<!--color: rgb(85, 85, 102); -->
<title>Station</title>

<div class="TitleHeader">
<table width="100%">
<tr>
<td style="text-align:left;" width="33%"><Img src="layout/dotc-logo.png" style="position:relative;text-align:left;height:40px;" /></td>
<td style="text-align:center;" width="34%">Station</td>
<td style="text-align:right;" width="33%"><Img src="layout/mrt3-logo.png" style="position:relative;text-align:left;height:40px;" /></td>
</tr>
</table>
</div>

<div class="nav-wrapper">
<ul class='nav'>
	<li><a href='#'>Primary</a>
		<ul>
			<li><a style='text-decoration:none;' href='daily_accomplishment.php'>Daily Accomplishment Report</a></li>
			<li><a style='text-decoration:none;' href='incident_summary.php'>Incident Report</a></li>
			<li><a style='text-decoration:none;' href='complaint_summary.php'>Passenger Complaint</a></li>
			<li><a style='text-decoration:none;' href='incidents_and_defective_monitoring.php'>Defective Equipment/Facilities Monitoring</a></li>
			<li><a style='text-decoration:none;' href='repair_facilities_monitoring.php'>Repaired Facilities and Equipment Monitoring</a></li>
			<li><a style='text-decoration:none;' href='ticket_seller_report.php'>Ticket Seller Report</a></li>
		</ul>
	</li>
	<li><a href='#'>Secondary</a>	
		<ul>
			<li><a style='text-decoration:none;' href='sales summary slip.php'>Sales based on SCS Data</a></li>  
			<!--<li><a style='text-decoration:none;' href='refund daily.php'>Refund Monitoring</a></li>  
			<li><a style='text-decoration:none;' href='ticket_encoding_monitoring.php'>Ticket Encoding Error Monitoring</a></li>
			-->
			<!-- <li><a style='text-decoration:none;' href='segregation_daily.php'>Segregation Monitoring</a></li> -->
			<li><a style='text-decoration:none;' href='manually collected.php'>Manually Collected Monitoring</a></li>
			<li><a style='text-decoration:none;' href='decorum daily.php'>Decorum Daily Report</a></li>
		</ul>
	</li>
	<li><a href='#'>Statistics</a></li>
	<li style="float:right;	border-left:1px solid #444;"><a href='index.php' >Log Out</a></li>	
</ul>
</div>
<!--
<ul>
	<li><a style='text-decoration:none;' href='equipment incident.php'>Equipment/Facility Incident</a></li>
	<li><a style='text-decoration:none;' href='passenger incident.php'>Passenger Incident/Complaint</a></li>
	<li><a style='text-decoration:none;' href='recommendations.php'>Recommendations</a></li>
	<li><a style='text-decoration:none;' href='sales and refund.php'>Actual Sales and Refund Report</a></li>
	<li><a style='text-decoration:none;' href='authorization entry.php'>Authorization Slip Report</a></li>
</ul>
-->
