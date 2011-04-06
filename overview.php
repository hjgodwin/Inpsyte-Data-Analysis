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


if($pagegroup=="projects"){
	include("projects.php");
}

if($pagegroup=="edit"){
	echo "<h1>Setup</h1>";
	echo "You can use the pages in this section to setup ways to analyse your data.";
}

if($pagegroup=="run"){
	echo "<h1>Run</h1>";
	echo "You can use the pages in this seciton to produce output from your data.";
}

if($pagegroup=="output"){
	echo "<h1>Output</h1>";
	echo "You can use the pages in this section to examine your output in detail.";
}

if($pagegroup=="help"){
	echo "<h1>Help</h1>";
	echo "There's nothing here.";
}


?>