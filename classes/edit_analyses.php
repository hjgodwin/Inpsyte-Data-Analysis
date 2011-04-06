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
        	
		if ($this->runner_type=='analyses'){
	        $this->analysis_name='';
	        $this->computation = 'AVG';
	        $this->input_column = 'CURRENT_FIX_DURATION';
	        $this->group_by = 'TRIAL_INDEX';
	        
		}
		
		if ($this->runner_type=='custom'){
			$this->name = '';
			$this->custom_label = ''; // label is the column name
			$this->output_type = 'calculation';
			$this->output_value = '';
			$this->output_text = '';
			$this->computation = '*';
	        $this->input_column = 'CURRENT_FIX_DURATION';
			$this->output_manual = '';
		}
		
		// the below are shared by both analyses and custom analyses
	    $this->restriction_columns = array();
	    $this->restriction_symbols = array();
	    $this->restriction_texts = array();   
	    $this->restriction_types=array();
		$this->restriction_manual_texts = array();
			
	    $this->responses = array();
	    $this->time_periods = array();
	    $this->outcomes = array();
    }
    
    // if we are editing, we fill in the values
    if ($this->action_value=='edit'){
      
        $edit_data = $this->get_runner_attribs($this->name_to_edit);    
       
	    if ($this->runner_type=='analyses'){
		    
	        $this->analysis_name=$this->name_to_edit;     
	        $this->computation = $edit_data['computation'];
	        $this->input_column = $edit_data['input_column'];
	        $this->group_by = $edit_data['group_by'];
		}
		
		if ($this->runner_type=='custom'){
			$this->name=$this->name_to_edit;     
	        $this->custom_label = $edit_data['custom_label']; 
			$this->output_type = $edit_data['output_type'];
			$this->computation = $edit_data['computation'];
	        $this->input_column = $edit_data['input_column'];
			$this->output_value = $edit_data['output_value'];
			$this->output_text = $edit_data['output_text'];
			$this->output_manual = $edit_data['output_manual'];
		}
		
		// below are shared by both analyses and custom analyses              
        $this->restriction_columns=array();
        $this->restriction_symbols=array();
        $this->restriction_texts=array();
		$this->restriction_types=array();
		$this->restriction_manual_texts = array();
        
        $this->responses = array();
        $this->time_periods = array();
        $this->outcomes = array();
        
        // restriction count for edits, this will be over-ridden below if we have asked to add more
        $this->restriction_count = 0;
        
        // handle infinite number of restrictions
        for ( $i = 1; $i <= count($edit_data); $i+= 1) {             
            if (array_key_exists('restriction_'.$i.'_type', $edit_data)){
              $this->restriction_columns[]=$edit_data['restriction_'.$i.'_column'];
              $this->restriction_symbols[]=$edit_data['restriction_'.$i.'_symbol'];
              $this->restriction_texts[]=$edit_data['restriction_'.$i.'_text'];
			  $this->restriction_types[]=$edit_data['restriction_'.$i.'_type'];
			  $this->restriction_manual_texts['restriction_'.$i.'_manual']= $edit_data['restriction_'.$i.'_manual']; 
              $this->restriction_count++;
            }           
        }
                 
    }

    if ($this->action=='change_restrictions' || $this->action=='change_restriction_type' 
    	|| $this->action=='change_custom_type'){
          
      parse_str(urldecode($_SERVER['QUERY_STRING']), $edit_data);

	  if ($this->runner_type=='analyses'){
	      $this->analysis_name=$edit_data['name'];     
	      $this->computation = $edit_data['computation'];
	      $this->input_column = $edit_data['input_column'];
	      $this->group_by = $edit_data['group_by'];
	  }
	  
	  // this is for custom analyses/calculations
	  if ($this->runner_type=='custom'){
	  	  $this->output_value = $edit_data['output_value'];    
          $this->custom_label = $edit_data['custom_label']; 
	      $this->output_type = $edit_data['output_type'];
	      $this->output_text = $edit_data['output_text'];
	      $this->output_manual = $edit_data['output_manual'];
          $this->name=$edit_data['name'];     
          $this->computation = $edit_data['computation'];
          $this->input_column = $edit_data['input_column'];
	  }
	  
      $this->responses = $edit_data['response']; // this part works differently to the rest
      $this->time_periods = $edit_data['time_period']; // because of the arrays sent via checkboxes
      $this->outcomes = $edit_data['outcome'];
     
      $this->restriction_count = $edit_data['count'];

      if ($this->action_value=='more'){$this->restriction_count++;}
      
      if ($this->action_value=='less'){$this->restriction_count--;}
                
      $this->restriction_columns=array();
      $this->restriction_symbols=array();
      $this->restriction_texts=array();  
	  $this->restriction_types=array();  
	  $this->restriction_manual_texts=array(); 
	 
     // for ( $i = 1; $i <= $this->restriction_count; $i+= 1) {
     for ( $i = 1; $i <= count($edit_data); $i+= 1) {             
        if (array_key_exists('restriction_'.$i.'_type', $edit_data)){            
	        $this->restriction_columns[]=$edit_data['restriction_'.$i.'_column'];
	        $this->restriction_symbols[]=$edit_data['restriction_'.$i.'_symbol'];
	        $this->restriction_texts[]=$edit_data['restriction_'.$i.'_text'];
			//$this->restriction_types[]=$edit_data['restriction_'.$i.'_type'];
			$this->restriction_types['restriction_'.$i.'_type']= $edit_data['restriction_'.$i.'_type'];
			$this->restriction_manual_texts['restriction_'.$i.'_manual']= $edit_data['restriction_'.$i.'_manual'];
        }   
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
    if ($this->runner_type=='analyses'){
	    $this->form_textbox("name", "Analysis Name", $this->analysis_name);
	    $this->form_optionlist("computation", "Computation", $this->computation_list, '', $this->computation);
	    $this->form_optionlist("input_column", "Input Column", $this->column_list, '', $this->input_column);
	    $this->form_optionlist("group_by", "Group By", $this->column_list, '', $this->group_by);
	}
	
	// custom analyses
	if ($this->runner_type=='custom'){
	    $this->form_textbox("name", "Name", $this->name);
		$this->form_textbox("custom_label", "Label", $this->custom_label);
		$this->form_custom_output($this->output_type, $this->input_column, $this->computation, 
			$this->output_value, $this->output_text, $this->output_manual, $this->restriction_count);
	}
	
	
    if (count($this->response_list)>0){
    	$this->form_checkbox("response", "Response", $this->response_list, $this->responses);
		$this->form_checkbox("outcome", "Trial Outcome", $this->outcome_list,$this->outcomes);
	}
	if (count($this->time_period_list)>0){
    	$this->form_checkbox("time_period", "Time Period", $this->time_period_list,$this->time_periods);
	}
    
    $this->restriction_builder($this->restriction_count, $this->restriction_columns, $this->restriction_symbols, 
    	$this->restriction_texts, $this->restriction_types, $this->restriction_manual_texts);
     
  }

 
       
}



?>

