<style type='text/css'>
ul {
  padding: 0;
  margin: 0;
  list-style: none;
}

ul li {
  float: left;
  position: relative;
  width: auto;
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

  .with_tree {
  
background: url('images/arrow.png') no-repeat;
  background-size:10%;
  -moz-background-size:10%;
  background-position:right;
  }
  
  
  ul li a {
color: rgb(85, 85, 102); 
  text-decoration: none;
font-weight: bold;


  }
  
  
li ul {
  display: none;
  position: absolute; 
  top: 1em;
  left: 0;
  margin-left:-.2em;
  margin-top:3;
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
h3 {
color: rgb(85, 85, 102);

}
ul ul li:hover ul {
display:inline-block;
left:auto;
}

ul ul li ul {
        display:none;
		visibility:hidden;
}

ul ul ul {
	margin-left:98%;
	top:0;
	
}


ul ul li:hover > ul { display:inline-block;  visibility:visible; }


</style>
<body>

<h3>Information Monitoring</h3><br>

<div >
<ul class='nav'>
<li>
<a style='text-decoration:none;' href='#'>Station</a>
	<ul>
<!--	<li><a style='text-decoration:none;' href='station/equipment incident.php'>Equipment/Facility Incident</a></li>
		<li><a style='text-decoration:none;' href='station/passenger incident.php'>Passenger Incident/Complaint</a></li>
		<li><a style='text-decoration:none;' href='station/sales report.php'>Actual Sales and Refund Report</a></li>
		<li><a style='text-decoration:none;' href='station/authorization slip.php'>Authorization Slip Report</a></li>  

-->
		<li><a style='text-decoration:none;' href='#'>Equipment/Facility Incident</a></li>
		<li><a style='text-decoration:none;' href='#'>Passenger Incident/Complaint</a></li>
		<li><a style='text-decoration:none;' href='#'>Actual Sales and Refund Report</a></li>
		<li><a style='text-decoration:none;' href='#'>Authorization Slip Report</a></li>  


	</ul>
</li>
<li>
<?php
	$browser=$_SERVER['HTTP_USER_AGENT'];
		

?>
<a  style='text-decoration:none;' href='transport/index.php'>Transport</a>
	<ul>
	<li class='with_tree'><a style='text-decoration:none;' href='#'>Control Center</a>
		<ul <?php if(preg_match('/Opera/i', $browser)){ echo "style='margin-left:21%; top:0;'; "; } ?>>
			<li><a href='transport/ccdr_summary.php'>CCDR Summary</a></li>
			<li><a href='transport/incident report.php'>Daily Report</a></li>
		</ul>	
	</li>
	<li><a style='text-decoration:none;' href='transport/onboard equipment.php'>On-Board Equipt. & Accessories</a></li>
	<li class='with_tree'><a  style='text-decoration:none;' href='#'>Train Report+</a>
		<ul <?php if(preg_match('/Opera/i', $browser)){ echo "style='margin-left:21%; top:0;'; "; } ?>>
		<li><a style='text-decoration:none;' href='transport/train_availability.php'>Train Availability</a></li>  
		<li><a style='text-decoration:none;' href='transport/train hourly_2.php'>Train Hourly Monitoring Report</a></li>
		</ul>
	</li>
	</ul>
</li>
<li>
<a  style='text-decoration:none;' href='#'>Safety and Security Unit</a> 
	<ul>
	<li><a style='text-decoration:none;' href='#'>Theft/Pickpocket Report</a></li>
	</ul>
</li>
</ul>

</div>

</body>