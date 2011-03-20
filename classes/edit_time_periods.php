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

class edit_time_periods extends edit_control {
 
  function form_filler(){
    // Default for restriction count
    $this->pair_count = 0;

    // if we are adding, set the values for cleanness. it sets some defaults
    if ($this->action_value=='add'){
        $this->time_period_name='';
        $this->temporal_order = '1';
        $this->search_text = '';
        $this->group_by = 'TRIAL_INDEX';
		$this->fix_sacs = 'both';
        //$this->pair_text_columns = array('');
        //$this->pair_time_columns = array('');
    }
    
    // if we are editing, we fill in the values
    if ($this->action_value=='edit'){
      
       $edit_data = $this->get_runner_attribs($this->name_to_edit);    
        
        $this->time_period_name=$this->name_to_edit; 
        $this->temporal_order = $edit_data['temporal_order'];
        $this->search_text = $edit_data['search_text'];
        $this->group_by = $edit_data['group_by'];
		$this->fix_sacs = $edit_data['fix_sacs'];
        
        //$this->pair_text_columns = array();
        //$this->pair_time_columns = array();
        
        /*// handle infinite number of restrictions
        for ( $i = 1; $i <= count($edit_data); $i+= 1) {
              
            if (array_key_exists('pair_'.$i.'_text_column', $edit_data)){
              $this->pair_text_columns[]=$edit_data['pair_'.$i.'_text_column'];
              $this->pair_time_columns[]=$edit_data['pair_'.$i.'_time_column'];
              $this->pair_count++;
            }           
        }       */ 
    }

    /*if ($this->action=='change_pairs'){
   
      parse_str(urldecode($_SERVER['QUERY_STRING']), $edit_data);
        
      $this->time_period_name=$edit_data['name']; 
      $this->temporal_order = $edit_data['temporal_order'];
      $this->search_text = $edit_data['search_text'];
      $this->group_by = $edit_data['group_by'];
      
      $this->pair_count = $edit_data['count'];

      if ($this->action_value=='more'){
        $this->pair_count++;
      }
      
      if ($this->action_value=='less'){
        $this->pair_count--;
      }
                
      $this->pair_text_columns = array();
      $this->pair_time_columns = array();

      for ( $i = 1; $i <= count($edit_data); $i+= 1) {    
            if (array_key_exists('pair_'.$i.'_text_column', $edit_data)){
              $this->pair_text_columns[]=$edit_data['pair_'.$i.'_text_column'];
              $this->pair_time_columns[]=$edit_data['pair_'.$i.'_time_column'];
            }
      }          
    
    }*/
    
    // now build the form with our values!
    $this->form_textbox("name", "Time Period Name", $this->time_period_name);
    $this->form_textbox("temporal_order", "Temporal Order", $this->temporal_order);
    $this->form_textbox("search_text", "Text to Search for", $this->search_text);
	$this->form_event_checkboxes($this->fix_sacs);
    //$this->time_period_pair_builder($this->pair_count, $this->pair_text_columns, $this->pair_time_columns);
    $this->form_optionlist("group_by", "Group By", $this->column_list, '', $this->group_by);  
}


function form_radioselect($name, $value, $text, $selected){
	
	echo "<input type='radio' name='".$name."' value='".$value."'"; 
	
	if ($selected == $value){echo " checked ";}
	
	echo " /> ".$text." &nbsp;";
	
}

function form_event_checkboxes($selected_button){
	
	echo "<tr>";
    echo "<td>";
    echo "Event can occur during:";
    echo "</td>";
    echo "<td>";
	$this->form_radioselect("fix_sacs", "fixations", "Fixations", $selected_button);
	$this->form_radioselect("fix_sacs", "saccades", "Saccades", $selected_button);
	$this->form_radioselect("fix_sacs", "both", "Both", $selected_button);
	#echo "<input type='radio' name='fix_sacs' value='fixations' /> Fixations &nbsp;";
	#echo "<input type='radio' name='fix_sacs' value='saccades' /> Saccades &nbsp;";
	#echo "<input type='radio' name='fix_sacs' value='both' /> Both";
	echo "</td>";
}

/*
 function time_period_pair_builder($max_number_of_pairs=3, $text_columns=array('','',''),$time_columns=array('','','')){
   
    echo "<tr>";
    echo "<td>";
    echo "Event Message Pairs:";
    echo "</td>";
    echo "<td>";

    for($pair_number=1; $pair_number<=$max_number_of_pairs; $pair_number++){
      echo "<table style='border:none'>";
      echo "<tr>";
      echo "<td style='border:none'>";  
      echo "Pair #".$pair_number.":";
      echo "</td>";
      echo "<td style='border:none'>";
      echo "Event Message: ";
      option_list_builder('pair_'.$pair_number.'_text_column', $this->column_list, '', $text_columns[$pair_number-1]);
      echo "<br>";
      echo "Event Time: ";
      option_list_builder('pair_'.$pair_number.'_time_column', $this->column_list, '', $time_columns[$pair_number-1]);      
      echo "</td>";
      echo "</tr>";
      echo "</table>"; 
      
    }
   
    echo "<input type='button' onclick='change_pairs(\"edit\", \"false\", \"".$this->runner_type."\", \"more\", \"".$max_number_of_pairs."\")' value='Add Pair'>";
    echo "<input type='button' onclick='change_pairs(\"edit\", \"false\", \"".$this->runner_type."\", \"less\", \"".$max_number_of_pairs."\")' value='Remove Pair'>";
    echo "</td>";
    echo "</tr>";  
 }*/
       
}

?>


