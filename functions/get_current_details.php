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

function get_current_session($participant){
  
    $session_array = explode("_", $participant);
    
    $session = 'unset';
    
    foreach ($session_array as $value){
      // if an 's' is found somewhere, it's a session
      if (preg_match('/s/i', $value)){$session = $value;}
    }
              
    return $session;
}

function get_current_true_participant_id($participant){
    
    $ppt_array = explode("_", $participant);
    
    $participant = 'unset';
    
    foreach ($ppt_array as $value){
      // if a 'p' is found somewhere, it's a participant
      if (preg_match('/p/i', $value)){$ppt = $value;}
    }
              
    return $ppt;
}


















?>

