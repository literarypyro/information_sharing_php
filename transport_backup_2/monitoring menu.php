<style type='text/css'>

ul {
  padding: 0;
  margin: 0;
  list-style: none;
  }

ul li {

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
h3 {
color: rgb(85, 85, 102);

}
</style>
<a href='../index.php'>Log Out</a>
<h3>Transport</h3>

<ul class='nav'>
	<li><a href='#'>Control Center Report+</a>
	<ul>
		<li><a href='incident report.php'>Daily Report</a></li>
		<li><a href='edit_ccdr.php'>Edit CCDR</a></li>
		<li><a href='ccdr_summary.php'>CCDR Summary</a></li>
		<li><a href='incident summary.php'>View Daily Incident Summary</a></li>
		
	</ul>
	</li>
	<li><a href='#'>Train+</a>	
	<ul>
	<li><a style='text-decoration:none;' href='train_availability.php'>Train Availability</a></li>  
	<li><a style='text-decoration:none;' href='train hourly.php'>Train Hourly Monitoring Report</a></li>
	<li><a style='text-decoration:none;' href='onboard equipment.php'>Onboard Equipment and Accessories</a></li>

	</ul>
	</li>	
	<li><a href='clearance form.php'>Clearance Form</a></li>
	<li><a href='#' onclick="window.open('statistics_report.php')">Statistics Report</a></li>

</ul>


  