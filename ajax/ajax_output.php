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

include("../conf/conf.php");
include("../functions/table_builder.php");
include("../functions/single_column_array_builder.php");
include("../functions/header_array_builder.php");
include("../functions/option_list_builder.php");
include("../functions/create_excel.php");

if ($_GET['refresh']=='output_list_form'){

    # Start building the form now
    echo "<div id='edit_output_div' name='edit_output_div'>";
    echo "<form name='checklist_form' id='checklist_form'>";
    echo "<input type='button' onclick='save_db()' value='Download for Excel'>";
    echo "</form>";
    echo "</div>";
    echo "<script>select_all(true);</script>";
}


if ($_GET['refresh']=='output_view'){
  
  //
  // begin by creating pivot table from analyses_output table
  //
  
  // get full list of analyses
  $result = mysql_query("SELECT name from ".$current_project.".analyses_list");
  
  while($row = mysql_fetch_array($result))
        {$analyses_list[]=$row[0];}
  
  // get full list of sessions
    $result = mysql_query("SELECT name from ".$current_project.".session_list");
  
  while($row = mysql_fetch_array($result))
        {$session_list[]=$row[0];}
  
  // now build pivot table query
  $result = mysql_query("DROP TABLE IF EXISTS ".$current_project.".output");
  
  $output_trunk = " CREATE TABLE ".$current_project.".output AS SELECT participant ";
  
  foreach($analyses_list as $analysis){
  	foreach ($session_list as $session) {
  		$output_trunk.=", avg(if(session_id = '".$session."' and runner='".$analysis."', value, NULL)) 
	  		AS ".$analysis."_".$session."";	
  	}
  }
  
  $output_trunk .= " FROM ".$current_project.".analyses_output GROUP BY participant ";
  
  $result=mysql_query($output_trunk);
  
  echo "<div>";
  
  table_builder($current_project.".output");
  
  echo "</div>";
  
}


if (isset($_GET['create_excel'])){
  
  create_excel($current_project.'.output');
  echo "File has been saved. 
  <br><a href='projects/".$current_project."/excel_output/".$current_project.".output.xls'><button>Click here to open it.</button></a>";
  
  }
  
?>
