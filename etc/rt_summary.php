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
$clear = mysql_query("drop table if exists interruption.rt_summary");


$created = false;

 while($row = mysql_fetch_array($result)){         
        echo $row[0];
		
		if ($created==false){
		// build basic table
			$create = mysql_query("create table interruption.rt_summary
				select p.trialtype, a.".$row[0]." AS ppt, '".$row[1]."' AS ppt_id, '".$row[2]."' AS ppt_sess,
				 a.rt AS rt,
				if(cast(trialtype as signed)<8,cast(rt as signed)-(cast(a.after_I_time as signed)-cast(a.INTER_time as signed)),rt) AS corrected_rt,
				a.BEFORE_I_time as BEFORE_TIME, a.INTER_time as INTER_TIME, a.after_I_time as AFTER_TIME,
				cast(a.INTER_time as signed)-cast(a.BEFORE_I_TIME as signed) as BEFORE_DURATION
				from interruption.".$row[0]." as p
				left join interruption.".$row[0]."_aggregated as a on p.trial_index = a.trial_index
				where a.outcome='correct'
				and (a.BEFORE_I_time IS NOT NULL and a.INTER_time IS NOT NULL and a.after_I_time IS NOT NULL)
				or (cast(p.trialtype as signed)>8)
				group by cast(p.trial_index as signed) asc;");	if(mysql_error){echo mysql_error();} 	
		
		// make ppt name column a bit longer
			$longer = mysql_query("ALTER TABLE interruption.rt_summary  CHANGE COLUMN `ppt` `ppt` 
				VARCHAR(20) NULL DEFAULT ''");if(mysql_error){echo mysql_error();}
		
		
		}
		
		if ($created == true){
		// table is built, insert into it.	
			$insert = mysql_query("
				insert into interruption.rt_summary
				select p.trialtype, a.".$row[0]." AS ppt, '".$row[1]."' AS ppt_id, '".$row[2]."' AS ppt_sess,
				 a.rt AS rt,
				if(cast(trialtype as signed)<8,cast(rt as signed)-(cast(a.after_I_time as signed)-cast(a.INTER_time as signed)),rt) AS corrected_rt,
				a.BEFORE_I_time as BEFORE_TIME, a.INTER_time as INTER_TIME, a.after_I_time as AFTER_TIME,
				cast(a.INTER_time as signed)-cast(a.BEFORE_I_TIME as signed) as BEFORE_DURATION
				from interruption.".$row[0]." as p
				left join interruption.".$row[0]."_aggregated as a on p.trial_index = a.trial_index
				where a.outcome='correct'
				and (a.BEFORE_I_time IS NOT NULL and a.INTER_time IS NOT NULL and a.after_I_time IS NOT NULL)
				or (cast(p.trialtype as signed)>8)
				group by cast(p.trial_index as signed) asc;");if(mysql_error){echo mysql_error();} 
			
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






