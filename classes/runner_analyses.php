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

class runner_analyses extends runner_control {
 
  function table_exists(){
    // used to check whether temporary table currently exists or not.
    $result=@mysql_query("SELECT * FROM ".$this->runner_interface->current_project.".".$this->participant."_aggregated LIMIT 1");
    
    $exists = false;
    
    while($row = @mysql_fetch_array($result)){
      $exists = true;     
    }
    
    return $exists;
  }
 
  function get_time_periods_info(){
    $result=mysql_query("SELECT runner_name, value from ".$this->runner_interface->current_project.".time_periods_attrib
      WHERE attribute_name = 'temporal_order' ORDER BY attribute_name ASC");
    
    $this->full_time_periods_list = array();
    
    while($row = mysql_fetch_array($result)){         
        $this->full_time_periods_temporal_list[$row['runner_name']]=$row['value'];
        $this->full_time_periods_list[]=$row['runner_name'];
    }
  }

  function create_aggregated_table(){
     
    // create temporary table - trunk
    // p is participant table alias
    // r is response table alias
    // other aliases are made dynamically 
    $aggregated_sql = " CREATE TABLE ".$this->runner_interface->current_project.".".$this->participant."_aggregated
      select distinct p.trial_index, r.response, r.rt, r.outcome, '".$this->participant."' ";
    
    // add basics of events/time periods
    foreach ($this->full_time_periods_list as $event){
      $aggregated_sql .= ", t_".$event.".value AS ".$event."_time ";
    }
    
    // close basics of sql trunk
    $aggregated_sql .= " from ".$this->runner_interface->current_project.".".$this->participant." AS p ";

    // add join for responses table
    $aggregated_sql .= " left join ".$this->runner_interface->current_project.".responses_output AS r on (p.trial_index= r.trial and r.ppt_id='".$this->participant."')";
    
    // add join for the various events (dynamic)
    foreach ($this->full_time_periods_list as $event){
      $aggregated_sql .= " left join ".$this->runner_interface->current_project.".time_periods_output AS t_".$event." on 
        (p.trial_index= t_".$event.".trial and t_".$event.".ppt_id='".$this->participant."' 
        and t_".$event.".runner='".$event."') ";
    }
    
    $result=mysql_query($aggregated_sql); if(mysql_error){echo mysql_error();}
             
  }
 
  function selection_query(){
    
    //$this->dummy_run=true;
    
    // this will be different for each runner type
    $this->computation = $this->runner_data['computation'];
    $this->input_column = $this->runner_data['input_column'];
    $this->group_by = $this->runner_data['group_by'];

    // set up info for time periods, inc full list and temporal order
    $this->get_time_periods_info();

    $this->restriction_columns=array();
    $this->restriction_symbols=array();
    $this->restriction_texts=array();

    $this->response_list = array();
    $this->outcome_list = array();
    $this->time_period_list = array();

    // sort out temporary table - create if it doesnt exist already
    $this->aggregated_table_exists = $this->table_exists();
    if ($this->aggregated_table_exists==false){
      $this->create_aggregated_table();
    }

    $this->restriction_query =" UPDATE ".$this->runner_interface->current_project.".".$this->participant." p
      JOIN ".$this->runner_interface->current_project.".".$this->participant."_aggregated a ON a.trial_index = p.".$this->group_by."
      SET p.".$this->runner_interface->runner_type."_".$this->runner_interface->name."_include=1 ";
      
    $this->where_written = false;

    //print_r($this->runner_data);

    // handle various monstrous arrays - restrictions, responses, time periods, outcomes
    for ( $i = 1; $i <= count($this->runner_data); $i+= 1) {
       
       // handle infinite number of restrictions
       if (array_key_exists('restriction_'.$i.'_column', $this->runner_data)){
         
          $this->add_where_check();

          // equals comparison 
          if ($this->runner_data['restriction_'.$i.'_symbol'] == '='){
            $this->restriction_query .= "p.".$this->runner_data['restriction_'.$i.'_column']." ".
              $this->runner_data['restriction_'.$i.'_symbol'].
              "'".$this->runner_data['restriction_'.$i.'_text']."' ";
          }

          // like COMPARISON
          if ($this->runner_data['restriction_'.$i.'_symbol'] == 'LIKE'){
            $this->restriction_query .= "p.".$this->runner_data['restriction_'.$i.'_column']." ".
              $this->runner_data['restriction_'.$i.'_symbol'].
              "'%".$this->runner_data['restriction_'.$i.'_text']."%' ";
          }

          // numerical comparison
          if ($this->runner_data['restriction_'.$i.'_symbol'] == '>' 
            || $this->runner_data['restriction_'.$i.'_symbol'] == '<'
            || $this->runner_data['restriction_'.$i.'_symbol'] == '<='
            || $this->runner_data['restriction_'.$i.'_symbol'] == '>='){
            $this->restriction_query .= " CAST("."p.".$this->runner_data['restriction_'.$i.'_column']." AS SIGNED) ".
              $this->runner_data['restriction_'.$i.'_symbol'].
              " CAST(".$this->runner_data['restriction_'.$i.'_text']." AS SIGNED) ";
          }          
        }

        // if we have selected specific response(s) to look at
        if (array_key_exists('response_'.$i, $this->runner_data)){
          $this->response_list[]= $this->runner_data['response_'.$i];
        }
        
        // if we have selected specific outcome(s) to look at
        if (array_key_exists('outcome_'.$i, $this->runner_data)){
         $this->outcome_list[]= $this->runner_data['outcome_'.$i];  
        }
 
        // HANDLES time periods       
        if (array_key_exists('time_period_'.$i, $this->runner_data)){
         $this->time_period_list[]= $this->runner_data['time_period_'.$i]; 
        }
                
    }
 
      // do user-selected aspects of the joinery
      $this->join_lister($this->response_list, "response");
      $this->join_lister($this->outcome_list, "outcome");
      $this->time_period_join_lister($this->time_period_list);
 
      // now select only trials where all events occurred
      // note this only counts for analyses where the user asked to look at events in some shape or form
      $this->select_time_period_trials_join_lister($this->time_period_list);
        
      ECHO "<BR>";
      echo $this->restriction_query;
      ECHO "<BR>";
    
  }

  function select_time_period_trials_join_lister ($list){
        
    if(count($list)>0){
        $this->add_where_check();  
        
        $ordered_temporal_list = array_keys($this->full_time_periods_temporal_list);
        
        // build the query  
        $this->restriction_query.= " (";
        
        for( $i=0; $i<count($ordered_temporal_list); $i++){
          if ($i>0){
            $this->restriction_query.= " AND ";
          }
          
          $this->restriction_query.= " a.".$ordered_temporal_list[$i]."_time IS NOT NULL ";
        }
        
        $this->restriction_query.= " ) ";
      }
    
  }

  function time_period_join_lister($list){
    
    $add_or = 'or';
    
    if(count($list)>0){
        // this might look weird but it is done so that stuff gets ordered according to the temporal list,
        // rather than according to how it gets received via the attribs table
        
        $this->add_where_check();
        
        $this->restriction_query.= " ( ";
        
        $ordered_temporal_list = array_keys($this->full_time_periods_temporal_list);
        
        $tp_count = 1;
        
        foreach ($ordered_temporal_list as $time_period){
        
          if (in_array($time_period, $list)){
             
            if($tp_count>1){$this->restriction_query.= " OR ";} 
          
            $this->restriction_query.= " ( ";
            
            $temporal_order = $this->full_time_periods_temporal_list[$time_period];
            $temporal_order_end = $temporal_order+1;
            
            // if this isn't the last event in our list of events, select the next event to serve
            // as the boundary for ending the current event
            if ($temporal_order_end<=count($ordered_temporal_list)){
              $end_name = $ordered_temporal_list[$temporal_order_end-1]."_time "; // -1 need as it's an array
            }
            
            // if this is the final of our events, set RT to be the boundary for the event ending
            if ($temporal_order_end>count($ordered_temporal_list)){
              $end_name = "rt ";
            }
              
            $this->restriction_query.=" CAST(p.CURRENT_FIX_START AS SIGNED)
              BETWEEN CAST(a.".$time_period."_time AS SIGNED) AND CAST(a.".$end_name." AS SIGNED) ";
            
            $this->restriction_query.= " ) ";  
            
            $tp_count++;
          }   
        }
        
        $this->restriction_query.= " ) "; // close the multi-or brackets
    }   
    
  }


  function join_lister($list, $column){
    // used for making life easier for the join - works only for relatively static columns
    // i.e., responses and outcomes
    
    if(count($list)>0){
        $this->add_where_check();  
        
        // build the query  
        $this->restriction_query.= " (";
        
        for( $i=0; $i<count($list); $i++){
          if ($i>0){
            $this->restriction_query.= " OR ";
          }
          
          $this->restriction_query.= "a.".$column."='".$list[$i]."'";
        }
        
        $this->restriction_query.= ") ";
      }
        
  }

  function add_where_check(){
    // helper function for building queriest with many ands and wheres  
    if($this->where_written==true){
      $this->restriction_query .= " AND ";
    }                  
    
    if($this->where_written==false){
      $this->restriction_query .= " WHERE ";
    $this->where_written=true;
    }
    
  }

  function runner_output_query(){
  
    // handles the actual output of the analysis for each trial based on whether that row is selected
    
    // these are for standard computations
    if (!strstr($this->computation, 'COUNT DISTINCT') && $this->input_column!=''){
      $this->computation_string = $this->computation."(".$this->input_column.")";
    }
    if (strstr($this->computation, 'COUNT DISTINCT') && $this->input_column!=''){
      $this->computation_string = "COUNT(DISTINCT(".$this->input_column."))";
    }

    // this is for custom computations ¬¬¬ may need some checking
    //   if ($input_column==''){
    //        $computation_string = "AVG(".$computation.")";
    //   }  
        
    // runner output table    
    $this->runner_query_string = "INSERT INTO ".$this->runner_interface->current_project.".".$this->runner_interface->runner_type."_output 
     (runner, ppt_id, participant, session_id, trial, value)
     SELECT '".$this->runner_interface->name."', '".$this->participant."',
     '".$this->current_true_ppt_id."', '".$this->current_session."', ".$this->group_by.",
     ".$this->computation_string." 
     FROM ".$this->runner_interface->current_project.".".$this->participant." 
     WHERE ".$this->runner_interface->runner_type."_".$this->runner_interface->name."_include=1 GROUP BY ".$this->group_by;
    
  }

}


?>

