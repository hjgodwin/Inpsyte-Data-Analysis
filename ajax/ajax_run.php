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
include("../classes/runner_class.php");

if ($_GET['runner_type']=='analyses'){
  $run_controller = new runner_analyses($current_project);
}

if ($_GET['runner_type']=='responses'){
  $run_controller = new runner_responses($current_project);
}

if ($_GET['runner_type']=='time_periods'){
  $run_controller = new runner_time_periods($current_project);
}

//updates list table
if ($run_controller->runner_interface->action=='refresh' && $run_controller->runner_interface->action_value=='interactive_table'){
    $run_controller->table_construct();
}

// runs a runner!
if ($run_controller->runner_interface->action=='run'){
    $run_controller->select_and_run();
}


?>

