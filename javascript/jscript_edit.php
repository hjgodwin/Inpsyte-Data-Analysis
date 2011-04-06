<script language="javascript" type="text/javascript">
<!--

function refresher (get_target, refresh_target, container){
    $.get(get_target, {refresh: refresh_target}, function(data){$(container).html(data);});
}

function refresh_all(table){ 
    $.get("ajax/ajax_edit.php", {runner_type: table, action: "refresh", action_value: "interactive_table"}, 
      function(data){$("#interactive_table").html(data);});
    //add_ppt_set_custom_computation(false);
}
 
function popup_display(add_or_edit, custom, name_to_edit, runner){
  $.get("ajax/ajax_edit.php", {
  action: "show_popup",
  action_value: add_or_edit,
  runner_type : runner,
  popup_type: add_or_edit, 
  is_custom: custom, 
  name: name_to_edit}, 
    function(data){
    $("#maintext").html(data);});
    
  //$( "#runner_popup" ).dialog({ modal: true, width:1000, height:900, position:'top', maxheight:900});
  
  
}

function close_analysis_popup(table){
  //$( "#runner_popup" ).dialog('close');
  window.location.href = "index.php?pagegroup=edit&viewer=edit&table="+table;
}

function change_restrictions(add_or_edit, custom, runner_name, more_or_less, current_count){
   var form_input=$('#popup_form').serialize();
   
   $.get("ajax/ajax_edit.php", {
       action: "change_restrictions",
       action_value: more_or_less,
       runner_type: runner_name,
       /* is_custom: custom,*/
       count: current_count,
       current_data : 'form&' + form_input
   }, 
    function(data){$("#maintext").html(data);}); 
    
}

function change_custom_type (add_or_edit, runner_name, custom_type, current_count){
   var form_input=$('#popup_form').serialize();
      
   $.get("ajax/ajax_edit.php", {
       action: "change_custom_type",
       action_value: custom_type,
       runner_type: runner_name,
       count: current_count,
       current_data : 'form&' + form_input
   }, 
    function(data){
    	$("#maintext").html(data);

    	}); 

}

function change_restriction_type (add_or_edit, runner_name, current_count, change_id){
   var form_input=$('#popup_form').serialize();

   
   $.get("ajax/ajax_edit.php", {
       action: "change_restriction_type",
       action_value: "change_restriction_type",
       runner_type: runner_name,
       count: current_count,
       current_data : 'form&' + form_input
   }, 
    function(data){
    	$("#maintext").html(data);

    	}); 

}

function modify_runner(modify_type){
   var form_input=$('#popup_form').serialize();
      
    $("#popup_output").html('');

	var regex_string = /!|£|%|&| |<|>|,|'|@|#|~|[|]/;
	if(regex_string.test($('#name').val())){ 
		$("#popup_output").append('Name can only contain letters, numbers, or the underscore character.');} 
	else {

   $.get("ajax/ajax_edit.php", {
       action: "modify",
       runner_type: modify_type,
       current_data : 'form&' + form_input}, 
    function(data){$("#popup_output").html(data);
      refresh_all(modify_type);
      }); 
       }
}

function delete_runner_confirm(input_name, runner){
  $("#delete_popup").html("Inpsyte Data Analysis is processing your request. This may take some time.<br> <img id='loading' src='css/3dmoonanimation.gif' width=50 height=50>");
  $.ajax({
   type: "GET",
   url: "ajax/ajax_edit.php",
   data: "action=confirm_delete&"+"name="+input_name+"&runner_type="+runner,
   async:true,
   success: function(msg){
   	  $("#delete_popup").html(msg);
      refresh_all(runner); 
   }
 });
  
}

function delete_runner(name, runner_type){
  $("#delete_popup").html("Are you sure you want to delete the following:<Br><br>"+name); 
  $("#delete_popup").append("<br><input type='button' onclick='delete_runner_confirm(\""+name+"\", \""+runner_type+"\")' value='Delete Now!'>");
  $("#delete_popup").append("<input type='button' onclick='close_delete_popup()' value='Close'>");
  $("#delete_popup" ).dialog({ modal: true});
  
}


</script>
