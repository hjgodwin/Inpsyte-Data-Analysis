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


function single_column_array_builder ($database, $column){
    // import global references
    global $current_project;  
      
    $column_list = array();
    
    #build headings
    $result=mysql_query("SELECT ".$column." FROM ".$database);

    while($row = mysql_fetch_array($result))
        {$column_list[]=$row[0];}


    return $column_list;
}

function single_column_array_builder_restriction ($database, $column, $where){
    // import global references
    global $current_project;  
      
    $column_list = array();
    
    #build headings
    $result=mysql_query("SELECT ".$column." FROM ".$database. " WHERE ". $where);

    while($row = mysql_fetch_array($result))
        {$column_list[]=$row[0];}


    return $column_list;
}

function single_column_array_builder_groupby ($database, $column){
    // import global references
    global $current_project;  
      
    $column_list = array();
    
    #build headings
    $result=mysql_query("SELECT ".$column." FROM ".$database." GROUP BY ".$column);

    while($row = mysql_fetch_array($result))
        {$column_list[]=$row[0];}


    return $column_list;
}


?>
