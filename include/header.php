<html> 
 <head> 
  <title>Inpsyte Data Analysis</title> 
 
  <link type="text/css" rel="stylesheet" href="css/ida.css"> 
  <link type="text/css" href="css/black-tie/jquery-ui-1.8.9.custom.css" rel="stylesheet"> 
  <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script> 
  <script type="text/javascript" src="js/jquery-ui-1.8.9.custom.min.js"></script> 
   
</head> 
 
<body> 
  
 
  

 
<div id = 'containing_div'>
 <div id = 'header'>
	<div id='navigation'>
		<ul>
		<li 
		<?php if ($pagegroup=='projects'){ echo "id='current'";} ?> >
			<a href='index.php?pagegroup=projects&viewer=overview'>PROJECTS </a></li>
		<li
		<?php if ($pagegroup=='edit'){ echo "id='current'";} ?> >
			<a href='index.php?pagegroup=edit&viewer=overview'>SETUP </a></li>
		<li
		<?php if ($pagegroup=='run'){ echo "id='current'";} ?> >
			<a href='index.php?pagegroup=run&viewer=overview'>RUN </a></li>
		<li
		<?php if ($pagegroup=='output'){ echo "id='current'";} ?> >
			<a href='index.php?pagegroup=output&viewer=overview'>OUTPUT </a></li>
		<li
		<?php if ($pagegroup=='help'){ echo "id='current'";} ?> >
			<a href='index.php?pagegroup=help&viewer=overview'>HELP </a></li>
		</ul>
 	</div> 
 </div>
<div id='nav_column'> 
	<ul>
		
		<?php 
		if ($pagegroup=='projects'){
			echo ""; /* Some placeholders for furture development
			<li><a href='newip.html'>Add Project</a></li>
			<li><a href='newip.html'>Delete Project</a></li>
			<li><a href='newip.html'>Copy Project</a></li>";*/
		}

		if ($pagegroup=='edit'){
			echo "<li><a href='index.php?pagegroup=edit&viewer=participants'>Participants</a></li>
 				<li><a href='index.php?pagegroup=edit&viewer=edit&table=responses'>Responses</a></li>
 				<li><a href='index.php?pagegroup=edit&viewer=edit&table=time_periods'>Time Periods</a></li>
 				<li><a href='index.php?pagegroup=edit&viewer=edit&table=analyses'>Analyses</a></li>
 				<li><a href='index.php?pagegroup=edit&viewer=edit&table=custom'>Custom</a></li>";
		}
		
		if ($pagegroup=='run'){
			echo "<li><a href='index.php?pagegroup=run&viewer=run&table=responses'>Responses</a></li>
 				<li><a href='index.php?pagegroup=run&viewer=run&table=time_periods'>Time Periods</a></li>
 				<li><a href='index.php?pagegroup=run&viewer=run&table=analyses'>Analyses</a></li>
 				<li><a href='index.php?pagegroup=run&viewer=run&table=custom'>Custom</a></li>";
		}
		
		if ($pagegroup=='output'){
			//echo "<li><a href='index.php?pagegroup=output&viewer=view_trials'>View Trials</a></li>
 				echo "
 				<li><a href='index.php?pagegroup=output&viewer=output'>Output</a></li>
 				<li><a href='index.php?pagegroup=output&viewer=histograms'>Histograms</a></li>
 				<li><a href='index.php?pagegroup=output&viewer=inspect'>Inspect Data</a></li>";
		}
		
		if ($pagegroup=='help'){
			echo "";
		}		
		
		?>
		
		
		
		
	</ul>
	

</div>

<div id='maintext'> 
	
