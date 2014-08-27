<?php
session_start();
?>
<!-- Start --> 
<style type='text/css'>
/*
a:hover
{
background-color:gold;
}
*/

h1{ 
	/* color: #444; */
	color: red;
	font: 25px Verdana; 
	-webkit-font-smoothing: antialiased; 
	text-shadow: 0px 1px black; 
/*	margin: 10px 0 2px 0; 
	padding: 5px 0 6px 0; */
}


 .one_heading {
	border-bottom: 1px solid #FBCC2A; 

}

.exception {
	border-bottom: 1px solid #FBCC2A; 

}
h2.except {
	color: #444; 
	font: 20px Verdana; 
	-webkit-font-smoothing: antialiased; 
	text-shadow: 0px 1px black; 
	margin: 10px 0 2px 0; 
	padding: 5px 0 6px 0; 
}
/*
.exception td {
	border:1px solid white; 

}
*/

/*--- mine
h1, th, td {
	border:1px solid black;

}

td {
	padding: 5px;
}

h2{
	
	border-bottom: 1px solid #FBCC2A; 
	margin: 10px 0 2px 0; 
	padding: 5px 0 6px 0; 
}
 ---  end mine*/
 

</style>


<link href="dist/css/bootstrap.min.css" rel="stylesheet">
<link href="dist/css/bootstrap.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="dist/js/bootstrap.min.js"></script>

<!-- <div class='well'>

<table class='h1'>
<tr>
  <td><img src='mrt-logo.png' style='width:130px;height:80px;' /></td>
  <td width=500px style="text-indent:0.3em"><h2><font face="Century" color="black"><b>Control Center Operation</font></b></h2>
  
  </td><td valign=center width=55%><font style='font-size:25px;'><h2><b>Control Center Operation</b></h2></font>
  
</td> 
  <td width="50%">50</td>
</tr>
<tr>
  <td></td>
  <td></td> 
  <td>94</td>
</tr>
</table>
	
</table>	

</div> -->



<div class='well'>

<table class='exception' width=100%>
<tr>
<td width=5%>
<img src='mrt-logo.png' style='width:130px;height:80px;' />
</td><td valign=center width=55%><font face="Century"><h2><b>Control Center Operation</b></h2></font>
</td>
<!--
<td width=40% align=right valign=center>
<font style='font-size:25px;'><b><?php echo strtoupper($row['department_name']); ?></b></font>
</td> 
-->

</tr>
</table>


<!-- <div class='one_heading'>
</div> -->

<table width=100% >
<tr>
<!-- <td style='' align=left width="50%">
	<em><?php echo $mainPageMessage; ?></em>
</td> -->

<td>
	<a href='../index.php'><font face="Century" size="4"><b>Log Out</b></font></a>		
</td>

<td style='' align="right" width=50%>	
<font style='font-size:20px;'><b>
	<?php echo "Hello, ".$_SESSION['name']."!"; ?>
	</b></font></td>
	

</tr>

</table>

</div>

<!----- End --> 



<style type='text/css'>

/*
 ul {
  padding: 0;
  margin: 0;
  list-style: none;
  }
  

ul li {

  float: left;
  position: relative;
  width: 15em;
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
} */



/*--- Menu --- */

ul#navMenu {
padding:0px; 
margin:5px;
width:900px;
list-style:none;
position:relative
}


ul#navMenu ul {
position:absolute;
left:0; 
top:100%;
display:none;
padding:0px;
margin:0px;
}

ul#navMenu li {
display:inline;
float:left;
position:relative

}


ul#navMenu a {
text-decoration:none;
padding:10px 0px; 
width:160px;
background:#f5f5f5;
color:black;
border:1px solid #e3e3e3;
float:left;
text-align:center;
border-radius: 4px;
}


ul#navMenu a:hover {
background:#cccccc;
color:#333333
}

ul#navMenu li:hover ul {
display:block;

}

/* -- width box match navmenu a */
ul#navMenu ul a {
width:160px;
}


ul#navMenu ul li {
display:block;
margin:0px
}


ul#navMenu ul ul {
top:0;left:100%;
}

ul#navMenu li:hover ul ul {
display:none;
}

ul#navMenu ul li:hover ul {
display:block;
}

 /* Menu end */


</style> 

<ul id="navMenu" >
  <li><a href="#">Control Center Report+</a>
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
		<li><a href='#' >Statistics Report+</a>
		<ul>
			<li><a href='#' onclick="window.open('statistics_report_modified.php')">Train Equipment</a></li>
			<li><a href='#' onclick="window.open('car_statistics_report.php')">Rolling Stock (Cars)</a></li>
			<li><a href='#' >Stats Report (AFC)+</a>
			<ul>			
        		<?php 
				$db=new mysqli("localhost","root","","transport");
			
				$sql="select * from station";
				$rs=$db->query($sql);
				$nm=$rs->num_rows;
			
				for($i=0;$i<$nm;$i++){
					$row=$rs->fetch_assoc();
				?>
				<li ><a href='#'  onclick="window.open('statistics_report_afc.php?station=<?php echo $row['id']; ?>&statio					n_name=<?php echo $row['station_name']; ?>')"><?php echo $row['station_name']; ?></a> </li>
				<?php
				}
				?>
				<li><a href='#' onclick="window.open('statistics_report_afc.php?station=D&station_name=Depot')">Depot</a><					/li>
			</ul>
			</li>		
		</li>		
</ul>
	<li><a href='#'>Train Driver List+</a>	
	<ul>
	<li><a style='text-decoration:none;' href='createRecord.php'>Add Record</a></li>  
	<li><a style='text-decoration:none;' href='admin_page.php'>Edit/Delete Record</a></li>
	</ul>
	</li>
</ul>


<!--
<style>

body {
    padding-top: 10px;
}

/*-- bootsnipp --*/

.dropdown-submenu {
    position: relative;
}

.dropdown-submenu>.dropdown-menu {
    top: 0; 
    left: 100%;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
}

.dropdown-submenu>a:after {
    display: block;
    content: " ";
    float: right;
    width: 0;
    height: 0;
    border-color: transparent;
    border-style: solid;
    border-width: 5px 0 5px 5px;
    border-left-color: #ccc;
    margin-top: 5px;
    margin-right: -10px;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: -100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}




</style>

--


<!-- <a href='../index.php'>Log Out</a> 
<h3>Transport</h3> -->

<!--
<div class="navbar  navbar-default" role="navigation">
         
        <div class="collapse navbar-collapse" >
            <ul class="nav navbar-nav navbar-right">
                <li><a href="https://github.com/fontenele/bootstrap-navbar-dropdowns" target="_blank">GitHub Project</a></li>
            </ul>
            
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Menu 1 <b class="caret"></b></a>
                    <ul class="dropdown-menu multi-level">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li class="dropdown-submenu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-submenu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Action</a></li>
                                                <li><a href="#">Another action</a></li>
                                                <li><a href="#">Something else here</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Separated link</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">One more separated link</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Menu 2 <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                        <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                                <li class="divider"></li>
                                <li class="dropdown-submenu">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-submenu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown</a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Action</a></li>
                                                <li><a href="#">Another action</a></li>
                                                <li><a href="#">Something else here</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Separated link</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">One more separated link</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!--/.nav-collapse -->
   <!-- 
</div>

-->






<!-- <ul class='nav' style=margin-left:10px;>
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
	<li><a href='#' >Statistics Report</a>
	<ul>
		<li><a href='#' onclick="window.open('statistics_report_modified.php')">Train Equipment</a></li>
		<li><a href='#' onclick="window.open('car_statistics_report.php')">Rolling Stock (Cars)</a></li>

	
	
	
	</ul>
	</li>
	<li><a href='#' >Stats Report (AFC)+</a>
		<ul>
			<?php 
			$db=new mysqli("localhost","root","","transport");
			
			$sql="select * from station";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			
			for($i=0;$i<$nm;$i++){
				$row=$rs->fetch_assoc();
			?>
			<li ><a href='#'  onclick="window.open('statistics_report_afc.php?station=<?php echo $row['id']; ?>&station_name=<?php echo $row['station_name']; ?>')"><?php echo $row['station_name']; ?></a> </li>
			<?php
			}
			?>
			<li><a href='#' onclick="window.open('statistics_report_afc.php?station=D&station_name=Depot')">Depot</a></li>
		</ul>
	</li>



	<li><a href='#'>Train Driver List+</a>	
	<ul>
	<li><a style='text-decoration:none;' href='createRecord.php'>Add Record</a></li>  
	<li><a style='text-decoration:none;' href='admin_page.php'>Edit/Delete Record</a></li>

	</ul>
	</li>
</ul> --> 