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
include("../functions/table_builder.php");
include("../functions/table_builder_directory.php");
include("../functions/import_tabbed_file.php");
include("../functions/single_column_array_builder.php");
include("../functions/header_array_builder.php");
include("../functions/option_list_builder.php");
include("../functions/import_aois.php");
include("../functions/get_current_details.php");

if (isset($_GET['refresh']) && $_GET['refresh']=='current_participants'){
    table_builder("".$current_project.".participants");
}

if (isset($_GET['refresh']) && $_GET['refresh']=='tabbed_directory_list'){

    table_builder_directory("../projects/".$current_project."/tabbedrawdata");

    # Start building the form for adding all participants now
    echo "<form>";
	echo "Above is the list of participants you are about to add to the project. Having large files
	or large numbers of participants will make this process take longer.";
    echo "<input type='button' onclick='add_participants()' value='I am Ready. Add them now!'>";
    echo "<div id='add_participant_output'></div>";
    echo "</form>";
    echo "</div>";
}

if (isset($_GET['refresh']) && $_GET['refresh']=='add_participants'){

    $available_participant_list = array();
    $available_participant_list = file_selector("../projects/".$current_project."/tabbedrawdata");

    if (count($available_participant_list>0)) {
        foreach ($available_participant_list as $ppt_id) {
               
           // import basic files etc.  
           //import_aois($ppt_id, 320, getcwd());
           //chdir('..');
           chdir("../projects/".$current_project."/tabbedrawdata");$current_dir = getcwd();
           import_tabbed_file($ppt_id, $current_dir);
           
           // add index for speed
           $index = mysql_query("ALTER TABLE ".$current_project.".".$ppt_id." ADD INDEX TRIAL_INDEX (TRIAL_INDEX)");
                  
           // work out what session this is
           $current_session = get_current_session($ppt_id);
    
           // work out true id of participant
           $current_true_ppt_id = get_current_true_participant_id($ppt_id);
  
           // add this to the output database : note the true ppt id is the unique key here
           $add_output = mysql_query("INSERT IGNORE INTO ".$current_project.".output 
            SET ppt_id='".$current_true_ppt_id."'");  if(mysql_error){echo mysql_error();}
           
           // add this to the participant database: note the individual file is the unique key here
           $add_ppt = mysql_query("INSERT IGNORE INTO ".$current_project.".participants 
            SET ppt_id='".$ppt_id."', session_id='".$current_session."', participant='".$current_true_ppt_id."'");
              if(mysql_error){echo mysql_error();}
           
           // add this to the list of sessions
           $add_session = mysql_query("INSERT IGNORE INTO ".$current_project.".session_list 
            SET name='".$current_session."'");  if(mysql_error){echo mysql_error();}
           }
    }
}

if (isset($_GET['refresh']) && $_GET['refresh']=='delete_participant_section'){

    $participant_list = single_column_array_builder("".$current_project.".participants", "ppt_id");

	if (count($participant_list)>0){
	    // Start building the form now
	    echo "<div><h1>Delete Participants</h1></div>";
	    echo "<div id='delete_participants_form'>";
	    echo "<form>";
	    echo "Participant ID:"; option_list_builder('select_participant_option', $participant_list, '', "");echo "<br>";
	    echo "<input type='button' onclick='delete_participant()' value='Delete Selected Participant'>";
	    echo "<input type='button' onclick='delete_all_participants()' value='Delete All Participants'>";
	    echo "</form>";
	    echo "</div>";
	}
}

if (isset($_POST['refresh']) && $_POST['refresh']=='delete' && isset($_POST['participant'])){

    $ppt_id = $_POST['participant'];

    $participant_list=array();

    if ($ppt_id !='all') {
      $participant_list[]=$ppt_id;
    }
    if ($ppt_id =='all'){
      $participant_list = single_column_array_builder($current_project.".participants", "ppt_id");
    }

    foreach($participant_list as $participant) {
        // work out true id of participant
        $current_true_ppt_id = get_current_true_participant_id($participant);  
          
        $result = mysql_query("DROP TABLE IF EXISTS ".$current_project.".".$participant."");
        $result = mysql_query("DROP TABLE IF EXISTS ".$current_project.".".$participant."_aggregated");
        $result = mysql_query("DELETE FROM ".$current_project.".participants WHERE ppt_id='".$participant."'");
        $result = mysql_query("DELETE FROM ".$current_project.".aois WHERE ppt_id='".$participant."'");
        $result = mysql_query("DELETE FROM ".$current_project.".analyses_output WHERE ppt_id='".$participant."'");
        $result = mysql_query("DELETE FROM ".$current_project.".responses_output WHERE ppt_id='".$participant."'");
        $result = mysql_query("DELETE FROM ".$current_project.".time_periods_output WHERE ppt_id='".$participant."'");
        $result = mysql_query("DELETE FROM ".$current_project.".completed_runner_list WHERE ppt_id='".$participant."'");
        $result = mysql_query("DELETE FROM ".$current_project.".output WHERE ppt_id='".$current_true_ppt_id."'");
        
        chdir('..');
        $aoi_file_delete = getcwd().'/projects/'.$current_project.'/compiled_aois/aoi'.$participant.'.txt';
        unlink($aoi_file_delete);
        }
}


?>
