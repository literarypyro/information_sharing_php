<style type='text/css'>

ul {
  padding: 0;
  margin: 0;
  list-style: none;
  }

ul li {
  	background-color: white;
  float: left;
  position: relative;
  width: 10em;
  text-align:center;
 padding: 0.2em; 
 	min-width: 8em;
	margin-right: 0.5em;
	
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	-webkit-box-shadow: 3px 3px 3px 3px rgba(43, 43, 77, 0.5);
	-moz-box-shadow: 3px 3px 3px 3px rgba(43, 43, 77, 0.5);
	box-shadow: 3px 3px 3px 3px rgba(43, 43, 77, 0.5);
  }

  ul li a {
  	background-color: white;
color: rgb(85, 85, 102); 
  text-decoration: none;
font-weight: bold;


  }
  
  
li ul {
  display: none;
  position: absolute; 
  top: 1em;
  left: 0;
  margin-top:3;
  margin-left:-.2em;
  }
li ul li {
	text-align:left;
 padding: 0.2em; 
  margin-top:1;
	background-color:white;
 }
  
li > ul {
 top: auto;
 left: auto;
 }

li:hover ul { display: block; }

li ul li:hover {
	background-color:rgb(207,207,207);
	color:white;

}
li ul li:hover a{
	background-color:rgb(207,207,207);
	color:white;

}



</style>
<!--color: rgb(85, 85, 102); -->

<a href='index.php' >Log Out</a>
<h3>Station</h3><br>

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
<li><a style='text-decoration:none;' href='refund daily.php'>Refund Monitoring</a></li>  
<li><a style='text-decoration:none;' href='ticket_encoding_monitoring.php'>Ticket Encoding Error Monitoring</a></li>

<!-- <li><a style='text-decoration:none;' href='segregation_daily.php'>Segregation Monitoring</a></li> -->
<li><a style='text-decoration:none;' href='manually collected.php'>Manually Collected Monitoring</a></li>
<li><a style='text-decoration:none;' href='decorum daily.php'>Decorum Daily Report</a></li>
</ul>
</li>

<li><a href='#'>Statistics</a>	
</li>
	
</ul>

<!--
<ul>
	<li><a style='text-decoration:none;' href='equipment incident.php'>Equipment/Facility Incident</a></li>
	<li><a style='text-decoration:none;' href='passenger incident.php'>Passenger Incident/Complaint</a></li>
	<li><a style='text-decoration:none;' href='recommendations.php'>Recommendations</a></li>
	<li><a style='text-decoration:none;' href='sales and refund.php'>Actual Sales and Refund Report</a></li>
	<li><a style='text-decoration:none;' href='authorization entry.php'>Authorization Slip Report</a></li>
</ul>
-->
