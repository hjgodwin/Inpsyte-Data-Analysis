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

class edit_analyses extends edit_control {
 
  function form_filler(){
    // Default for restriction count
    $this->restriction_count = 0;
	
    // if we are adding, set the values for cleanness. it sets some defaults
    if ($this->action_value=='add'){
        $this->analysis_name='';
        $this->computation = 'AVG';
        $this->input_column = 'CURRENT_FIX_DURATION';
        $this->group_by = 'TRIAL_INDEX';
        
        $this->restriction_columns = array();
        $this->restriction_symbols = array();
        $this->restriction_texts = array();   
        
        $this->responses = array();
        $this->time_periods = array();
        $this->outcomes = array();
        
    }
    
    // if we are editing, we fill in the values
    if ($this->action_value=='edit'){
      
        $edit_data = $this->get_runner_attribs($this->name_to_edit);    
        
        $this->analysis_name=$this->name_to_edit;     
        $this->computation = $edit_data['computation'];
        $this->input_column = $edit_data['input_column'];
        $this->group_by = $edit_data['group_by'];
                       
        $this->restriction_columns=array();
        $this->restriction_symbols=array();
        $this->restriction_texts=array();
        
        $this->responses = array();
        $this->time_periods = array();
        $this->outcomes = array();
        
        // restriction count for edits, this will be over-ridden below if we have asked to add more
        $this->restriction_count = 0;
        
        // handle infinite number of restrictions
        for ( $i = 1; $i <= count($edit_data); $i+= 1) {             
            if (array_key_exists('restriction_'.$i.'_column', $edit_data)){
              $this->restriction_columns[]=$edit_data['restriction_'.$i.'_column'];
              $this->restriction_symbols[]=$edit_data['restriction_'.$i.'_symbol'];
              $this->restriction_texts[]=$edit_data['restriction_'.$i.'_text'];
              $this->restriction_count++;
            }           
        }
                 
    }

    if ($this->action=='change_restrictions'){
          
      parse_str(urldecode($_SERVER['QUERY_STRING']), $edit_data);
        
      $this->analysis_name=$edit_data['name'];     
      $this->computation = $edit_data['computation'];
      $this->input_column = $edit_data['input_column'];
      $this->group_by = $edit_data['group_by'];

      $this->responses = $edit_data['response']; // this part works differently to the rest
      $this->time_periods = $edit_data['time_period']; // because of the arrays sent via checkboxes
      $this->outcomes = $edit_data['outcome'];
      
      $this->restriction_count = $edit_data['count'];

      if ($this->action_value=='more'){
        $this->restriction_count++;
      }
      
      if ($this->action_value=='less'){
        $this->restriction_count--;
      }
                
      $this->restriction_columns=array();
      $this->restriction_symbols=array();
      $this->restriction_texts=array();      
        
      for ( $i = 1; $i <= $this->restriction_count; $i+= 1) {            
        $this->restriction_columns[]=$edit_data['restriction_'.$i.'_column'];
        $this->restriction_symbols[]=$edit_data['restriction_'.$i.'_symbol'];
        $this->restriction_texts[]=$edit_data['restriction_'.$i.'_text'];
        }   
    }
    
    if ($this->action!='change_restrictions'){
      // handle infinite number of responses
      for ( $i = 1; $i <= count($edit_data); $i+= 1) {             
          if (array_key_exists('response_'.$i, $edit_data)){
            $this->responses[]=$edit_data['response_'.$i];
          }           
      }
          
      // handle infinite number of time periods
      for ( $i = 1; $i <= count($edit_data); $i+= 1) {             
          if (array_key_exists('time_period_'.$i, $edit_data)){
            $this->time_periods[]=$edit_data['time_period_'.$i];
          }           
      }
          
      // handle infinite number of outcomes
      for ( $i = 1; $i <= count($edit_data); $i+= 1) {             
          if (array_key_exists('outcome_'.$i, $edit_data)){
            $this->outcomes[]=$edit_data['outcome_'.$i];
          }           
      }        
    }
    
    // now build the form with our values!
    $this->form_textbox("name", "Analysis Name", $this->analysis_name);
    $this->form_optionlist("computation", "Computation", $this->computation_list, '', $this->computation);
    $this->form_optionlist("input_column", "Input Column", $this->column_list, '', $this->input_column);
    $this->form_optionlist("group_by", "Group By", $this->column_list, '', $this->group_by);
    
    if (count($this->response_list)>0){
    	$this->form_checkbox("response", "Response", $this->response_list, $this->responses);
		$this->form_checkbox("outcome", "Trial Outcome", $this->outcome_list,$this->outcomes);
	}
	if (count($this->time_period_list)>0){
    	$this->form_checkbox("time_period", "Time Period", $this->time_period_list,$this->time_periods);
	}
    
    $this->restriction_builder($this->restriction_count, $this->restriction_columns, $this->restriction_symbols, $this->restriction_texts);
     
  }

 
       
}



?>

