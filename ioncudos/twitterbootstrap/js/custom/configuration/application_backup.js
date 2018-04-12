
//application_backup.js
$(function(){
    $('#backup_list_table').dataTable().fnDestroy();
    $('#backup_list_table').dataTable({
        "aaSorting" : [[3, 'desc']],
        "sPaginationType": "bootstrap",
        "bSort": true
    }); 
});

//Function is to take backup.
$("#backup").on("click",function(){
    $("#loading").show();
    $.ajax({
        type: "POST",
        url: base_url + 'configuration/application_backup/get_application_backup',
        data: {},
        success: function(data) {
            location.reload();
            $("#loading").hide();
        }
    });       
});

//function is to store file name
$(".get_file_name").click(function(){
    $("#file_name").val($(this).attr('data-file'));
});

//function is to delete file from folder.
$("#delete_selected").click(function(){
    $("#loading").show();
    var file_name = $("#file_name").val();
    $.ajax({
        type: "POST",
        url: base_url + 'configuration/application_backup/delete_file',
        data: {
            'file' : file_name
        },
        success: function(data) {
            location.reload();
            $("#loading").hide();
        }
    });
});
    //File ends here.
