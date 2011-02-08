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

### Includes for DB connection as well as first paragraph and first heading for each page
include("conf/conf.php");
include("include/header.php");
include("functions/table_builder.php");
include("functions/single_column_array_builder.php");

if(!isset($_GET["viewer"])){ $content='projects'; }
if(isset($_GET["viewer"])){  $content = $_GET["viewer"]; }

if(!file_exists($content.".php"))
  {
  echo "<h1>Error: Requested Page does not Exist</h1>";
  echo "<div class='ui-state-error ui-corner-all' style='width:50%; height:50px;'> 
        <p><span class='ui-icon ui-icon-alert' style='float: left;'></span> 
        <strong>Alert:</strong> Sample ui-state-error style.</p>
      </div>";
  echo "<p>Sorry but the page you requested does not exist.</p>";
  }

else
  {
  include($content.".php");
  }


include("include/footer.php");

?>