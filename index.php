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

// Includes for database connection as well as first paragraph and first heading for each page
include("conf/conf.php");

if(!isset($_GET["viewer"])){ $content='overview'; }
if(isset($_GET["viewer"])){  $content = $_GET["viewer"]; }

if(!isset($_GET["pagegroup"])){ $pagegroup='projects'; }
if(isset($_GET["pagegroup"])){  $pagegroup = $_GET["pagegroup"]; }

include("include/header.php");
include("functions/table_builder.php");
include("functions/single_column_array_builder.php");

if(!file_exists($content.".php"))
  {
  echo "<h1>Error: Requested Page does not Exist</h1>";
  echo "<p>Sorry but the page you requested does not exist.</p>";
  }

else
  {
  include($content.".php");
  }


include("include/footer.php");

?>