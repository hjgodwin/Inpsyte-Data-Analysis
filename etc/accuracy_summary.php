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
$clear = mysql_query("drop table if exists interruption.accuracy_summary");


$created = false;

 while($row = mysql_fetch_array($result)){         
        echo $row[0];
		
		if ($created==false){
		// build basic table
			$create = mysql_query("create table interruption.accuracy_summary
				select p.trialtype, a.response, a.outcome, a.".$row[0]." AS ppt, count(distinct(a.trial_index))/40 AS perc_correct 
				from interruption.".$row[0]." as p
				left join interruption.".$row[0]."_aggregated as a on p.trial_index = a.trial_index
				where a.outcome='correct'
				group by cast(p.trialtype as signed) asc, a.outcome;");	if(mysql_error){echo mysql_error();} 	
		
		// make ppt name column a bit longer
			$longer = mysql_query("ALTER TABLE interruption.accuracy_summary  CHANGE COLUMN `ppt` `ppt` 
				VARCHAR(20) NULL DEFAULT '' AFTER `outcome`");if(mysql_error){echo mysql_error();}
		
		
		}
		
		if ($created == true){
		// table is built, insert into it.	
			$insert = mysql_query("
				insert into interruption.accuracy_summary
				select p.trialtype, a.response, a.outcome, a.".$row[0]." AS ppt, count(distinct(a.trial_index))/40 AS perc_correct 
				from interruption.".$row[0]." as p 
				left join interruption.".$row[0]."_aggregated as a on p.trial_index = a.trial_index
				where a.outcome='correct'
				group by cast(p.trialtype as signed) asc, a.outcome;");if(mysql_error){echo mysql_error();} 
			
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






