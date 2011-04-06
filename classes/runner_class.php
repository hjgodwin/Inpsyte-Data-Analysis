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

class runner_control {
  public $dummy_run = false;
  public $generate_output = true;
      
  public function __construct($current_project){      
      $this->runner_interface = new edit_control($current_project);
  }
  
  function table_construct(){
    echo "<div id='run_div' name='run_div'>";
    echo "<form>";
   
    $option_list = single_column_array_builder("".$this->runner_interface->current_project.".".
            $this->runner_interface->list_table, "name");
    
    if (count($option_list)>0){
    	option_list_builder('selected_option', $option_list, '','');
    	echo "<input type='button' id='run_all_button' onclick='run_selected(\"".$this->runner_interface->runner_type."\", \"all\")' value='Run Selected for all Participants'>";    
	}
	if (count($option_list)==0){
		echo "Nothing has been added for this yet. You can add stuff by going to the Edit menu.";
	}
		
    echo "</form>";  
  }
  
  function get_current_session($participant){ 
    $session = 0;
    
    $session_result = mysql_query("SELECT session_id from ".$this->runner_interface->current_project.".participants 
      WHERE ppt_id='".$participant."'");
        
    while($session_row = mysql_fetch_array($session_result))
            { $session = $session_row[0];}
            
    return $session;
   }

   function get_current_true_participant_id($participant){
    $participant_real_id = 'nothing';

    $participant_result = mysql_query("SELECT participant from ".$this->runner_interface->current_project.".participants 
          WHERE ppt_id='".$participant."'");

    while($participant_row = mysql_fetch_array($participant_result))
      { $participant_real_id = $participant_row[0];}

     return $participant_real_id;
    }

  function select_and_run(){
    	
	$need_more_runners = false;	
	
	// if this is an analysis, we need to work out if responses and time periods have been run yet
	// if they have not, we force the user to go back and run them first
	if ($this->runner_interface->runner_type=='analyses'){
		
		// for this, we begin by working out how many participants we have and store it as a variable	
		$result = mysql_query("select @participants := count(ppt_id) 
			from ".$this->runner_interface->current_project.".participants");
		
		// we then do a right join for responses which returns a count of how many participants
		// have responses that are not yet run	
		$runner_select= mysql_query("select count(distinct(c.ppt_id)), r.*,  @participants 
			from ".$this->runner_interface->current_project.".responses_output as c
			right join ".$this->runner_interface->current_project.".responses_list as r on (r.name = c.runner)
			group by r.name");	

		// sets to refuse running if necessary
		while($runner_row = mysql_fetch_array($runner_select))
      		{if($runner_row[0]<$runner_row[2]){$need_more_runners=true;}}	
	
		// now we do the same again but with time periods instead
		$runner_select= mysql_query("select count(distinct(c.ppt_id)), r.*,  @participants 
			from ".$this->runner_interface->current_project.".time_periods_output as c
			right join ".$this->runner_interface->current_project.".time_periods_list as r on (r.name = c.runner)
			group by r.name");	
				
		// sets to refuse running if necessary
		while($runner_row = mysql_fetch_array($runner_select))
      		{if($runner_row[0]<$runner_row[2]){$need_more_runners=true;}}	
	
	}
		
	// hack to test things
	$need_more_runners=false;
	
    // run the real thing
    if ($need_more_runners==false){
		$begin_array = explode(' ', microtime() );
		$start = $begin_array[1] + $begin_array[0];

		$this->runner();

		$end_array = explode(' ', microtime() );
    	$total_time = round(($end_array[0] +  $end_array[1] - $start),6);

    	echo "<br>Running completed. Operation took ".$total_time. " to complete.";
    }

	if($need_more_runners==true) {echo "Can not run analysis. You need to make sure you have run all time
		periods and responses before running analyses.";}
  }  
  
  function runner (){

        // work out what session this is
        $this->current_session = $this->get_current_session($this->participant);

        // work out true id of participant
        $this->current_true_ppt_id = $this->get_current_true_participant_id($this->participant);

		// get runner attribs        
        $this->runner_data = $this->runner_interface->get_runner_attribs($this->runner_interface->name);
 
        // build the query to select the appropriate rows
        // also inserts averages per trial into X_output.
        $this->selection_query();

        $result=mysql_query($this->restriction_query); if(mysql_error){echo mysql_error();}

    }

}

include("runner_analyses.php");
include("runner_responses.php");
include("runner_time_periods.php");
//include("runner_custom.php");

?>

