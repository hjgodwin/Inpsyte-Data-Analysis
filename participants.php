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

include("javascript/jscript_participants.php");
include("functions/header_array_builder.php");
include("functions/option_list_builder.php");

$current_directory = getcwd();

echo "<article><h1>Participants</h1>
<form>Some important information:
	<ul>
	<li>Use this page to add participants to the project.
	Participant data needs to be added to the <b>tabbedrawdata</b> directory that can be found here:<b>
	".$current_directory."\\".$current_project."\\tabbedrawdata</b>. You will need to browse the contents
	of your hard drive to find this folder.
	</li>
	<li>To get this to work properly, participant IDs need to be preceded by the letter 'p'. </li>
	<li>If you want to	have multiple sessions, you will need a session number preceded by the letter 's'.</li> 
	<li>It's also important	that if you have both participant and session IDs, they are separated by an underscore.</li> 
	<li>So what this means 	is you should basically name your files according to the format such as p01_s1 for participant 1, session 1. </li>
	<li>Note that the raw data files need to be saved as tabbed-delimited text files. More options for this 
	will be added in due course.</li>
	<input type='button' onclick='popup_add()' value='Add Participants'></form>
	</article>";

echo "<article id='current_participants'>";
echo "</article>";

echo "<article id='add_participant_popup' title='Add Participants' style='display:none;'>";
    
echo "<article id='tabbed_directory_list'>";
echo "</article>"; 
echo "<article id='current_progress'></article>";
echo "</article>";


echo "<article id='delete_participant_section'>";
echo "</article>";



?>