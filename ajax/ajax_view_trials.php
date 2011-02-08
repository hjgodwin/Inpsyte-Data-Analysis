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
include("../functions/single_column_array_builder.php");
include("../functions/header_array_builder.php");
include("../functions/option_list_builder.php");
include("../functions/trial_display.php");

// if no one selected, auto-select first ppt
if (!isset($_GET['participant'])) {
    $participant_select = "SELECT * from ".$current_project.".participants LIMIT 1";
    $participant_result = mysql_query($participant_select);
    while($row2 = mysql_fetch_array($participant_result)){$participant_name=$row2['ppt_id'];}
    }

if (isset($_GET['participant'])){
    $participant = $_GET['participant'];
    }

// get full details for this ppt
   $ppt_result = mysql_query("SELECT * from ".$current_project.".participants WHERE ppt_id='".$participant."'");
   while($row3 = mysql_fetch_array($ppt_result)){
       $participant_name=$row3['ppt_id'];
       $view_trials_complete = $row3['view_trials'];}

if ($_GET['refresh']=='participant_list'){

    // get the ppts and lsit them
    $participant_list = single_column_array_builder("".$current_project.".participants", "ppt_id");

    # Start building the form now
    echo "<article id='participant_list_form' name='participant_list_form'>";
    echo "<form>";
    echo "Select Participant:"; option_list_builder('selected_participant', $participant_list, '', $participant_name);echo "<br>";
    echo "<input type='button' onclick='select_participant()' value='View Trials'>";
    echo "</form>";
    echo "</article>";
}

if (isset($_GET['participant'])){
// if images not processed, do them now
   if($view_trials_complete==0){
       $current_dir = getcwd();
       chdir('..');
       $root_dir = getcwd();
       chdir('images');
       $image_dir = getcwd();
       chdir('..');
       chdir('trial_images');
       $trial_image_root_dir = getcwd();
       @mkdir($participant_name);
       chdir($participant_name);
       $output_dir = getcwd();
       chdir($current_dir);}


   $trials_result = mysql_query("SELECT trial_index FROM ".$current_project.".".$participant_name." WHERE INTERRUPTION_SAC IS NOT NULL group by trial_index");

   while($row3 = mysql_fetch_array($trials_result)) {
        $new_trial=$row3['trial_index'];
        
        if($view_trials_complete==0){@trial_display($participant_name, $new_trial, $image_dir, $output_dir);}
        
        echo "<image class='trial' width=800 height=600 src='../gemaa/trial_images/".$participant_name."/".$participant_name."_".$new_trial.".jpg'>";
        
        }

   $trials_update = mysql_query("UPDATE ".$current_project.".participants SET view_trials='1'");
        
}

?>
