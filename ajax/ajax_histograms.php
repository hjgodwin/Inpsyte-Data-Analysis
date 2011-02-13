<?php
include("../conf/conf.php");
include("../functions/table_builder.php");
include("../functions/single_column_array_builder.php");
include("../functions/header_array_builder.php");
include("../functions/option_list_builder.php");
include("../functions/trial_display.php");

if ($_GET['refresh']=='histogram_form'){

    // get the ppts and lsit them
    $participant_list = single_column_array_builder_groupby("".$current_project.".analyses_output", "ppt_id");
    array_unshift($participant_list,'all');

    // set runner list
    $runner_list = single_column_array_builder_groupby("".$current_project.".analyses_output", "runner");

    // options for number of bins
    $bin_list = array();
    for ($i=1;$i<51; $i++){ $bin_list[] = $i;}

    // Start building the form now
    echo "<article id='participant_list_form' name='histogram_selection_form'>";
    echo "<form>";
	echo "Note: This section is a work in progress.<br>";
    echo "Select Runner:"; option_list_builder('selected_runner', $runner_list, '', '');echo "<br>";
    echo "Select Participant:"; option_list_builder('selected_participant', $participant_list, '', '');echo "<br>";
    echo "Number of Bins:"; option_list_builder('selected_bincount', $bin_list, '', '20');echo "<br>";
    echo "<input type='button' onclick='select_runner()' value='View Histogram Now'>";
    echo "</form>";
    echo "</article>";
}

if (isset($_GET['participant']) && isset($_GET['runner']) && isset($_GET['bincount'])){
   echo "<img src='functions/draw_histogram.php?runner=".$_GET['runner'].
    "&bincount=".$_GET['bincount'].
    "&participant=".$_GET['participant'].
    "&table=".$current_project.
    "'>";

        
}

?>
