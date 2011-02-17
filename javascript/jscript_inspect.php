<script language="javascript" type="text/javascript">
<!--

// you really need to include this from somewhere else
function refresher (get_target, refresh_target, container){
    $.get(get_target, {refresh: refresh_target}, function(data){$(container).html(data);});
}


function refresh_all(){
    refresher("ajax/ajax_inspect.php", "runner_select_form", "#runner_select_form");
    draw_table();
}

function draw_table(){

	$("#trial_view").html('');

	// basic values
	var trial_input =$('#trial_list :selected').val();
	var analyses_input =$('#analyses_list :selected').val();
	var participant_input =$('#participant_list :selected').val();

	var column_string = '';

	// selected columns for table
	$("#selected option").each(function()
		{ //alert($(this).val());
		  var column = $(this).val();
          column_string = column_string + '&column_list[]=' + column; 
		});

	$.get("ajax/ajax_inspect.php", { action: "draw_table", trial: trial_input, 
		analyses: analyses_input,
		participant: participant_input,
		columns: 'yes'+column_string},
        function(data){
            $("#trial_view").html(data);
        });
}

function slide_toggle(){
	$('#slide_section').slideToggle('slow', function() {
  });
}

function add_column(){
	$('#available option:selected').remove().appendTo('#selected');
	draw_table();
}

function remove_column(){
	$('#selected option:selected').remove().appendTo('#available');
	draw_table();
}

</script>

