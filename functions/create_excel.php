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

function create_excel($database){
    
    // import global references
    global $current_project;
    
    // build data for output
    $spreadsheet = ''; // this contains all of the stuff we are going to add
    
    // build headers
    $headers = header_array_builder($database);
    foreach($headers as $header){
      $spreadsheet = $spreadsheet . $header . "\t";
    }
    $spreadsheet = $spreadsheet . "\n";
    
    // get the actual data and add it in
    $result = mysql_query("SELECT * FROM ".$database);
  
    while($row= mysql_fetch_row($result)){
            foreach($row as $cell){
               $spreadsheet = $spreadsheet . $cell . "\t";
            }
            
            $spreadsheet = $spreadsheet . "\n";
    }
    
    // output file to appropriate location
    $file_name = "../projects/".$current_project."/excel_output/".$database.".xls";
    $file_handler = fopen($file_name,"w");
    fwrite($file_handler,$spreadsheet); 
}

?>