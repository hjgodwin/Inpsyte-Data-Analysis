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

include("../conf/conf.php");
include("../functions/table_builder.php");
include("../functions/table_builder_interactive.php");
include("../functions/single_column_array_builder.php");
include("../functions/header_array_builder.php");
include("../functions/option_list_builder.php");
include("../classes/edit_class.php");

if ($_GET['runner_type']=='analyses'){
  $edit_controller = new edit_analyses($current_project);
}

if ($_GET['runner_type']=='responses'){
  $edit_controller = new edit_responses($current_project);
}

if ($_GET['runner_type']=='time_periods'){
  $edit_controller = new edit_time_periods($current_project);
}

// updates list table
if ($edit_controller->action=='refresh' && $edit_controller->action_value=='interactive_table'){
    $edit_controller->interactive_table();
}

// special for analysis popup       
if ($edit_controller->action=='show_popup' || $edit_controller->action=='change_restrictions' 
  || $edit_controller->action=='change_pairs'){
  $edit_controller->popup();
}

// generic delete  
if ($edit_controller->action=='confirm_delete'){  
  $edit_controller->delete_runner();  
}

// generic modify (i.e., add or edit)    
if ($edit_controller->action=='modify'){  
  $edit_controller->add_runner();  
}


?>

