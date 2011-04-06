<?php

/*
 * drop table if exists accuracy_summary;
create table accuracy_summary
select p.trialtype, a.rt, a.response, a.outcome, a.p12_s1 AS ppt, a.BEFORE_I_time, a.INTER_time, A.AFTER_I_time, count(distinct(a.trial_index))/40 AS perc_correct
from p12_s1 as p
left join p12_s1_aggregated as a on p.trial_index = a.trial_index
where a.outcome='correct'
group by cast(p.trialtype as signed) asc, a.outcome;
select * from accuracy_summary;
 * 
 * 
 * ABOVE IS A LONGER VERSION.
 */

if (!get_cfg_var('safe_mode')) {set_time_limit(0);}
	
	// basic password information
	$username="root";
	$password="";
	mysql_connect(localhost,$username,$password) 
		or die ("MYSQL connection problem. Is your MySQL server running?");


$result = mysql_query("SELECT * from interruption.participants");
$clear = mysql_query("drop table if exists interruption.ia_distance_summary");


$created = false;

 while($row = mysql_fetch_array($result)){         
        echo $row[0];
		
		if ($created==false){
		// build basic table
			$create = mysql_query("create table interruption.ia_distance_summary
			SELECT 
			if(((CAST(p.CURRENT_FIX_START AS SIGNED) BETWEEN CAST(a.INTER_time AS SIGNED) AND CAST(a.AFTER_I_time AS SIGNED))) , 'interruption', 
			if(((CAST(p.CURRENT_FIX_START AS SIGNED) BETWEEN CAST(a.BEFORE_I_time AS SIGNED) AND CAST(a.INTER_time AS SIGNED))) , 'before',
			if(((CAST(p.CURRENT_FIX_START AS SIGNED) >= CAST(a.AFTER_I_time AS SIGNED))) , 'after', 'null'))) AS time_period,
			p.current_fix_start, '".$row[0]."' AS ppt, p.trialtype, p.current_fix_nearest_interest_area_distance AS DISTANCE,a.*
			FROM 
			    interruption.".$row[0]." p
	        JOIN
				interruption.".$row[0]."_aggregated a ON a.trial_index = p.TRIAL_INDEX
			WHERE 
				(a.outcome='CORRECT') 
            AND	a.BEFORE_I_time IS NOT NULL AND	a.INTER_time IS NOT NULL AND a.AFTER_I_time IS NOT NULL AND
			CAST(p.CURRENT_FIX_START AS SIGNED) >=a.BEFORE_I_time;");	if(mysql_error){echo mysql_error();} 	
		
		// make ppt name column a bit longer
			$longer = mysql_query("ALTER TABLE interruption.ia_distance_summary  CHANGE COLUMN `ppt` `ppt` 
				VARCHAR(20) NULL DEFAULT ''");if(mysql_error){echo mysql_error();}
		
		
		}
		
		if ($created == true){
		// table is built, insert into it.	
			$insert = mysql_query("
				insert into interruption.ia_distance_summary
				SELECT 
			if(((CAST(p.CURRENT_FIX_START AS SIGNED) BETWEEN CAST(a.INTER_time AS SIGNED) AND CAST(a.AFTER_I_time AS SIGNED))) , 'interruption', 
			if(((CAST(p.CURRENT_FIX_START AS SIGNED) BETWEEN CAST(a.BEFORE_I_time AS SIGNED) AND CAST(a.INTER_time AS SIGNED))) , 'before',
			if(((CAST(p.CURRENT_FIX_START AS SIGNED) >= CAST(a.AFTER_I_time AS SIGNED))) , 'after', 'null'))) AS time_period,
			p.current_fix_start, '".$row[0]."' AS ppt, p.trialtype, p.current_fix_nearest_interest_area_distance AS DISTANCE,a.*
			FROM 
			    interruption.".$row[0]." p
	        JOIN
				interruption.".$row[0]."_aggregated a ON a.trial_index = p.TRIAL_INDEX
			WHERE 
				(a.outcome='CORRECT') 
            AND	a.BEFORE_I_time IS NOT NULL AND	a.INTER_time IS NOT NULL AND a.AFTER_I_time IS NOT NULL AND
			CAST(p.CURRENT_FIX_START AS SIGNED) >=a.BEFORE_I_time;");if(mysql_error){echo mysql_error();} 
			
		}
		
		$created=true;
    }





/*
 * 
 * 
 * drop table if exists accuracy_summary;
create table accuracy_summary
select p.trialtype, a.response, a.outcome, a.p12_s1 AS ppt, count(distinct(a.trial_index))/40 AS perc_correct 
from p12_s1 as p
left join p12_s1_aggregated as a on p.trial_index = a.trial_index
where a.outcome='correct'
group by cast(p.trialtype as signed) asc, a.outcome;
select * from accuracy_summary;





insert into accuracy_summary
select p.trialtype, a.response, a.outcome, a.p12_s1 AS ppt, count(distinct(a.trial_index))/40 AS perc_correct 
from p12_s1 as p 
left join p12_s1_aggregated as a on p.trial_index = a.trial_index
where a.outcome='correct'
group by cast(p.trialtype as signed) asc, a.outcome;
select * from accuracy_summary;

select trialtype, replace(left(ppt,3), '_', ''), perc_correct from accuracy_summary 
group by replace(left(ppt,3), '_', '')
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */




















?>






