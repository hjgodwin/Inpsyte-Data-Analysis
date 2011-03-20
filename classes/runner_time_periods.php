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

class runner_time_periods extends runner_control {
 
  function selection_query(){
    
    //$this->dummy_run=true;
    $this->generate_output=false;
    
    // this will be different for each runner type
    $this->temporal_order = $this->runner_data['temporal_order'];
    $this->search_text = $this->runner_data['search_text'];
	$this->fix_sacs = $this->runner_data['fix_sacs'];
    $this->group_by = $this->runner_data['group_by'];	
      
  	$this->restriction_query = "INSERT INTO 
  	 ".$this->runner_interface->current_project.".".$this->runner_interface->runner_type."_output 
     (runner, ppt_id, participant, session_id, trial, search_text, temporal_order, value)
     SELECT '".$this->runner_interface->name."', p.INPSYTE__PPT_ID,
     p.INPSYTE__PPT_TRUE_ID, p.INPSYTE__SESSION_ID, p.".$this->group_by.",
     '".$this->search_text."' as label, '".$this->temporal_order."' as temporal_order,";
     
	 // set up the columns that we want to look at in terms
	 // of fixation or saccade messages (or both)
	 $this->fixations_select = false;
	 $this->fixations_select = false;
	 
	 if($this->fix_sacs=='both'){ $this->fixations_select = true; $this->saccades_select= true;}
	 if($this->fix_sacs=='fixations'){ $this->fixations_select = true; }
	 if($this->fix_sacs=='saccades'){ $this->saccades_select= true;}
	 
	 $schema = mysql_query("DESCRIBE ".$this->runner_interface->current_project.".datasets");
	 
	 $this->selected_event_colummns = array();
	 $this->selected_time_colummns = array();
	 
	 while($schema_row = mysql_fetch_array($schema)){
	 	
      	if($this->fixations_select == true && preg_match('/current_fix_msg_text_/i', $schema_row[0])){
      		$this->selected_event_colummns[]=$schema_row[0];     	
      	}
		
		if($this->fixations_select == true && preg_match('/current_fix_msg_time_/i', $schema_row[0])){
      		$this->selected_time_colummns[]=$schema_row[0];     	
      	}
		
		if($this->saccades_select == true && preg_match('/next_sac_msg_text_/i', $schema_row[0])){
      		$this->selected_event_colummns[]=$schema_row[0];     	
      	}
		
		if($this->saccades_select == true && preg_match('/next_sac_msg_time_/i', $schema_row[0])){
      		$this->selected_time_colummns[]=$schema_row[0];     	
      	}
	 }
	 
     $column_array = array();
     
	 // dynamic if statement for multiple event columns
     for ( $i = 0; $i < count($this->selected_event_colummns); $i+= 1) {
             
		  // min is used so we can get more than just the first row for that ppt/trial grouped variation
          $column_array[]= " IF(p.".$this->selected_event_colummns[$i]." 
            = '".$this->search_text."', p.".$this->selected_time_colummns[$i].", ";
              
     } 
	 
	 
     /*// dynamic if statement for multiple event columns
     for ( $i = 1; $i <= count($this->runner_data); $i+= 1) {
       if (array_key_exists('pair_'.$i.'_text_column', $this->runner_data)){
            
		  // min is used so we can get more than just the first row for that ppt/trial grouped variation
          $column_array[]= " IF(p.".$this->runner_data['pair_'.$i.'_text_column']." 
            = '".$this->search_text."', p.".$this->runner_data['pair_'.$i.'_time_column'].", ";
        }           
     } */
     
     // build these into if statement
     
     $this->restriction_query.="min( "; // min needed as a hack to deal with group by issues
     
     foreach ($column_array as $column){
       $this->restriction_query.=$column;
     }
     
     // close up the last 'false' conditional in the if statements
     $this->restriction_query.="NULL";
     
     // build in the close brackets
     for ( $i = 1; $i <= count($column_array); $i+= 1) {
       $this->restriction_query.=') ';
     }

     $this->restriction_query.=") "; // min needed as a hack to deal with group by issues: closing bracket

     // final part
     $this->restriction_query.=" AS event_time FROM ".$this->runner_interface->current_project.".datasets p
     GROUP BY p.INPSYTE__PPT_ID, p.TRIAL_INDEX";
		
	 echo $this->restriction_query;

  }

}

?>

