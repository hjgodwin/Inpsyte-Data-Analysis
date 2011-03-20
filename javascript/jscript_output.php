<script language="javascript" type="text/javascript">
<!--



// you really need to include this from somewhere else
function refresher (get_target, refresh_target, container){
    $.get(get_target, {refresh: refresh_target}, function(data){$(container).html(data);});
}


function refresh_all(){
    refresher("ajax/ajax_output.php", "output_list_form", "#output_selector");
    refresher("ajax/ajax_output.php", "output_view", "#output_view");
}

function save_db(){
  //$( "#please_wait_popup" ).dialog();
  $.get("ajax/ajax_output.php",
        { create_excel: "now" },
            function(data){
               //alert(data);
               //$( "#please_wait_popup" ).dialog('close');
               $("#excel_popup").html(data);
               $( "#excel_popup" ).dialog({ hide: 'blind', modal: true });
               
               refresh_all();
            });
}

</script>

