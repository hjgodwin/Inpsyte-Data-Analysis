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

include("../conf/conf.php");
include("../functions/single_column_array_builder.php");
include("../functions/header_array_builder.php");
include("../functions/option_list_builder.php");

if (isset($_GET['refresh'])){
    
//	this will only be blank when we have no projects, i.e. it's a new install
	    $project_list = single_column_array_builder("inpsyte__projects.projects", "name");  
	
		// change current active project
	    echo "<form id='change_project_form' name='change_project_form'>
	    
	    <h3>Change Current Project</h3>
	    
	    <p> This section is used for changing the current project in use by the application.</p>";
	   
	    option_list_builder("project_list", $project_list, '', $current_project);
	   
	    echo "<input type='button' onclick='change_project()' value='Change Now!'>
	    <input type='button' onclick='delete_project()' value='Delete Now!'></p>
	    
	    </form>
	
	    </article>";	
	
	// add a new project
	echo "<form id='add_project_form' name='add_project_form'>
    <h3>Add New Project</h3>
    <p> You can add a new project here.</p>";
   	echo "<input type='text' id='name' name='name' value='' class='required' minlength='2'>";   
    echo "  <input type='button' onclick='add_project()' value='Add Now!'>";
    //echo "  <input type='button' id='name' name='name' value='Add Now!'>";		
    echo "</p>
      
	<article id='add_project_output'>";
	
	if ($current_project!=''){ echo "<br>";}
	if ($current_project==''){ echo "<script>
	
	$('#change_project_form').fadeTo( 0.01, 0.3);
	alert('Welcome to INPSYTE Data Analysis. To begin with, create a new project.');
	</script>";}
	
	echo "</article>
	
    </form>
    
    </article>";
}

if (isset($_GET['action']) && $_GET['action']=='change_project'){
  
  $change_project = mysql_query("UPDATE inpsyte__projects.projects SET selected=0");
  $change_project = mysql_query("UPDATE inpsyte__projects.projects SET selected=1
                                    WHERE name='".$_GET['project']."'");
   
}

if (isset($_GET['action']) && $_GET['action']=='delete_project'){
  // delete attributes from config table	
  $delete_project = mysql_query("DELETE FROM inpsyte__projects.projects 
                                    WHERE name='".$_GET['project']."'");		
	
  // delete all tables	
  $delete_project = mysql_query("DROP DATABASE ".$_GET['project']);
   
  // set up array for additional project directories
  $folders = array("aois", "compiled_aois", "excel_output", "images", "tabbedrawdata", "trial_images");
  
  // delete these folders and their contents
  foreach ($folders as $folder){
  	// all files in folder	
  	foreach(glob("../projects/".$_GET['project']."/".$folder."/".'*.*') as $file){
    	unlink($file);
	}
  			
	// now delete folder  		
  	rmdir("../projects/".$_GET['project']."/".$folder."/");
  }  
  
  // now root dir for the project
  rmdir("../projects/".$_GET['project']."/");
  
}



if (isset($_GET['action']) && $_GET['action']=='add_project'){
  
  echo "../projects/".$_GET['project']."/";
  
  // build root directory for project
  mkdir("../projects/".$_GET['project']."/");
   
  // set up array for additional project directories
  $folders = array("aois", "compiled_aois", "excel_output", "images", "tabbedrawdata", "trial_images");
  
  // build additional folders
  foreach ($folders as $folder){
  	mkdir("../projects/".$_GET['project']."/".$folder."/");
  }  
   
  // update config database
  $add_project = mysql_query("INSERT INTO inpsyte__projects.projects 
  	SET name='".$_GET['project']."', owner='na'"); if(mysql_error){echo mysql_error();}
  
  // select our newly-added database
  $change_project = mysql_query("UPDATE inpsyte__projects.projects SET selected=1
                                    WHERE name='".$_GET['project']."'");if(mysql_error){echo mysql_error();}
                                    
  // now create our key tables
  $result=mysql_query("CREATE DATABASE ".$_GET['project']);
  
  $result=mysql_query("CREATE TABLE ".$_GET['project'].".analyses_attrib (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`runner_type` VARCHAR(20) NOT NULL,
	`runner_name` VARCHAR(20) NOT NULL,
	`attribute_name` VARCHAR(40) NOT NULL,
	`value` VARCHAR(40) NOT NULL,
	PRIMARY KEY (`id`))
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DEFAULT");if(mysql_error){echo mysql_error();}

  $result=mysql_query("CREATE TABLE ".$_GET['project'].".analyses_output (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`runner` VARCHAR(20) NOT NULL,
	`ppt_id` VARCHAR(15) NOT NULL,
	`participant` VARCHAR(15) NOT NULL,
	`session_id` VARCHAR(15) NOT NULL,
	`trial` VARCHAR(10) NOT NULL,
	`value` VARCHAR(10) NOT NULL,
	PRIMARY KEY (`id`))
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DYNAMIC");
 
  $result=mysql_query("CREATE TABLE ".$_GET['project'].".aois (
	`ppt_id` VARCHAR(20) NULL DEFAULT NULL,
	`trial_index` VARCHAR(15) NULL DEFAULT NULL,
	`shape` VARCHAR(100) NULL DEFAULT NULL,
	`ia_id` VARCHAR(100) NULL DEFAULT NULL,
	`X_1` VARCHAR(100) NULL DEFAULT NULL,
	`Y_1` VARCHAR(100) NULL DEFAULT NULL,
	`X_2` VARCHAR(100) NULL DEFAULT NULL,
	`Y_2` VARCHAR(100) NULL DEFAULT NULL,
	`IA_LABEL` VARCHAR(100) NULL DEFAULT NULL,
	`image_name` VARCHAR(100) NULL DEFAULT NULL)
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DEFAULT");

  $result=mysql_query("CREATE TABLE ".$_GET['project'].".completed_runner_list (
	`ppt_id` VARCHAR(20) NULL DEFAULT NULL,
	`runner` VARCHAR(20) NULL DEFAULT NULL)
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DEFAULT");

  $result=mysql_query("CREATE TABLE ".$_GET['project'].".output (
	`ppt_id` VARCHAR(10) NULL DEFAULT NULL,
	UNIQUE INDEX `ppt_id` (`ppt_id`))
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DYNAMIC");

  $result=mysql_query("CREATE TABLE ".$_GET['project'].".participants (
	`ppt_id` VARCHAR(10) NULL DEFAULT '0',
	`participant` VARCHAR(50) NOT NULL,
	`session_id` VARCHAR(50) NOT NULL,
	`view_trials` VARCHAR(10) NULL DEFAULT '0',
	UNIQUE INDEX `ppt_id` (`ppt_id`))
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DYNAMIC");
 
  $result=mysql_query("CREATE TABLE ".$_GET['project'].".responses_attrib (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`runner_type` VARCHAR(20) NOT NULL,
	`runner_name` VARCHAR(20) NOT NULL,
	`attribute_name` VARCHAR(40) NOT NULL,
	`value` VARCHAR(40) NOT NULL,
	PRIMARY KEY (`id`))
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DYNAMIC");

  $result=mysql_query("CREATE TABLE ".$_GET['project'].".analyses_list (
	`name` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Analysis Name')
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DYNAMIC");
	
  $result=mysql_query("CREATE TABLE ".$_GET['project'].".responses_list (
	`name` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Analysis Name')
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DYNAMIC");
 
  $result=mysql_query("CREATE TABLE ".$_GET['project'].".responses_output (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`runner` VARCHAR(20) NOT NULL,
	`ppt_id` VARCHAR(15) NOT NULL,
	`participant` VARCHAR(15) NOT NULL,
	`session_id` VARCHAR(15) NOT NULL,
	`trial` VARCHAR(10) NOT NULL,
	`response` VARCHAR(30) NOT NULL,
	`rt` VARCHAR(15) NOT NULL,
	`outcome` VARCHAR(30) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `trial` (`trial`))
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DYNAMIC");
  
 $result=mysql_query("CREATE TABLE ".$_GET['project'].".session_list (
	`name` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Analysis Name',
	UNIQUE INDEX `name` (`name`))
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DYNAMIC ");
  
  $result=mysql_query("CREATE TABLE ".$_GET['project'].".time_periods_attrib (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`runner_type` VARCHAR(20) NOT NULL,
	`runner_name` VARCHAR(20) NOT NULL,
	`attribute_name` VARCHAR(40) NOT NULL,
	`value` VARCHAR(40) NOT NULL,
	PRIMARY KEY (`id`))
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DYNAMIC"); 
  
  $result=mysql_query("CREATE TABLE ".$_GET['project'].".time_periods_list (
	`name` VARCHAR(20) NULL DEFAULT NULL COMMENT 'Analysis Name')
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DYNAMIC");
      
 $result=mysql_query("CREATE TABLE ".$_GET['project'].".time_periods_output (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`runner` VARCHAR(20) NOT NULL,
	`ppt_id` VARCHAR(15) NOT NULL,
	`participant` VARCHAR(15) NOT NULL,
	`session_id` VARCHAR(15) NOT NULL,
	`trial` VARCHAR(10) NOT NULL,
	`search_text` VARCHAR(20) NOT NULL,
	`temporal_order` VARCHAR(10) NOT NULL,
	`value` VARCHAR(10) NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `trial` (`trial`))
	COLLATE='latin1_swedish_ci'
	ENGINE=MyISAM
	ROW_FORMAT=DYNAMIC");
 
 
}


?>

