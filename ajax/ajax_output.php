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
    echo "<article id='edit_output_article' name='edit_output_article'>";
    echo "<form name='checklist_form' id='checklist_form'>";
    echo "<input type='button' onclick='save_db()' value='Download for Excel'>";
    echo "</form>";
    echo "</article>";
    echo "<script>select_all(true);</script>";
}

if (isset($_GET['run_analyses'])){
      
      parse_str(urldecode($_SERVER['QUERY_STRING']), $analyses);
      array_shift($analyses); // remove first item from array which is run_analyses, we only want checkboxes
      
      // run through the checkboxes and stuff now.
      foreach($analyses as $analysis){
          
        // get all the ppt ids  
        $participant_array = single_column_array_builder("".$current_project.".participants", "ppt_id");
        
        foreach ($participant_array as $participant){
          
          // calculate the value for insertion into the output table  
          $average_query = mysql_query("SELECT AVG(".$analysis.") 
            FROM ".$current_project.".aggregated_trials WHERE ppt_id='".$participant."'");  
    
          while($average_row = mysql_fetch_array($average_query)){
            $average = $average_row[0];
            
            //add the value to the output table
            $update_output = mysql_query("UPDATE ".$current_project.".output 
              SET ".$analysis."='".$average."' WHERE ppt_id='".$participant."'");
  
          }
          echo $participant; 
          echo $analysis; 
          echo $average;
          
        }
      }
}

if ($_GET['refresh']=='output_view'){
  
  echo "<article>";
  
  table_builder("".$current_project.".output");
  
  echo "</article>";
  
}


if (isset($_GET['create_excel'])){
  
  create_excel($current_project.'.output');
  echo "File has been saved. 
  <br><a href='projects/".$current_project."/excel_output/".$current_project.".output.xls'><button>Click here to open it.</button></a>";
  
  }
  
?>
