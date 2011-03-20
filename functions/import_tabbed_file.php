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
	
		$header_query = "ALTER TABLE ".$current_project.".datasets ";
	
	    # create the headers
	    for ($j=0; $j<count($headers); $j++) {
	        if ($j>0){$header_query.=", ";}	
	        $header_query.="  ADD ".$headers[$j]." VARCHAR(30)";
	    }
	
		$result = mysql_query($header_query);
	
	    $result = mysql_query("ALTER TABLE ".$current_project.".datasets DROP dummy");

		// session and ppt_id columns added here
		$result = mysql_query("ALTER TABLE ".$current_project.".datasets 
			ADD INPSYTE__PPT_ID VARCHAR(50)	DEFAULT NULL, 
			ADD INPSYTE__PPT_TRUE_ID VARCHAR(50)	DEFAULT NULL,
			ADD INPSYTE__SESSION_ID VARCHAR(10) DEFAULT NULL");

		$result =mysql_query("ALTER TABLE ".$current_project.".datasets ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY");
		
		// add index for speed - this column will be need to be set by the user in the future
		// now a composite index for more speed in joins
        $index = mysql_query("ALTER TABLE ".$current_project.".datasets 
        	ADD INDEX INPSYTE__PPT_ID (INPSYTE__PPT_ID, TRIAL_INDEX)");           
	}

	

    $filename_full = addslashes($dir."/".$ppt_id.".txt");

    $result = mysql_query("LOAD DATA LOCAL INFILE '".$filename_full."'
        INTO TABLE ".$current_project.".datasets
        FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES");
    if(mysql_error()) { echo mysql_error(); exit();}
    
}


?>
