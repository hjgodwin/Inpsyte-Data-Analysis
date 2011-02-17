<?php

/* pChart library inclusions */
include("../pchart/class/pData.class");
include("../pchart/class/pDraw.class");
include("../pchart/class/pImage.class");
include("../conf/conf.php");

$runner = $_GET['runner'];
$participant = $_GET['participant'];
$bincount = $_GET['bincount'];
$table = $_GET['table'];

/*
// USED FOR DEBUGGING

$runner = 'after_fix_dur';
$participant = 'all';
$table='interruption';
$bincount = 20;
*/

///////////////////////////////////////////////////////////////////////////////////////////////////////
// THIS CALCULATES THE HISTOGRAM DATA ITSELF : BINS AND LABELS FOR GRAPH
///////////////////////////////////////////////////////////////////////////////////////////////////////

  if($participant!='all'){
    $min_query_string = "SELECT @min := MIN(value) FROM ".$table.".analyses_output 
      WHERE runner='".$runner."' AND ppt_id='".$participant."';";
  }
  if($participant=='all'){
    $min_query_string = "SELECT @min := MIN(value) FROM ".$table.".analyses_output 
      WHERE runner='".$runner."';";
  }
  
  $min_query = mysql_query($min_query_string);if(mysql_error){echo mysql_error();} 
  $min_data = mysql_fetch_row($min_query);if(mysql_error){echo mysql_error();} 
  $min = $min_data[0];

  if($participant=='all'){
    $range_query_string = "SELECT @range := MAX(value)-MIN(value) FROM ".$table.".analyses_output
      WHERE runner='".$runner."';";
  }  
    
  if($participant!='all'){
    $range_query_string = "SELECT @range := MAX(value)-MIN(value) FROM ".$table.".analyses_output
      WHERE runner='".$runner."' AND ppt_id='".$participant."';";
  }     
  
  $range_query = mysql_query($range_query_string);if(mysql_error){echo mysql_error();} 
  $range_data = mysql_fetch_row($range_query);
  $range = $range_data[0];

  if($participant!='all'){  
    $binsize_query_string = "SELECT @binsize := @range/".$bincount." FROM ".$table.".analyses_output 
      WHERE runner='".$runner."' AND ppt_id='".$participant."' LIMIT 1;";
  }    

  if($participant=='all'){  
    $binsize_query_string = "SELECT @binsize := @range/".$bincount." FROM ".$table.".analyses_output
      WHERE runner='".$runner."' LIMIT 1;";
  }  
  
  //echo $min_query_string.$range_query_string.$binsize_query_string;
  
  $binsize_query = mysql_query($binsize_query_string); if(mysql_error){echo mysql_error();}    
  $binsize_data = mysql_fetch_row($binsize_query);if(mysql_error){echo mysql_error();} 
  $binsize = $binsize_data[0];
  
  // setup bin labels
  $bin_labels = array();
  
  // now build the key select query
  $query_core = 'SELECT ';
  
  // counter for skipping labels so the x-axis doesn't get flooded
  $c = 5;
  
  for($i = 0; $i < ($bincount); $i++){
    
    $next = $i+1;
    
    // build key subquery
    $query_core = $query_core . "(SELECT count(value) from ".$table.".analyses_output
      WHERE runner='".$runner."' AND ";
      
    if($participant!='all'){
      $query_core = $query_core . " ppt_id='".$participant."' AND ";}
      
    $query_core = $query_core . " value>=(@min+(".$i."*@binsize)) and value < (@min+(".($next)."*@binsize))) AS bin_".$i.",";
    
    // add labels
    if ($c==5){
      $bin_labels[] = "".($min+((($i*$binsize)+($next*$binsize))/2))."";
      
      $c = 0;
    }
    else {$bin_labels[] = " ";}
    $c++;
  }
  
  $histogram_query = trim($query_core, ",");

  //echo $histogram_query;

  $result = mysql_query($histogram_query); if(mysql_error){echo mysql_error();}   
  
  // call the query for the histogram data
  $histogram_data = mysql_fetch_row($result);if(mysql_error){echo mysql_error();} 
  
///////////////////////////////////////////////////////////////////////////////////////////////////////
// GET STATS FOR THE GRAPH
///////////////////////////////////////////////////////////////////////////////////////////////////////

if($participant!='all'){
    $stats_query_string = "SELECT MIN(value), MAX(value), AVG(value), STDDEV(value), COUNT(value) 
      FROM ".$table.".analyses_output 
      WHERE runner='".$runner."' AND ppt_id='".$participant."' LIMIT 1;";
  }
if($participant=='all'){
    $stats_query_string = "SELECT MIN(value), MAX(value), AVG(value), STDDEV(value), COUNT(value)  
      FROM ".$table.".analyses_output 
      WHERE runner='".$runner."' LIMIT 1;";
  }
  
  $stats_query = mysql_query($stats_query_string);if(mysql_error){echo mysql_error();} 
  $stats_data = mysql_fetch_row($stats_query);if(mysql_error){echo mysql_error();} 
  $stat_min = $stats_data[0];
  $stat_max = $stats_data[1];
  $stat_avg = $stats_data[2];
  $stat_stdev = $stats_data[3];
  $stat_count = $stats_data[4];

///////////////////////////////////////////////////////////////////////////////////////////////////////
// THIS DRAWS THE GRAPH
///////////////////////////////////////////////////////////////////////////////////////////////////////

// Create and populate the pData object 
$MyData = new pData();  
$MyData->addPoints($histogram_data,"histogram_data");
$MyData->setAxisName(0,"Count");
$MyData->addPoints($bin_labels,"Labels");
$MyData->setSerieDescription("Labels",$runner);
$MyData->setAbscissa("Labels");

// change bar colour
$serieSettings = array("R"=>0,"G"=>0,"B"=>0,"Alpha"=>100);
$MyData->setPalette("histogram_data",$serieSettings);

// Create the pChart object 
$myPicture = new pImage(700,500,$MyData);

// Add a border to the picture 
$myPicture->drawRectangle(0,0,699,499,array("R"=>0,"G"=>0,"B"=>0));

// Write the picture title 
$myPicture->setFontProperties(array("FontName"=>"../pchart/fonts/verdana.ttf","FontSize"=>6));
$myPicture->drawText(500,150,"Min:".$stat_min,array("R"=>0,"G"=>0,"B"=>0));
$myPicture->drawText(500,160,"Max:".$stat_max,array("R"=>0,"G"=>0,"B"=>0));
$myPicture->drawText(500,170,"Avg:".$stat_avg,array("R"=>0,"G"=>0,"B"=>0));
$myPicture->drawText(500,180,"St. Dev:".$stat_stdev,array("R"=>0,"G"=>0,"B"=>0));
$myPicture->drawText(500,190,"Count:".$stat_count,array("R"=>0,"G"=>0,"B"=>0));

// Write the chart title  
$myPicture->setFontProperties(array("FontName"=>"../pchart/fonts/verdana.ttf","FontSize"=>11));
$myPicture->drawText(350,55,$runner." Histogram",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

// Draw the info
$myPicture->setGraphArea(60,60,600,470);
$myPicture->drawScale(array("DrawSubTicks"=>TRUE));
$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
$myPicture->setFontProperties(array("FontName"=>"../pchart/fonts/verdana.ttf","FontSize"=>11));
$myPicture->drawBarChart(array("DisplayValues"=>FALSE, "Rounded"=>FALSE,"Surrounding"=>60));
$myPicture->setShadow(FALSE);

// Draw the picture
$myPicture->autoOutput("../pictures/example.drawBarChart.png");


?>
