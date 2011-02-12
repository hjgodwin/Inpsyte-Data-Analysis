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

include("javascript/jscript_edit.php");

$table_type = $_GET['table'];

echo "<script type='text/javascript'>
        refresh_all(\"".$table_type."\");
        
     </script> ";


////////////////////////////////////////////////////////////////////////////////////////////////////////////
// count the number of participants currently in the database.
// if there are zero, we can't build the runners, so we take the user to add people to the database
$result=mysql_query("SELECT COUNT(ppt_id) FROM ".$current_project.".participants");
while($row = mysql_fetch_array($result)){$ppt_count = $row[0];} 

if ($ppt_count==0){$table_type = 'empty';}

if ($table_type=='empty'){
  echo "<article><h1>Cannot Continue without Particpiants</h1>
  <b>You need to add participants to the project before Inpsyte Data Analysis can continue.<br>
  To do this click <a href='index.php?viewer=participants'>here</a>.</b>
  </article>";
}

if ($table_type!='empty'){
	
	if ($table_type=='analyses'){
	  echo "<article><h1>Edit Analyses</h1>
	  Analyses are used to create means for output (e.g. average time spent on a task).
	  </article>"; 
	}
	
	
	if ($table_type=='time_periods'){
	  echo "<article><h1>Edit Time Periods</h1>
	  Time periods are specific to eye tracking experiments and can be used to chart multiple events
	  within a single trial, such as the appearance or disappearance of a given display.
	  </article>"; 
	}
	
	if ($table_type=='responses'){
	  echo "<article><h1>Edit Responses</h1>
	  The responses are currently set up to be specific for eye tracking studies. Contact me if you want to 
	  examine different forms of responses. Note that many forms of experimental software output responses
	  in a specific column in the data, so you may be able to access the required information already for the
	  analyses.
	  </article>"; 
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
	echo "<article id='interactive_table'>";
	echo "</article>";
	
	 
	echo "<article id='runner_popup' title='Running...' style='display:none'>";
	
	echo "<article id='runner_popup_output'></article>";
	
	echo "</article>";
	
	echo "<article id='delete_popup' title='Delete Runner' style='display:none'>";
	echo "<article id='delete_popup_output'></article>";
	
	echo "</article>";
	
}



?>