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
  echo "<div><h1>Run Analyses</h1>
  </div>"; 
}

if ($_GET['table']=='events'){
  echo "<div><h1>Run Events</h1>
  </div>"; 
}

if ($_GET['table']=='time_periods'){
  echo "<div><h1>Run Time Periods</h1>
  </div>"; 
}

if ($_GET['table']=='responses'){
  echo "<div><h1>Run Responses</h1>
  </div>"; 
}

echo "<div id='interactive_table'>";

echo "</div>";


echo "<div id='runner_popup' title='Running...' style='display:none'>";
echo "<div id='runner_popup_output'></div>";

echo "</div>";

echo "<div id='delete_popup' title='Delete Runner' style='display:none'>";
echo "<div id='delete_popup_output'></div>";

echo "</div>";

// make this into an interactive popup.

//echo "<div><h1>Test Analysis</h1></div>";
echo "<div id='table_demo' style='display:none'>";
echo "</div>";





?>