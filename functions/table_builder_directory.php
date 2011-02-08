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

function table_builder_directory ($folder){

    // import global references
    global $current_project;

    # first we work out who has already been imported and added to the database
    # we will ignore their files because we don't want to add them again

    $result = mysql_query("SELECT ppt_id from ".$current_project.".participants");

    $participant_array = array();
    $available_participant_list = array();

    if (mysql_num_rows($result)>0){
      while($row = mysql_fetch_array($result)){
          $participant_array[] = $row[0];
      }
    }
    else {$participant_array = array("");}
    
    echo "<table>";
    echo "<tr>";
    echo "<td class='top'>";
    echo "Filename";
    echo "</td>";
    echo "</tr>";

 
    if ($handle_seeker = opendir($folder.'/')) {
    while (false !== ($file = readdir($handle_seeker))) {
        if ($file != "." && $file != "..") {
            $ppt_id = explode(".", $file);

            if (!in_array($ppt_id[0], $participant_array)){
	            $available_participant_list[] = $file;//add this to the available list
	            echo "<tr>";
	            echo "<td>";
	            echo $ppt_id[0];
	            echo "</td>";
	            echo "</tr>";
            }
        }
    }
    closedir($handle_seeker);
}
  
    echo "</table>";
        
}

# identical to above but has no table drawing. used for adding ppts.
function file_selector($folder){

    // import global references
    global $current_project;

    # first we work out who has already been imported and added to the database
    # we will ignore their files because we don't want to add them again

    $result = mysql_query("SELECT ppt_id from ".$current_project.".participants");

    $participant_array = array();
    $available_participant_list = array();

    while($row = mysql_fetch_array($result)){

        $participant_array[] = $row[0];

    }

   
    if ($handle_seeker = opendir($folder.'/')) {
    while (false !== ($file = readdir($handle_seeker))) {
        if ($file != "." && $file != "..") {
            $ppt_id = explode(".", $file);

            if (!in_array($ppt_id[0], $participant_array)){

            $available_participant_list[] = $ppt_id[0];#add this to the available list

           }
        }
    }
    closedir($handle_seeker);
}

    return $available_participant_list;


}
?>
