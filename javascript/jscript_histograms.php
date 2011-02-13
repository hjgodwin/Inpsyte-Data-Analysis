<script language="javascript" type="text/javascript">
<!--

function refresher (get_target, refresh_target, container){
    $.get(get_target, {refresh: refresh_target}, function(data){$(container).html(data);});
}

function refresh_all(){ 
    refresher("ajax/ajax_histograms.php", "histogram_form", "#histogram_form");
    //select_participant();
}

function select_runner(){
    $("#histogram_display").html("Generating image...");
    var participant_chosen=$('#selected_participant').val();
    var runner_chosen=$('#selected_runner').val();
    var bincount_chosen=$('#selected_bincount').val();
    $.get("ajax/ajax_histograms.php", { participant: participant_chosen, runner: runner_chosen, bincount: bincount_chosen},
        function(data){
            $("#histogram_display").html(data);
    
        });
}

</script>
