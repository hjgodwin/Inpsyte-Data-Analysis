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

function table_builder_interactive ($database, $where='', $order_by='', $direction=''){
    // import global references
    global $current_project;  
      
    $column_list = array();
    
    // build headings
    $result=mysql_query("DESCRIBE ".$database);

    while($row = mysql_fetch_array($result))
        {$column_list[]=$row[0];}

    echo "<table class='display' id='example'>";
    echo "<thead>";
  
    foreach($column_list as $column){
          
        if ($column != $order_by){echo "<th class='top' onclick='select_event_interactive(\"".$column."\",\"ASC\")'>";}
        if ($column == $order_by && $direction=='ASC'){echo "<th class='selected' onclick='select_event_interactive(\"".$column."\",\"DESC\")'>";}
        if ($column == $order_by && $direction=='DESC'){echo "<th class='selected' onclick='select_event_interactive(\"".$column."\",\"ASC\")'>";}
        echo $column;
        echo "</th>";
    }

    echo "<th class='top'>Delete</th>";
    echo "<th class='top'>Edit</th>";
    echo "</thead>";
    
    echo "<tbody>";

    if ($order_by == '') {
      if ($where==''){$result=mysql_query("SELECT * FROM ".$database);}
      if ($where!=''){$result=mysql_query("SELECT * FROM ".$database." WHERE ".$where);}
    }
    
    if ($order_by != '') {
      if ($where==''){$result=mysql_query("SELECT * FROM ".$database. " ORDER BY ".$order_by. " ".$direction);}
      if ($where!=''){$result=mysql_query("SELECT * FROM ".$database." WHERE ".$where. " ORDER BY ".$order_by. " ".$direction);}            
    }

    $rowcounter = 1;

    while($row = mysql_fetch_array($result)){
        $total = mysql_num_fields($result);
        echo "<tr>";

        for ( $i=0; $i<$total; $i+=1) {
            if ($rowcounter%2==0){echo "<td>";}
            if ($rowcounter%2!=0){echo "<td class='stripe'>";}
            echo str_replace("__", "<br>", $row[$i]);
            echo "</td>";
        }

        if ($rowcounter%2==0){echo "<td>";}
        if ($rowcounter%2!=0){echo "<td class='stripe'>";}
        echo "<input type='button' onclick='delete(\"".$row[0]."\")' value='Delete'>";
        echo "</td>";

        if ($rowcounter%2==0){echo "<td>";}
        if ($rowcounter%2!=0){echo "<td class='stripe'>";}
        echo "<input type='button' onclick='edit(\"".$row[0]."\")' value='Edit'>";
        echo "</td>";

        echo "</tr>";
        $rowcounter = $rowcounter+1;
    }
    echo "</tbody>";
    echo "</table>";
        
}

// need to do something about these forms... at some point.

function table_builder_interactive_edit ($database, $runner, $where='', $order_by='', $direction=''){
    // import global references
    global $current_project;  
      
	// create buffer for if the table is empty  
	$table_buffer = ''; 
	  
    $column_list = array();
    
    // build headings
    $result=mysql_query("DESCRIBE ".$database);

    while($row = mysql_fetch_array($result))
        {$column_list[]=$row[0];}

    $table_buffer .= "<table class='display' id='display'>";
    $table_buffer .= "<thead>";
  
    foreach($column_list as $column){
        $table_buffer .= "<th class='top'>";
        $table_buffer .= $column;
        $table_buffer .= "</th>";
    }

    $table_buffer .= "<th class='top'>Delete</th>";
    $table_buffer .= "<th class='top'>Edit</th>";
    $table_buffer .= "</thead>";
    
    $table_buffer .= "<tbody>";

    if ($order_by == '') {
      if ($where==''){$result=mysql_query("SELECT * FROM ".$database);}
      if ($where!=''){$result=mysql_query("SELECT * FROM ".$database." WHERE ".$where);}
    }
    
    if ($order_by != '') {
      if ($where==''){$result=mysql_query("SELECT * FROM ".$database. " ORDER BY ".$order_by. " ".$direction);}
      if ($where!=''){$result=mysql_query("SELECT * FROM ".$database." WHERE ".$where. " ORDER BY ".$order_by. " ".$direction);}            
    }

    $rowcounter = 1;

    while($row = mysql_fetch_array($result)){
        $total = mysql_num_fields($result);
        $table_buffer .="<tr>";

        for ( $i=0; $i<$total; $i+=1) {
            if ($rowcounter%2==0){$table_buffer .= "<td>";}
            if ($rowcounter%2!=0){$table_buffer .= "<td class='stripe'>";}
            $table_buffer .= str_replace("__", "<br>", $row[$i]);
            $table_buffer .= "</td>";
        }
                
        if ($rowcounter%2==0){$table_buffer .= "<td>";}
        if ($rowcounter%2!=0){$table_buffer .= "<td class='stripe'>";}
        $table_buffer .= "<input type='button' onclick='delete_runner(\"".$row[0]."\", \"".$runner."\")' value='Delete'>";
        $table_buffer .= "</td>";

        if ($rowcounter%2==0){$table_buffer .= "<td>";}
        if ($rowcounter%2!=0){$table_buffer .= "<td class='stripe'>";}
        $table_buffer .= "<input type='button' onclick='popup_display(\"edit\", \"false\", \"".$row[0]."\", \"".$runner."\")' value='Edit'>";
        $table_buffer .= "</td>";

        $table_buffer .= "</tr>";
        $rowcounter = $rowcounter+1;
    }
    $table_buffer .= "</tbody>";
    $table_buffer .= "</table>";
 
 	if($rowcounter>1){echo $table_buffer;}
	if($rowcounter==1){echo "<br><br>Nothing has been added yet. Click the add button above to add something!";}
        
}


?>


