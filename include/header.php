<html> 
 <head> 
  <title>Inpsyte Data Analysis</title> 
 
  <link rel="stylesheet" href="css/ge2.css" /> 
  <link type="text/css" href="css/black-tie/jquery-ui-1.8.9.custom.css" rel="stylesheet" /> 
  <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script> 
  <script type="text/javascript" src="js/jquery-ui-1.8.9.custom.min.js"></script> 
   
   
 <script>
			$().ready(function() {
				
				$('#edit').click(toggle_column);
				$('#run').click(toggle_column);
				$('#output').click(toggle_column);
				$('#help').click(toggle_column);

 			});
 			
 			function toggle_column(){
 			
 				if( $('#run_dropdown').is(':visible') && this.id != 'run' ) {
    				hide_column('#run_dropdown');
				}
 			
 				if( $('#edit_dropdown').is(':visible') && this.id != 'edit' ) {
    				hide_column('#edit_dropdown');
				}
				
				if( $('#output_dropdown').is(':visible') && this.id != 'output' ) {
    				hide_column('#output_dropdown');
				}
				
				if( $('#help_dropdown').is(':visible') && this.id != 'help' ) {
    				hide_column('#help_dropdown');
				}
 			
 				var hovername = '#'+this.id+'_dropdown'; 			
 				$(hovername).slideToggle('slow', function() {});
 				
 			}
 			
 			function hide_column(column){		
 				$(column).hide();		
 			}
 			
		  </script>

</head> 
 
<body> 
 
 
 
 <header>
 
	<article id='navigation'>
		<ul>
		<li id='edit'><a href='#'>EDIT <img src='css/triangle.png'></a></li>
		<li id='run'><a href='#'>RUN <img src='css/triangle.png'></a></li>
		<li id='output'><a href='#'>OUTPUT <img src='css/triangle.png'></a></li>
		<li id='help'><a href='#'>HELP <img src='css/triangle.png'></a></li>
		</ul>
		
 		<article id='edit_dropdown' style='display:none;'>
 			<ul>
 				<li><a href='index.php?viewer=participants'>Participants</a></li>
 				<li><a href='index.php?viewer=edit&table=responses'>Responses</a></li>
 				<li><a href='index.php?viewer=edit&table=time_periods'>Time Periods</a></li>
 				<li><a href='index.php?viewer=edit&table=analyses'>Analyses</a></li>
 			</ul> 			
 		</article>

		
		
 		<article id='run_dropdown' style='display:none;'>
 			<ul>
 				<li><a href='index.php?viewer=run&table=responses'>Responses</a></li>
 				<li><a href='index.php?viewer=run&table=time_periods'>Time Periods</a></li>
 				<li><a href='index.php?viewer=run&table=analyses'>Analyses</a></li>
 			</ul> 			
 		</article>
 		
 		<article id='output_dropdown' style='display:none;'>
 			<ul>
 				<li><a href='index.php?viewer=view_trials'>View Trials</a></li>
 				<li><a href='index.php?viewer=inspect'>Inspect Data</a></li>
 				<li><a href='index.php?viewer=output'>Output</a></li>
 			</ul> 			
 		</article>
 		
 		
		
 		<article id='help_dropdown' style='display:none;'>
 			<ul>
 				<li><a href='http://inpsyte.psychwire.co.uk'>Help files</a></li>
 		
 			</ul> 			
 		</article>
 		
 	</article>
   
 </header>
 
<br><br><br><br>
<article id='maintext'> 

