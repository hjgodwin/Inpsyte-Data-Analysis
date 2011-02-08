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

class edit_responses extends edit_control {
 
  function form_filler(){

    // if we are adding, set the values for cleanness. it sets some defaults
    if ($this->action_value=='add'){
        $this->response_name='';
        $this->correct_answer = '';
        $this->output_label = '';
        $this->comparison_value = '';
        $this->input_column = '';
        $this->group_by = 'TRIAL_INDEX';
     }
    
    // if we are editing, we fill in the values
    if ($this->action_value=='edit'){
      
        $edit_data = $this->get_runner_attribs($this->name_to_edit);    
        
        $this->response_name=$this->name_to_edit;   
        $this->correct_answer = $edit_data['correct_answer'];
        $this->output_label = $edit_data['output_label'];
        $this->comparison_value = $edit_data['comparison_value'];
        $this->input_column = $edit_data['input_column'];
        $this->group_by = $edit_data['group_by'];
        
    }
        
    // now build the form with our values!
    $this->form_textbox("name", "Response Name", $this->response_name);
    $this->form_optionlist("correct_answer", "Correct Answer", $this->column_list, '', $this->correct_answer);
    $this->form_textbox("output_label", "Output Label", $this->output_label);
    $this->form_textbox("comparison_value", "Comparison Value", $this->comparison_value);
    $this->form_optionlist("input_column", "Input Column", $this->column_list, '', $this->input_column);
    $this->form_optionlist("group_by", "Group By", $this->column_list, '', $this->group_by);
  }
       
}



?>

