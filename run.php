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

include("javascript/jscript_run.php");

$table_type = $_GET['table'];

echo "<script type='text/javascript'>
        refresh_all(\"".$table_type."\");
        
     </script> ";

if ($_GET['table']=='analyses'){
  echo "<article><h1>Run Analyses</h1>
  </article>"; 
}

if ($_GET['table']=='events'){
  echo "<article><h1>Run Events</h1>
  </article>"; 
}

if ($_GET['table']=='time_periods'){
  echo "<article><h1>Run Time Periods</h1>
  </article>"; 
}

if ($_GET['table']=='responses'){
  echo "<article><h1>Run Responses</h1>
  </article>"; 
}

echo "<article id='interactive_table'>";

echo "</article>";


echo "<article id='runner_popup' title='Running...' style='display:none'>";
echo "<article id='runner_popup_output'></article>";

echo "</article>";

echo "<article id='delete_popup' title='Delete Runner' style='display:none'>";
echo "<article id='delete_popup_output'></article>";

echo "</article>";

// make this into an interactive popup.

//echo "<article><h1>Test Analysis</h1></article>";
echo "<article id='table_demo' style='display:none'>";
echo "</article>";





?>