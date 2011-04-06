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



function datasets_table_exists($dataset){
    // used to check whether temporary table currently exists or not.
    $result=@mysql_query("SELECT * FROM ".$dataset." LIMIT 1");
    
    $exists = false;
    
    while($row = @mysql_fetch_array($result)){
      $exists = true;     
    }
    
    return $exists;
  }

function import_tabbed_file ($ppt_id, $dir){

    // import global references
    global $current_project;

    
	$datasets_exists = datasets_table_exists($current_project.".datasets");
	
	if($datasets_exists==false){
    	$result = mysql_query("CREATE TABLE IF NOT EXISTS ".$current_project.".datasets ( dummy INT(10) NULL )");	
	
	    $fcontents = file ("../tabbedrawdata/".$ppt_id.".txt");
	    $firstline = $fcontents[0];
	    $headers = explode("\t", $firstline);
	
		// now that we have the headers, we need to check the following:
		// 1) that there are no duplicates
		// 2) that the header names don't conflict with anything that we need (i.e., 'id')
		
		// this will be the list of headers actually created
		$final_header_list = array();
	
		// this is the list of reserved headers that can't be created
		$reserved_header_list = array('id');
		
		foreach($headers as $header){
			
			$header = trim($header);
			
			//echo $header; 
			//echo "<br>";
			
			if(!in_array($header, $final_header_list) && !in_array($header, $reserved_header_list)){
				$new_name_needed = false;
			}
			
			if((in_array($header, $final_header_list) || in_array($header, $reserved_header_list))){
				$new_name_needed = true;	
			}
			
			if ($new_name_needed == false){
				$final_header_list[] = $header;					
			}
			
			if ($new_name_needed == true){
			 
			 	$new_header = 'EMPTY_HEADER';
			 
			 	//echo "trying to add new header...<br>";
			 
			 	$header_increment = 1;		 	
			
			    for ( $header_increment = 1; $header_increment <= 1000; $header_increment += 1) {
					
					//echo $header_increment." generating new header<br>";				
					$new_header = $header .'_'.$header_increment;
					
					if (!in_array($new_header, $final_header_list) && !in_array($new_header, $reserved_header_list)){
						$final_header_list[] = $new_header;
						break;
					}
				}
								
			}						
		}
		
		$header_query = "ALTER TABLE ".$current_project.".datasets ";
		
	    $one_added = false;
		
	    foreach ($final_header_list as $header){
	    	if (strlen($header)>0){
		    	if ($one_added == true){
		    		$header_query.=", ";
				}	
				$header_query.="  ADD ".$header." VARCHAR(30)";
				$one_added = true;
			}
	    };
	

		// echo $header_query;
	
		$result = mysql_query($header_query); if(mysql_error()) { echo mysql_error(); exit();}
	
	    $result = mysql_query("ALTER TABLE ".$current_project.".datasets DROP dummy"); if(mysql_error()) { echo mysql_error(); exit();}

		// session and ppt_id columns added here
		$result = mysql_query("ALTER TABLE ".$current_project.".datasets 
			ADD INPSYTE__PPT_ID VARCHAR(50)	DEFAULT NULL, 
			ADD INPSYTE__PPT_TRUE_ID VARCHAR(50)	DEFAULT NULL,
			ADD INPSYTE__SESSION_ID VARCHAR(10) DEFAULT NULL"); if(mysql_error()) { echo mysql_error(); exit();}

		$result =mysql_query("ALTER TABLE ".$current_project.".datasets ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY"); if(mysql_error()) { echo mysql_error(); exit();}
		
		// add index for speed - this column will be need to be set by the user in the future
		// now a composite index for more speed in joins
        $index = mysql_query("ALTER TABLE ".$current_project.".datasets 
        	ADD INDEX INPSYTE__PPT_ID (INPSYTE__PPT_ID, TRIAL_INDEX)"); if(mysql_error()) { echo mysql_error(); exit();}          
	}

	

    $filename_full = addslashes($dir."/".$ppt_id.".txt");

    $result = mysql_query("LOAD DATA LOCAL INFILE '".$filename_full."'
        INTO TABLE ".$current_project.".datasets
        FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES");
    if(mysql_error()) { echo mysql_error(); exit();}
    
}


?>
