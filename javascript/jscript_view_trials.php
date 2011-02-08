<script language="javascript" type="text/javascript">
<!--

function refresher (get_target, refresh_target, container){
    $.get(get_target, {refresh: refresh_target}, function(data){$(container).html(data);});
}

function refresh_all(){ 
    refresher("ajax/ajax_view_trials.php", "participant_list", "#participant_list_form");
    //select_participant();
}

function select_participant(){
    var participant_chosen=$('#selected_participant').val();
    $( "#please_wait_popup" ).dialog({ hide: 'blind', modal: true });
    $.get("ajax/ajax_view_trials.php", { participant: participant_chosen},
        function(data){
            $("#trial_view").html(data);
            $( "#please_wait_popup" ).dialog('close');
        });
}

</script>
