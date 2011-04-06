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
    $result=@mysql_query("SELECT * FROM ".$this->runner_interface->current_project.".datasets_aggregated LIMIT 1");
    
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

   function get_responses_info(){
    $result=mysql_query("SELECT name from ".$this->runner_interface->current_project.".responses_list");
    
    $this->full_responses_list = array();
    
    while($row = mysql_fetch_array($result)){         
        $this->full_responses_list[]=$row['name'];
    }
  }
   
  function get_ppt_aggregated(){
    // used to check whether temporary table currently exists or not.
    $result=@mysql_query("SELECT * FROM ".$this->runner_interface->current_project.".datasets_aggregated 
    	WHERE ppt='".$this->participant."' LIMIT 1");
    	
    $exists = false;
    
    while($row = @mysql_fetch_array($result)){
    
      $exists = true;     
    }
    
    return $exists;
  }

  function aggregated_table($type){
    
	// $type defines whether we create the aggregated monster or just add to it
	if($type=='create'){$aggregated_sql = "CREATE TABLE ";}
	if($type=='insert'){$aggregated_sql = "INSERT INTO ";}
	 
    // create temporary table - trunk
    // p is participant table alias
    // r is response table alias
    // t is time period table alias
    // this does a dynamic join based on whether there are time periods and/or responses
            
    $aggregated_sql .= $this->runner_interface->current_project.".datasets_aggregated
      SELECT p.INPSYTE__PPT_ID, p.trial_index ";
    
	// if responses have been set up, include them in the aggregated table
	if (count($this->full_responses_list)>0){
		 $aggregated_sql .= " , r.response, r.rt, r.outcome ";	
	}
	
	// add basics of events/time periods
    foreach ($this->full_time_periods_list as $event){
      $aggregated_sql .= ", min(IF(t.runner='".$event."', value, NULL)) AS ".$event."_time ";     
    }
		
	// close basics of sql trunk
    $aggregated_sql .= " FROM ".$this->runner_interface->current_project.".datasets AS p ";
	
	// if we have both time periods and responses, join them both
	if (count($this->full_responses_list)>0 && count($this->full_time_periods_list)>0){
	    // add join for responses table
	    $aggregated_sql .= " LEFT JOIN ".$this->runner_interface->current_project.".responses_output AS r 
	    on r.ppt_id=p.INPSYTE__PPT_ID and p.trial_index= r.trial ";
	    
	    // add join for the various events (dynamic)
	  //  foreach ($this->full_time_periods_list as $event){
	      $aggregated_sql .= " LEFT JOIN ".$this->runner_interface->current_project.".time_periods_output 
	      	AS t ON t.ppt_id=r.ppt_id AND t.trial = r.trial ";
	   // }
	}
	
	// if we have just time periods, join them only
	if (count($this->full_responses_list)==0 && count($this->full_time_periods_list)>0){
	    
	    // add join for the various events (dynamic)
	   // foreach ($this->full_time_periods_list as $event){
	      $aggregated_sql .= " LEFT JOIN ".$this->runner_interface->current_project.".time_periods_output 
	      	AS t ON t.ppt_id=p.INPSYTE__PPT_ID and p.trial_index= t.trial ";
	    //}
	}
		
	// if we have just responses, join them only
	if (count($this->full_responses_list)>0 && count($this->full_time_periods_list)==0){
	    // add join for responses table
	    $aggregated_sql .= " LEFT JOIN ".$this->runner_interface->current_project.".responses_output AS r 
	    on r.ppt_id=p.INPSYTE__PPT_ID and p.trial_index= r.trial ";		
		
	}
    
    $aggregated_sql .= "GROUP BY p.INPSYTE__PPT_ID, p.trial_index";

	$result=mysql_query($aggregated_sql); if(mysql_error){echo mysql_error();}
	
	echo $aggregated_sql;
	
	// add indices for speed
	if($type=='create'){
        $index = mysql_query("ALTER TABLE ".$this->runner_interface->current_project.".datasets_aggregated 
        	ADD INDEX trial_index (trial_index),
        	ADD INDEX INPSYTE__PPT_ID (INPSYTE__PPT_ID)"); if(mysql_error){echo mysql_error();}
	}
           
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
              BETWEEN CAST(a.".$time_period."_time AS SIGNED) AND CAST(a.".$end_name." AS SIGNED)
              OR 
              CAST(p.CURRENT_FIX_END AS SIGNED)
              BETWEEN CAST(a.".$time_period."_time AS SIGNED) AND CAST(a.".$end_name." AS SIGNED) ";
            
            $this->restriction_query.= " ) ";  
            
			// this is used to select the lower bound of the time periods
			// also used to sort out fix durs that overlap
			if($tp_count==1){ $this->first_time_period_selected= $time_period."_time";}
			
			// this variable will, once the end of the list is reached, be set to the final
			// time period selected (in terms of temporal order)
			// it is used below to sort out fix durs that overlap across different time periods.
			// however, we want to do this only when we have more than one tp in total,
			// or else it sets the final tp to be the next tp up from the first (i.e., only)
			$this->final_time_period_selected = $end_name;
			
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
  
 function selection_query(){
	
	//////////////
	//
	// 	THIS HANDLES BOTH NORMAL AND CUSTOM ANALYSES. THEY HAVE A LOT OF OVERLAP.
	//
	//////////////
	
    //$this->dummy_run=true;
    
    if ($this->runner_interface->runner_type=='analyses'){
    	$this->computation = $this->runner_data['computation'];
    	$this->input_column = $this->runner_data['input_column'];
    	$this->group_by = $this->runner_data['group_by'];
	}
    
	if ($this->runner_interface->runner_type=='custom'){
			
		$this->generate_output=false;
		
	    $this->custom_label = $this->runner_data['custom_label']; 
		$this->output_type = $this->runner_data['output_type'];
		$this->computation = $this->runner_data['computation'];
	    $this->input_column = $this->runner_data['input_column'];
		$this->output_value = $this->runner_data['output_value'];
		$this->output_text = $this->runner_data['output_text'];
		$this->output_manual = $this->runner_data['output_manual'];
	}
    
    // set up info for time periods, inc full list and temporal order
    $this->get_time_periods_info();
	
	// set up info for responses
	$this->get_responses_info();

    $this->restriction_columns=array();
    $this->restriction_symbols=array();
    $this->restriction_texts=array();

    $this->response_list = array();
    $this->outcome_list = array();
    $this->time_period_list = array();

    // sort out temporary table - create if it doesnt exist already
    $this->aggregated_table_exists = $this->table_exists();
    if ($this->aggregated_table_exists==false){
      $this->aggregated_table("create");
    }

	if ($this->runner_interface->runner_type=='analyses'){ 		
		// these are for standard computations
	    if (!strstr($this->computation, 'COUNT DISTINCT') && $this->input_column!=''){
	      $this->computation_string = $this->computation."(".$this->input_column.")";
	    }
	    if (strstr($this->computation, 'COUNT DISTINCT') && $this->input_column!=''){
	      $this->computation_string = "COUNT(DISTINCT(".$this->input_column."))";
	    }
		
		// this is used to shave times off fixation durations that occur as part of multiple time periods
		if (count($this->time_period_list)>0 && strtolower($this->input_column)=='current_fix_duration'
			&& $this->computation=='avg'){
			$this->computation_string = 
			"AVG(
			  CASE
				WHEN (cast(p.current_fix_start as signed) 
					between cast(a.".$this->first_time_period_selected." as signed) 
		  				and cast(a.".$this->final_time_period_selected." as signed)) 
		  		    and (cast(p.current_fix_end as signed) between
		  		    	cast(a.".$this->first_time_period_selected." as signed) 
		   				and cast(a.".$this->final_time_period_selected." as signed)) 
		   		THEN p.current_fix_duration
	
				WHEN (cast(p.current_fix_start as signed) 
					between cast(a.".$this->first_time_period_selected." as signed) 
		  				and cast(a.".$this->final_time_period_selected." as signed)) 
		  			and (cast(p.current_fix_end as signed) > 
		  				cast(a.".$this->final_time_period_selected." as signed)) 
		  		THEN cast(a.".$this->final_time_period_selected." as signed) - cast(p.current_fix_start as signed)
	
				WHEN (cast(p.current_fix_start as signed) 
					< cast(a.".$this->first_time_period_selected." as signed)) 
		  			and (cast(p.current_fix_end as signed) 
		  				between cast(a.".$this->first_time_period_selected." as signed) and 
		  				cast(a.".$this->final_time_period_selected." as signed))
		  		THEN cast(p.current_fix_end as signed) - cast(a.".$this->first_time_period_selected." as signed)
	
				WHEN (cast(p.current_fix_start as signed) 
					< cast(a.".$this->first_time_period_selected." as signed)) 
		   			and (cast(p.current_fix_end as signed) 
		   				> cast(a.".$this->final_time_period_selected." as signed)) 
		   		THEN cast(a.".$this->final_time_period_selected." as signed)- cast(a.".$this->first_time_period_selected." as signed)
	
			 END
			)";
		
		}
	     
		 $this->restriction_query = "INSERT INTO 
		     ".$this->runner_interface->current_project.".".$this->runner_interface->runner_type."_output 
		     (runner, ppt_id, participant, session_id, trial, value)
		     SELECT '".$this->runner_interface->name."', p.INPSYTE__PPT_ID,
		     p.INPSYTE__PPT_TRUE_ID, p.INPSYTE__SESSION_ID, p.".$this->group_by.",
		     ".$this->computation_string." 
		     FROM ".$this->runner_interface->current_project.".datasets p
		     JOIN ".$this->runner_interface->current_project.".datasets_aggregated as a on 
		     p.INPSYTE__PPT_ID = a.INPSYTE__PPT_ID AND p.TRIAL_INDEX = a.trial_index
		     ";
	 }
	 
	 ////// build query trunk for custom
	 if ($this->runner_interface->runner_type=='custom'){
	 			
	 	if($this->output_type=='calculation'){
	 		$output_string = $this->input_column." ".$this->computation." ".$this->output_value;
	 	}	
		
	 	if($this->output_type=='text'){
	 		$output_string = " '".$this->output_text."' ";
	 	}	
		
		if($this->output_type=='manual'){
	 		$output_string = " ".$this->output_manual." ";
	 	}	
		
		
		$datasets_columns = header_array_builder($this->runner_interface->current_project.".datasets");
		
		if (!in_array($this->custom_label, $datasets_columns)){
			$add = mysql_query("ALTER TABLE ".$this->runner_interface->current_project.".datasets
				 ADD COLUMN ".$this->custom_label." VARCHAR(50) DEFAULT NULL");	
			
		}
		
		$this->restriction_query = "UPDATE ".$this->runner_interface->current_project.".datasets p
		LEFT JOIN ".$this->runner_interface->current_project.".datasets_aggregated AS a 
		ON p.INPSYTE__PPT_ID = a.INPSYTE__PPT_ID AND p.TRIAL_INDEX = a.trial_index
		SET ".$this->custom_label." = ".$output_string."";
		
	}
	 
	// this is used for dynamic query generation
    $this->where_written = false; 

    // handle various monstrous arrays - restrictions, responses, time periods, outcomes
    for ( $i = 1; $i <= count($this->runner_data); $i+= 1) {
       
       // handle infinite number of restrictions
       if (array_key_exists('restriction_'.$i.'_type', $this->runner_data)){
         
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
          
          // manual restrictions
          if ($this->runner_data['restriction_'.$i.'_type']=='manual'){
          	$this->restriction_query .= $this->runner_data['restriction_'.$i.'_manual'];
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
      	  
	  // close up and group by - not for custom analyses though
	  if ($this->runner_interface->runner_type=='analyses'){ 
	  	$this->restriction_query.= " GROUP BY p.INPSYTE__PPT_ID, p.TRIAL_INDEX ";
	  }
	  
      ECHO "<BR>";
      echo $this->restriction_query;
      ECHO "<BR>";
    
  }
  
  
  
}


?>

