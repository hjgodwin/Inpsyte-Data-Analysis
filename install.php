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

	// basic password information
	$username="root";
	$password="";
	mysql_connect(localhost,$username,$password) 
		or die ("MYSQL connection problem. Is your MySQL server running?");

	// create key databases
	$result=mysql_query("CREATE DATABASE inpsyte__projects");
		if(mysql_error){echo mysql_error();}
		
	$result= mysql_query("CREATE TABLE inpsyte__projects.projects (
		`id` INT(100) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR(50) NOT NULL,
		`owner` VARCHAR(100) NOT NULL,
		`selected` INT(10) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`))");
		if(mysql_error){echo mysql_error();}

	// if everything is fine, head to the overview page
	header( 'Location: index.php?viewer=projects' ) ;

?>
