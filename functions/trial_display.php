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

function trial_display ($participant, $current_trial, $source_image_dir, $output_dir){

    // connect to db
    $username="root";
    $password="";
    mysql_connect(localhost,$username,$password) or die ("Can't find databse!");

    //Create Canvas
    $myImage=ImageCreateTrueColor(1024,768);
    ImageAntiAlias($myImage, true);
    imagesetthickness($myImage, 2);

    //Define Colors, First Color is BG Color
    $background=ImageColorAllocate($myImage, 255, 255, 255);
    $red=ImageColorAllocate($myImage, 255, 0, 0);
    $blue=ImageColorAllocate($myImage, 0, 0, 255);
    $green=ImageColorAllocate($myImage, 0, 255, 0);
    $white=ImageColorAllocate($myImage,255,255,255);
    $black=ImageColorAllocate($myImage,0,0,0);
    $purple =ImageColorAllocate($myImage,255,165,0);#this is really orange
    $teal =ImageColorAllocate($myImage,100,255,255);
    $gray =ImageColorAllocate($myImage, 255, 255, 153);
    $pink = ImageColorAllocate($myImage, 255, 0, 204);

    // draw the background
    imagefilledrectangle( $myImage , 0 , 0 , 1024 , 768 , $background );

    $ias=mysql_query("SELECT * FROM ".$current_project.".aois WHERE trial_index='".$current_trial."' AND ppt_id='".$participant."'");
    #echo "SELECT * FROM ".$current_project.".aois WHERE trial_index=".$current_trial." AND ppt_id='".$participant."'";
    if(mysql_error()) { echo mysql_error();}
        while ($row_ia= mysql_fetch_array($ias)) {
            $x = $row_ia['X_1'];
            $y = $row_ia['Y_1'];
            $x2 = $row_ia['X_2'];
            $y2 = $row_ia['Y_2'];
            $image_name = $row_ia['image_name'];
            $object=imagecreatefromjpeg($source_image_dir."\\".$image_name.".jpg");
            imagecopymerge($myImage, $object, $x, $y, 0, 0, 50, 50, 80);
            imagerectangle ( $myImage , $x, $y , $x2 , $y2 , $purple );
        }

    $result=mysql_query("SELECT * FROM ".$current_project.".".$participant." WHERE trial_index=".$current_trial."") OR DIE ("FFS");

    $row_count = 1;

    while ($row= mysql_fetch_array($result)) {

        $current_x = $row['CURRENT_FIX_X'];
        $current_y = $row['CURRENT_FIX_Y'];
        $next_x = $row['NEXT_FIX_X'];
        $next_y = $row['NEXT_FIX_Y'];
        $last_x = $row['PREVIOUS_FIX_X'];
        $last_y = $row['PREVIOUS_FIX_Y'];
        $fix_index = $row['CURRENT_FIX_INDEX'];
        $trial_index = $row['TRIAL_INDEX'];
        $current_fix_start = $row['CURRENT_FIX_START'];
        $current_fix_end= $row['CURRENT_FIX_END'];
        $current_fix_duration= $row['CURRENT_FIX_DURATION'];
        $before_any = $row['BEFORE_ANY'];
        $interruption_sac = $row['INTERRUPTION_SAC'];
        $after_any = $row['AFTER_ANY'];
        $interruption_duration = $after_any - $interruption_sac;
        $before_time = $interruption_sac - $before_any;

        $header_string= "Participant: ".$participant. " Trial ".$trial_index." Duration:".$interruption_duration. " Before Time:".$before_time;

        if ($row_count == 1){
        #bool imagefilledrectangle ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color )
        imagefilledrectangle($myImage, 0, 0, 1024, 30, $gray);
        imagettftext ( $myImage, 12, 0, 20  , 20  , $black, "C:/windows/fonts/arial.ttf", $header_string);
       
        }
        $row_count = $row_count + 1;

        // before period
        if ($current_fix_start < $interruption_sac && 
            $current_fix_end < $interruption_sac){    
                $set_colour=$red;}
        
        // interruption period  : PURE INTERRUPTION
        if ($current_fix_start > $interruption_sac && 
            $current_fix_end > $interruption_sac &&
            $current_fix_start < $after_any &&
            $current_fix_end < $after_any) {
                $set_colour=$blue;}

       // inter/after period
        if ($current_fix_start > $interruption_sac &&
            $current_fix_end > $interruption_sac &&
            $current_fix_start < $after_any &&
            $current_fix_end > $after_any) {
                $set_colour=$pink;}

       // after period
        if ($current_fix_start > $after_any &&
            $current_fix_end > $after_any){
                $set_colour=$green;}
              
        ImageLine ( $myImage  , $last_x  , $last_y  , $current_x  , $current_y  , $set_colour ); 
        imagettftext ( $myImage, 10, 0, ($current_x+10)  , $current_y  , $set_colour, "C:/windows/fonts/arial.ttf", $fix_index);
        imagettftext ( $myImage, 10, 0, ($current_x+10)  , ($current_y+13)  , $set_colour, "C:/windows/fonts/arial.ttf", $current_fix_duration);
    }

    Imagejpeg($myImage,$output_dir."/".$participant."_".$current_trial.".jpg", 100);
    ImageDestroy($myImage);
}
?>


