<?php

 /* //////////////////////////////////////////////////////////////////////////////////////////////////
  * Author: Hayward J. Godwin, 2011
  
    This file is part of Inpsyte Data Analysis.

    Inpsyte Data Analysis is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 3, as published by
    the Free Software Foundation.

    Inpsyte Data Analysis is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Inpsyte Data Analysis.  If not, see <http://www.gnu.org/licenses/>. 
 */ //////////////////////////////////////////////////////////////////////////////////////////////////

	// this allows the page to run very long scripts (for data analysis)
	if (!get_cfg_var('safe_mode')) {set_time_limit(0);}
	
	// basic password information
	$username="root";
	$password="";
	mysql_connect(localhost,$username,$password) 
		or die ("MYSQL connection problem. Is your MySQL server running?");
	
	// check to see if this is a new installation or not
	$result=mysql_query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA 
			WHERE SCHEMA_NAME ='inpsyte__projects'");
	
	$rows = mysql_num_rows($result);
		
	// if the config db does not exist - i.e., we need to install
	if($rows==0 && !defined(INSTALL)){	
		header( 'Location: ../inpsyte/install.php' ) ;
	}

	// this gets the config information and selects the current database
	$config_result = mysql_query("SELECT name from inpsyte__projects.projects WHERE selected=1 LIMIT 1");
	
	while($config_row=mysql_fetch_array($config_result)){	  
	  $current_project = $config_row[0];
	  $current_project_description = $config_row[1];
	  $current_project_owner = $config_row[2];
	}
	
	// if our projects database is empty, select the first one
	if($current_project==''){
	  $config_result = mysql_query("SELECT name from inpsyte__projects.projects LIMIT 1");
				
	  while($config_row=mysql_fetch_array($config_result)){
	  
	  $current_project = $config_row[0];
	  $current_project_description = $config_row[1];
	  $current_project_owner = $config_row[2];
	  }
		
	}	


?>
