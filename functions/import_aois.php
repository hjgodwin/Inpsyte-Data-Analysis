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

function import_aois($participant, $number_of_trials, $original_dir){ //note: get this to work out how many trials there are somehow

   // import global references
   global $current_project; 
    
    for ($q=1;$q<=$number_of_trials; $q++){
    	
		// start basics
        $imported_content= array();
        chdir($original_dir);
        chdir('..');
		
		// start importing files
        $aoi_filename = addslashes(getcwd()."/aois/".$participant."/IA_".$q.".ias");
        $raw_aoi = fopen( $aoi_filename, 'r' );
        $total_lines = count(file($aoi_filename));
        $line_count = 0;

            while ($line_count<$total_lines) {

                $buffer = $participant."\t".$q."\t".fgets($raw_aoi);
                $imported_content[] = $buffer;
                $line_count = $line_count+1;
                                    }
        fclose($raw_aoi);

		// now the files are summarised in an array
		// we can write the files out
        $compiled_aoi= fopen('compiled_aois/aoi'.$participant.'.txt','a');
        foreach($imported_content as $k=>$kk){
        	
            $new_stuff_to_write= $imported_content[$k];
			
			// finally write it
            fwrite($compiled_aoi,$new_stuff_to_write); }
		
        fclose($compiled_aoi);

    }

	// import the compiled one
    $compiled_aoi_filename = addslashes(getcwd().'/compiled_aois/aoi'.$participant.'.txt');
    $result = mysql_query("LOAD DATA LOCAL INFILE '".$compiled_aoi_filename."' INTO TABLE ".$current_project.".aois FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n'");
    $result =mysql_query("DELETE FROM ".$current_project.".aois WHERE SUBSTRING(shape, 1, 1)='-'");
    $result = mysql_query("UPDATE ".$current_project.".aois SET image_name = SUBSTRING(SUBSTRING_INDEX(IA_LABEL, '.', 1), 5, 20)");

    chdir($original_dir); // this takes us back to the dir we were in originally so everything else can work
}

?>
