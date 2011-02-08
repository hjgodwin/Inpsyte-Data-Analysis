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

function import_tabbed_file ($ppt_id, $dir){

    // import global references
    global $current_project;

    $result = mysql_query("CREATE TABLE IF NOT EXISTS ".$current_project.".".$ppt_id." ( dummy INT(10) NULL )");

    $fcontents = file ("../tabbedrawdata/".$ppt_id.".txt");
    $firstline = $fcontents[0];
    $headers = explode("\t", $firstline);

    # create the headers
    for ($j=0; $j<count($headers); $j++) {
        $result = mysql_query("ALTER TABLE ".$current_project.".".$ppt_id." ADD ".$headers[$j]." VARCHAR(30)");
    }

    $result = mysql_query("ALTER TABLE ".$current_project.".".$ppt_id." DROP dummy");

    $filename_full = addslashes($dir."/".$ppt_id.".txt");

    $result = mysql_query("LOAD DATA LOCAL INFILE '".$filename_full."'
        INTO TABLE ".$current_project.".".$ppt_id."
        FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES");
    if(mysql_error()) { echo mysql_error(); exit();}

    $result =mysql_query("ALTER TABLE ".$current_project.".".$ppt_id." ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY");

    //set ppt_id as recording session label
    $result =mysql_query("UPDATE ".$current_project.".".$ppt_id." set RECORDING_SESSION_LABEL='".$ppt_id."'");

}


?>
