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

class edit_control {
  public $runner_type;
  public $action;
  public $action_value;
  public $list_table;
  public $attribs_table;
  public $output_table;
  public $current_project;
  public $attribs = array();
  public $form_values= array();
  public $name_to_edit;
  public $produce_output=false;

  public function __construct($current_project){
    $this->runner_type = $_GET['runner_type'];
    $this->action = $_GET['action'];
    $this->action_value = $_GET['action_value'];
    $this->list_table = $this->runner_type."_list";
    $this->attribs_table = $this->runner_type."_attrib";
    $this->output_table = $this->runner_type."_output:";
    $this->current_project = $current_project;   
    $this->name_to_edit = $_GET['name'];
    $this->name = $_GET['name'];
    
    // select the first ppt for use as a template for input etc.
    //$participant= $this->get_single_participant_id();

    // now build the arrays for the form
    $this->column_list = @header_array_builder("".$this->current_project.".datasets");
    
    // Computation list 
    $this->computation_list = array("AVG", "SUM", "MAX", "MIN", "COUNT", "COUNT DISTINCT");
       
    // Calculation list - for custom columns. Defaults to multiply
    $this->calculation_list = array("*", "+", "-", "/");
    
    // Time period list
    $this->time_period_list = single_column_array_builder ($this->current_project.".time_periods_list", "name");
    
    // Response list
    $this->response_list = single_column_array_builder ($this->current_project.".responses_list", "name");
    
    // Outcome list
    $this->outcome_list = array("CORRECT", "INCORRECT");
       
  }
  
  function interactive_table(){
     echo "<br>";
     echo "<input type='button' onclick='popup_display(\"add\", \"false\", \"none\", \"".$this->runner_type."\")' value='Add'>";
     table_builder_interactive_edit($this->current_project.".".$this->list_table, $this->runner_type);// add this function to this class.  
  }
  
  // this is the function called by the extended classes to build the popup table
  function popup(){       

	echo "<form id='popup_form' name='popup_form'>";
    echo "<table id='table_popup' style='width:100%;'>";
        
    // Fill in appropriate variables for the form
    $this->form_filler();
       
    echo "</table>";
	echo "</form>";

    echo "<input type='button' onclick='modify_runner(\"".$this->runner_type."\")' value='Submit'>";
    echo "<input type='button' onclick='close_analysis_popup()' value='Close'>";
    echo "<br><div id='popup_output'></div>";
    
    //echo "</form>";
    //echo "</table>";
       
  }
  
  function form_textbox($name, $description, $value){
      echo "<tr>";
        echo "<td>";
          echo $description.":";
        echo "</td>";
        echo "<td>";
          echo "<input type='text' id='".$name."' name='".$name."' value='".$value."'>";
        echo "</td>";
      echo "</tr>";
  }
  
  function form_optionlist($name, $description, $options, $onchange, $selected){
    echo "<tr>";
        echo "<td>";
          echo $description.":";
        echo "</td>";
        echo "<td>";
            option_list_builder($name, $options, $onchange, $selected);    
        echo "</td>";
    echo "</tr>";    
  }
  
  function form_checkbox($name, $description, $options, $list){
    echo "<tr>";
        echo "<td>";
          echo $description.":";
        echo "</td>";
        echo "<td>";
            $this->form_checkbox_field_builder($name, $options, $list);
        echo "</td>";
    echo "</tr>";    
  }
    
  function form_checkbox_field_builder ($name, $array, $list){
 
    foreach ($array as $box){
      echo "<input type='checkbox' name='".$name."[]' value='".$box."'";
      if (count($list)>0){
        if (in_array($box, $list)){echo " checked ";}
      }  
      echo " /> ".$box;
      echo "&nbsp;&nbsp;";            
    }
  }
  
  function get_single_participant_id(){
    
    $participant_select = "SELECT ppt_id from ".$this->current_project.".participants LIMIT 1";
    $participant_result = mysql_query($participant_select);
    while($row2 = mysql_fetch_array($participant_result)){$participant=$row2['ppt_id'];}
    
    return $participant;
 }

  function restriction_builder($max_number_of_restrictions=3, $columns=array('','',''),$symbols=array('','',''),$texts=array('','','')){
   
    $symbol_list = array("=", "LIKE", ">", "<", "<=", ">=", "<>");
   
    echo "<tr>";
    echo "<td>";
    echo "Restrictions:";
    echo "</td>";
    echo "<td>";
   
    for($restriction_number=1; $restriction_number<=$max_number_of_restrictions; $restriction_number++){
      echo "Restriction #".$restriction_number.":"; 
      option_list_builder('restriction_'.$restriction_number.'_column', $this->column_list, '', $columns[$restriction_number-1]);
      option_list_builder('restriction_'.$restriction_number.'_symbol', $symbol_list, '', $symbols[$restriction_number-1]);
      echo "<input type='text' id='restriction_".$restriction_number."_text' name='restriction_".$restriction_number."_text' value='".$texts[$restriction_number-1]."'>";
      echo "<br>";
    }
    
    echo "<input type='button' onclick='change_restrictions(\"edit\", \"false\", \"".$this->runner_type."\", \"more\", \"".$max_number_of_restrictions."\")' value='Add Restriction'>";
    echo "<input type='button' onclick='change_restrictions(\"edit\", \"false\", \"".$this->runner_type."\", \"less\", \"".$max_number_of_restrictions."\")' value='Remove Restriction'>";
    echo "</td>";
    echo "</tr>";
  
 }
  
  function get_runner_attribs($name){   
    // get specific details about this runner
    $result = mysql_query ("SELECT attribute_name, value  from ".$this->current_project.".".$this->runner_type."_attrib
      WHERE runner_name='".$name."'");    
  
    $data = array();
  
    while($row = mysql_fetch_array($result)){
        $data[$row['attribute_name']]= $row['value'];
    }
    
    return $data; 

  }
  
  function add_runner_attrib($runner_type, $runner_name, $attribute_name, $value){
    // this function adds an attribute row to the runner_attrib table    
    $runner_query= "INSERT INTO ".$this->current_project.".".$this->runner_type."_attrib
      (runner_type, runner_name, attribute_name, value) VALUES
      ('".$runner_type."', '".$runner_name."','".$attribute_name."', 
      '".$value."');";
    
    $result = mysql_query($runner_query);
    
    if(mysql_error){echo mysql_error();} 
  }
  
  function delete_runner_attrib(){
    // this function removes an entire runner from the runner_attrib table  
    $runner_query= "DELETE FROM ".$this->current_project.".".$this->runner_type."_attrib
      WHERE runner_name = '".$this->name_to_edit."'";   
    
    $result = mysql_query($runner_query);
    
    if(mysql_error){echo mysql_error();}   
  
  }
  
  function add_runner_list($runner_name, $runner_list){
    // this function removes an entire runner from the runner_attrib table 
    $runner_query= "INSERT INTO ".$this->current_project.".".$runner_list." (name)
      VALUES ('".$runner_name."')";
    
    $result = mysql_query($runner_query);
    
    if(mysql_error){echo mysql_error();}   
  
  }
  
  function delete_runner_list($runner_name, $runner_list){
    // this function removes an entire runner from the runner_attrib table 
    
    $runner_query= "DELETE FROM ".$this->current_project.".".$runner_list."
      WHERE name = '".$runner_name."'";
    
    $result = mysql_query($runner_query);
    
    if(mysql_error){echo mysql_error();}   
  
  }
  
  
  /*function delete_completed_runner($name){
   // this function removes completed runners from the completed runner list

    $runner_query= "DELETE FROM ".$this->current_project.".completed_runner_list
      WHERE runner = '".$name."'";
    
    $result = mysql_query($runner_query);
    
    if(mysql_error){echo mysql_error();}  
    
  }*/
  
  function delete_runner_output($name, $output_list){
   // this function removes completed runners from the completed runner list
    
    $runner_query= "DELETE FROM ".$this->current_project.".".$output_list."
      WHERE runner = '".$name."'";
    
    $result = mysql_query($runner_query);
    
    if(mysql_error){echo mysql_error();}  
    
  }

  function delete_runner(){
     
    // delete runner from database
    $this->delete_runner_attrib();
    $this->delete_runner_list($this->name_to_edit, $this->runner_type."_list");
    //$this->delete_completed_runner($this->name_to_edit);
    $this->delete_runner_output($this->name_to_edit, $this->runner_type."_output");
    
    // output to browser
    if ($this->action!='modify'){
      echo "<form>The requested runner (".$this->name_to_edit.") has been deleted.<br>";
    }
	
	// delete aggregated tables 
	 if ($this->runner_type=='responses' || $this->runner_type=='time_periods'){
  		$this->delete_aggregated_tables();		
  	}
  }
  
  function delete_aggregated_tables(){
  	// this function wipes the aggregated tables whenever a response is changed
  	// or a time period is changed
  	
  	$result=mysql_query("DROP TABLE IF EXISTS ".$this->current_project.".datasets_aggregated");
  }
  
  function add_runner(){

    // do basics
    parse_str(urldecode($_SERVER['QUERY_STRING']), $add);
    
    // set which to edit. weird hack needed.
    $this->name_to_edit = $add['name'];
    
    // delete this to begin with in case this is an edit. also for clean-keepingness
    $this->delete_runner();
    
    // add to main list
    $this->add_runner_list($add['name'], $this->runner_type."_list");
        
    // add appropriate values to attribute list  
    $attribute_list = array_keys($add);

    for ( $i = 0; $i < count($attribute_list); $i += 1) {
      
      // if it's not from a set of checkboxes which come out as an array, add it right away    
      if (!is_array($add[$attribute_list[$i]])){
      
         if ($attribute_list[$i]!="action" && $attribute_list[$i]!="current_data" 
            && $attribute_list[$i]!="name" && strlen($add[$attribute_list[$i]])>0
              && $attribute_list[$i]!="runner_type" ){            
              $this->add_runner_attrib($this->runner_type, $add['name'], $attribute_list[$i], $add[$attribute_list[$i]]);              
              }
      }

      // if it's from a set of checkboxes which come out as an array 
      if (is_array($add[$attribute_list[$i]])){
      
         $checkbox_array = $add[$attribute_list[$i]];

         for ( $j = 0; $j < count($checkbox_array); $j += 1) {
          $next = $j+1; 
          $this->add_runner_attrib($this->runner_type, $add['name'], $attribute_list[$i]."_".$next, $checkbox_array[$j]);
        } 
      }
    }

    echo "<form>Update complete.</form>";  
  }
      
}

include("edit_analyses.php");
include("edit_responses.php");
include("edit_time_periods.php");

?>

