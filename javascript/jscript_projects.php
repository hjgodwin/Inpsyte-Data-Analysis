<script language="javascript" type="text/javascript">
<!--

// you really need to include this from somewhere else
function refresher (get_target, refresh_target, container){
    $.get(get_target, {refresh: refresh_target}, function(data){$(container).html(data);});
}


function refresh_all(){
    refresher("ajax/ajax_projects.php", "change_project_form", "#change_project_article");
    //select_analysis_table();
}


function check_schema(){
     $( "#please_wait_popup" ).dialog({ hide: 'blind', modal: true });
    $.get("ajax/ajax_projects.php", { action: "check_schema"},
        function(data){
            $("#check_schema_output").html(data);
            $( "#please_wait_popup" ).dialog('close');
        });
}

function change_project(){
  var selected_project =$('#project_list :selected').val();
  $.get("ajax/ajax_projects.php", { action: "change_project", project: selected_project},
        function(data){
            window.location = "index.php?viewer=projects";
        });

}

function add_project(){
  var selected_project =$('#name').val();
  $.get("ajax/ajax_projects.php", { action: "add_project", project: selected_project},
        function(data){
        	//$("#add_project_output").html(data);
        	alert("New project created successfully!");
            window.location = "index.php?viewer=projects";
        });

}

function delete_project(){
  var selected_project =$('#project_list :selected').val();
  $.get("ajax/ajax_projects.php", { action: "delete_project", project: selected_project},
        function(data){
            window.location = "index.php?viewer=projects";
        });

}

</script>

