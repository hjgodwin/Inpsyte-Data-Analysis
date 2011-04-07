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

function import_aois($participant, $original_dir){ //note: get this to work out how many trials there are somehow

   // import global references
   global $current_project;

   chdir($original_dir);
   chdir('..');
   chdir("projects/".$current_project."/aois");
   
   $aois_present = false;
   
   if ($aoi_dir_handle = opendir('.')) {
   		
   	while (false !== ($file = readdir($aoi_dir_handle)) && $aois_present == false) {
        if ($file != "." && $file != "..") {
        	$aois_present = true;
        }
    }
    closedir($aoi_dir_handle);
   }
   
   // if aois exist, then go ahead and import them
   if ($aois_present== true){
   	
	chdir(getcwd().'/'.$participant.'/aoi');
	
	$individual_aoi_dir = opendir(getcwd());
		while ($file = readdir($individual_aoi_dir)) { 
  			if (eregi("\.ias",$file)) { 
    			echo $file."<BR>";
    			
				$aoi_pre = explode('.', $file);
				$aoi_sec = explode('_', $aoi_pre[0]);
				$trial_id = $aoi_sec[1];
								
				$compiled_aoi_filename = addslashes(getcwd()."/".$file);
    			$result = mysql_query("LOAD DATA LOCAL INFILE '".$compiled_aoi_filename."' INTO TABLE 
    				".$current_project.".aois FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n'");
						
				$result = mysql_query("UPDATE ".$current_project.".aois SET ppt_id='".$participant."', 
					trial_index='".$trial_id."' WHERE ppt_id IS NULL and trial_index is NULL");
				 
				
  			}
		}
	
	closedir($individual_aoi_dir);
   }
   
    chdir($original_dir); // this takes us back to the dir we were in originally so everything else can work
}

?>
