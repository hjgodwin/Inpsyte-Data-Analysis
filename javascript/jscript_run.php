<script language="javascript" type="text/javascript">
<!--

function refresher (get_target, refresh_target, container){
    $.get(get_target, {refresh: refresh_target}, function(data){$(container).html(data);});
}

function refresh_all(table){ 
    $.get("ajax/ajax_run.php", {runner_type: table, action: "refresh", action_value: "interactive_table"}, 
      function(data){$("#interactive_table").html(data);});
    //add_ppt_set_custom_computation(false);
}

function run_selected(table_type, run_type){
  // clear the popup first
  $("#runner_popup").html("Inpsyte Data Analysis is processing your request. This may take some time.<br> <img src='css/3dmoonanimation.gif' width=50 height=50>");

  var selected_to_run =$('#selected_option :selected').val();
  //alert(selected_to_run);
  //$("#running_popup_output").html('');
  $( "#runner_popup" ).dialog({ modal: true, width:700, height:700 });
   $.get("ajax/ajax_run.php", {runner_type: table_type, action: "run", name: selected_to_run},
    function(data){
        
      $("#runner_popup").html(data);
      
    });
    
    //refresh_all(table_type);
}


</script>
