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

class runner_responses extends runner_control {
 
  function selection_query(){
    
    //$this->dummy_run=true;
    $this->generate_output=false;
    
    // this will be different for each runner type
    $this->correct_answer = $this->runner_data['correct_answer'];
    $this->output_label = $this->runner_data['output_label'];
    $this->comparison_value = $this->runner_data['comparison_value'];
    $this->input_column = $this->runner_data['input_column'];
    $this->group_by = $this->runner_data['group_by'];
    
    $this->restriction_query = "UPDATE ".$this->runner_interface->current_project.".".$this->participant." 
      SET ".$this->runner_interface->runner_type."_".$this->runner_interface->name."_include=1
       WHERE ".$this->input_column."<>'.'";
  }

  function runner_output_query(){
  
    // runner output table    
    $this->runner_query_string = "INSERT INTO ".$this->runner_interface->current_project.".".$this->runner_interface->runner_type."_output 
     (runner, ppt_id, participant, session_id, trial, response, rt, outcome)
     SELECT '".$this->runner_interface->name."', '".$this->participant."',
     '".$this->current_true_ppt_id."', '".$this->current_session."', ".$this->group_by.",
     '".$this->output_label."', ".$this->input_column.", IF(".$this->correct_answer." = ".$this->comparison_value.", 'CORRECT', 'INCORRECT' )
     FROM ".$this->runner_interface->current_project.".".$this->participant." 
     WHERE ".$this->runner_interface->runner_type."_".$this->runner_interface->name."_include=1 GROUP BY ".$this->group_by;  
  }

}

?>

