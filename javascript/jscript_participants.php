<script language="javascript" type="text/javascript">
<!--

$(document).ready(function() { refresh_all();});

function refresh_all(){
    refresh_participants_table();
    refresh_directory_list();
    refresh_delete_participant_form();
}

function refresh_participants_table(){

    $.get("ajax/ajax_participants.php", { refresh: 'current_participants'}, function(data){
   //alert("Data Loaded: " + data);
   $("#current_participants").html(data);
    });       
}

function refresh_directory_list(){
    $.get("ajax/ajax_participants.php", { refresh: 'tabbed_directory_list'}, function(data){
   $("#tabbed_directory_list").html(data);
    });

}

function refresh_delete_participant_form(){
   $.get("ajax/ajax_participants.php", { refresh: 'delete_participant_section'}, function(data){
   $("#delete_participant_section").html(data);
    });

}


function popup_add(){
  refresh_all();
  $( "#add_participant_popup" ).dialog({ modal: true, width:700, height:500,
  
  buttons: {
        "Ok": function() {
          $( this ).dialog( "close" );
        }
      } });

}



function add_participants(){
    $("#current_progress").html("Processing your request. Please wait...");
    $.get("ajax/ajax_participants.php", { refresh: 'add_participants'}, function(data){
   $("#add_participant_output").html(data); 
   $("#current_progress").html("Participants added with no problems.");
   refresh_all();
    });

}

function delete_participant(){
    $( "#please_wait_popup" ).dialog({ hide: 'blind', modal: true });
    var participant_id=$('#select_participant_option').val();

   $.post("ajax/ajax_participants.php",
    { refresh: "delete", participant: participant_id },
        function(data){
            
           $( "#please_wait_popup" ).dialog('close');
           $( "#popup_complete" ).dialog();
           refresh_all();
        }); 

 }

function delete_all_participants(){
    $( "#please_wait_popup" ).dialog({ hide: 'blind', modal: true });
    $.post("ajax/ajax_participants.php",
    { refresh: "delete", participant: "all" },
        function(data){
           $( "#please_wait_popup" ).dialog('close');
           $( "#popup_complete" ).dialog();
           refresh_all();
        });


    
}

</script>
