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

// set up some basic details
$analyses_list = single_column_array_builder_groupby($current_project.".analyses_list", "name");
sort($analyses_list, SORT_STRING);
$participant_list = single_column_array_builder_groupby($current_project.".participants", "ppt_id");
sort($participant_list, SORT_STRING);

// pick up the trial if we have selected a specific one
if(!isset($_GET['trial'])){$trial=1;}
if(isset($_GET['trial'])){$trial=$_GET['trial'];}

// pick up the participant if we have selected a specific one
if(!isset($_GET['participant'])){$participant=$participant_list[0];}
if(isset($_GET['participant'])){$participant=$_GET['participant'];}

// work out the trial list
$trial_list = single_column_array_builder_groupby($current_project.".".$participant."_aggregated", "trial_index");
sort($trial_list, SORT_NUMERIC);

// pick up the analyses if we have selected a specific one
if(!isset($_GET['analyses'])){$analyses=$analyses_list[0];}
if(isset($_GET['analyses'])){$analyses=$_GET['analyses'];}

// sort out the column list
parse_str(urldecode($_SERVER['QUERY_STRING']), $data);
$selected_column_list = $data['column_list'];

$default_column_list = array('trial', 'CURRENT_FIX_INDEX', 'CURRENT_fIX_DURATION',
 'value', 'response', 'rt', 'outcome');

if (is_array($selected_column_list)){
	$column_list = array_merge($selected_column_list, $default_column_list);
}
if (!is_array($selected_column_list)){
	$column_list = $default_column_list;
}

//////////////////////////////////////////////////////////////////////////////////////////////
// now control input etc.
/////////////////////////

function select_column_builder ($database, $list=array()){
 	
 	$array = header_array_builder($database);
 
	echo "<select multiple id='available' name='available'>";
	
	foreach($array as $head){echo "<option value='".$head."'>".$head."</option>";}

  	echo "</select>";
  	
  	echo "<select multiple id='selected' name='selected'></select>";
	
	echo "<script>
			$().ready(function() {
				$('#available').click(add_column);
				$('#selected').click(remove_column);		
 			});
		  </script>";
  }

if (isset($_GET['refresh']) && $_GET['refresh']=='runner_select_form'){
	
	/// this sets out what to select
    echo "<form id='change_project_form' name='change_project_form'>
	Note: This section is a work in progress.<br>    
    <p>Select what you want to inspect here.</p>";
   
    echo "Analysis:"; option_list_builder("analyses_list", $analyses_list, 'draw_table()', $analyses);
	echo "Participant:"; option_list_builder("participant_list", $participant_list, 'draw_table()', $participant);
	echo "Trial Number:"; option_list_builder("trial_list", $trial_list, 'draw_table()', $trial);    
	
	echo "<br><input type='button' onclick='slide_toggle()' value='Reveal/Hide List of Columns'>";
	
	echo "<div id='slide_section' style='display:none'>";
	
	select_column_builder($current_project.".".$participant);
	
	echo "</div>";
	    
    echo "</form>
	
    </div>";	
}

if (isset($_GET['action']) && $_GET['action']=='draw_table'){
	
	$result = mysql_query("select p.*, a.*, ag.* from ".$current_project.".".$participant." as p 
		left join ".$current_project.".analyses_output 
			AS a on (a.trial = p.trial_index and a.ppt_id='".$participant."' and a.runner='".$analyses."')
		left join ".$current_project.".".$participant."_aggregated 
			AS ag on (ag.trial_index = p.trial_index)
		where p.trial_index = ".$trial."");if(mysql_error()) { echo mysql_error();}
	
	echo "<table class='display' id='example'>";
    echo "<thead>";
	
	$headers_done = false;
	
	while($row = mysql_fetch_assoc($result)){
				
		// get column names for this row
		$heads = array_keys($row);	
		
		// draw table heading
		if($headers_done==false){
			echo "<table class='display' id='example'>";
    		echo "<thead>";
			
			foreach($heads as $head){
				if (in_array($head, $column_list)){
					echo "<th class='top'>";
					echo $head;
					echo "</th>";
				}
			}
			
			echo "</thead>";		
			echo "<tbody>";		
			$headers_done=true;
		}
		
		
		// this does each row and cell, as selected by the user
		echo "<tr>";
		
		foreach($heads as $head){
			if (in_array($head, $column_list)){
				echo "<td>";
				echo $row[$head];
				echo "</td>";	
			}		
		}
	
		echo "</tr>";
		
	}
	
	echo "</tbody>";
	
	echo "</table>";
	
	
}


?>

