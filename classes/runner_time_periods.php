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
    $this->group_by = $this->runner_data['group_by'];
    
    $this->restriction_query = "UPDATE ".$this->runner_interface->current_project.".".$this->participant." 
      SET ".$this->runner_interface->runner_type."_".$this->runner_interface->name."_include=";

    // this sets inclusion.
    // important note. 
    // it evaluates the set of ORs and says '1' if ANY of them return true
    // see below for runner_output_query to get a proper if/or mysql statement working
    for ( $i = 1; $i <= count($this->runner_data); $i+= 1) {

       if (array_key_exists('pair_'.$i.'_text_column', $this->runner_data)){
            
          if ($i>1){
            $this->restriction_query .= " OR ";
          }

          $this->restriction_query .= "IF(".$this->runner_data['pair_'.$i.'_text_column']." 
            = '".$this->search_text."', 1, '') ";
        }           
    } 
    
  }

  function runner_output_query(){
  
    // runner output table - main trunk
    $this->runner_query_string = " INSERT INTO ".$this->runner_interface->current_project.".".$this->runner_interface->runner_type."_output 
     (runner, ppt_id, participant, session_id, trial, search_text, temporal_order, value)
     SELECT '".$this->runner_interface->name."', '".$this->participant."',
     '".$this->current_true_ppt_id."', '".$this->current_session."', ".$this->group_by.",
     '".$this->search_text."', '".$this->temporal_order."',";
     
     $column_array = array();
     
     // dynamic if statement for multiple event columns
     for ( $i = 1; $i <= count($this->runner_data); $i+= 1) {
       if (array_key_exists('pair_'.$i.'_text_column', $this->runner_data)){
            
          $column_array[]= " IF(".$this->runner_data['pair_'.$i.'_text_column']." 
            = '".$this->search_text."', ".$this->runner_data['pair_'.$i.'_time_column'].", ";
        }           
     } 
     
     // build these into excel-like if statement
     foreach ($column_array as $column){
       $this->runner_query_string.=$column;
     }
     
     // close up the last 'false' conditional in the if statements
     $this->runner_query_string.="''";
     
     // build in the close brackets
     for ( $i = 1; $i <= count($column_array); $i+= 1) {
       $this->runner_query_string.=')';
     }

     // final part
     $this->runner_query_string .= " FROM ".$this->runner_interface->current_project.".".$this->participant." 
     WHERE ".$this->runner_interface->runner_type."_".$this->runner_interface->name."_include=1 GROUP BY ".$this->group_by;  

  }

}

?>

