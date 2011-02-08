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

function table_builder ($database, $where=''){
    $column_list = array();
    
	// buffer a string so we can spew it out only when it has more than 0 rows in.
	$table_buffer = '';
	
    #build headings
    $result=mysql_query("DESCRIBE ".$database);

    while($row = mysql_fetch_array($result))
        {$column_list[]=$row[0];}

    $table_buffer .= "<table class='display' id='example'>";
    $table_buffer .= "<thead>";

    foreach($column_list as $column){
        $table_buffer .= "<th class='top'>";
        $table_buffer .= $column;
        $table_buffer .= "</th>";
    }

    $table_buffer .= "</thead>";
    
    $table_buffer .= "<tbody>";

    if ($where==''){$result=mysql_query("SELECT * FROM ".$database);}
    if ($where!=''){$result=mysql_query("SELECT * FROM ".$database." WHERE ".$where);}

    $rowcounter = 1;

    while($row = mysql_fetch_array($result)){
        $total = mysql_num_fields($result);
        $table_buffer .= "<tr>";

        for ( $i=0; $i<$total; $i+=1) {
	          if ($rowcounter%2==0){$table_buffer .= "<td>";}
            if ($rowcounter%2!=0){$table_buffer .= "<td class='stripe'>";}
            $table_buffer .= str_replace("__", "<br>", $row[$i]);
	          $table_buffer .= "</td>";
        }

        $table_buffer .= "</tr>";
        $rowcounter = $rowcounter+1;
    }
    $table_buffer .= "</tbody>";
    $table_buffer .= "</table>";
    
	if($rowcounter>1){echo $table_buffer;}
	    
}

?>


