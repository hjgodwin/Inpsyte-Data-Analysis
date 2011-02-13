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

include("javascript/jscript_projects.php");
echo "<script type='text/javascript'>
        refresh_all();
     </script> ";
echo "<div><h1>Inpsyte Data Analysis: Dashboard / Administration</h1>
</div>";

// change current project
echo "<div id='change_project_div'>";
echo "</div>";

// check participant schema
echo "<div id='check_schema_div'>";
echo "</div>";
echo "<div id='check_schema_output'>";
echo "</div>";

?>