// Help List Page Script starts from here.
$('#help_doc_list').dataTable({
    "sPaginationType": "bootstrap"
});
var base_url = $('#get_base_url').val();
$("#hint a").tooltip();
var currentID;
var serial_no;
var table_row;
var id;
var file_uploaded_id
function currentIDSt(id)
{
    currentID = id;
}
$('.get_topic_id').live("click", function () {
    serial_no = $(this).attr('id');
    table_row = $(this).closest("tr").get(0);
});

$('.help_document').on('change', '.document_list', function () {
    var entity_id = $('#entity').val();
    var post_data = {
        'entity_id': entity_id
    }
    $.ajax(
            {
                type: "POST",
                url: base_url + 'configuration/help_content/document_list/' + entity_id,
                //data: post_data,
                dataType: 'json',
                success: populate_table
            }
    );
});
function populate_table(msg) {
    $('#help_doc_list').dataTable().fnDestroy();
    $('#help_doc_list').dataTable(
            {
                "aoColumns": [
                    {"sTitle": "Sl No.", "sClass": "", "mData": "sl_no"},
                    {"sTitle": "File Name", "sClass": "", "mData": "file_name"},
                    {"sTitle": "Delete", "sClass": "center", "mData": "delete"}
                ], "aaData": msg,
                "sPaginationType": "bootstrap"});
}

/*
 * Function is to delete the help data.
 * @param - entity id is used to delete the particular help data.
 * returns the succes message.
 */
$('.get_file_id').live('click', function () {
    file_uploaded_id = $(this).attr('id');
    serial_no = $(this).attr('id');
    table_row = $(this).closest("tr").get(0);
    $('#file_delete_modal').modal('show');
});

//function to delete Page Name.
function delete_data() {
    var delete_path = base_url + 'configuration/help_content/delete_data/';
    $.ajax({type: "POST",
        url: delete_path + currentID,
        success: function (msg) {
            var oTable = $('#example').dataTable();
            oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
        }
    });
}

//function to delete uploaded file
function file_delete() {
    var delete_path = base_url + 'configuration/help_content/delete_file/';
    $.ajax({type: "POST",
        url: delete_path + file_uploaded_id,
        success: function (msg) {
            var oTable = $('#help_doc_list').dataTable();
            oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
            location.reload();
        }
    });
}

$('#entity').change(function () {
    var file_val = $('#userfile').val();
    var entity_val = $('#entity').val();
    if (file_val == '' || entity_val == '') {
        $('#upload').prop('disabled', true);
    } else {
        $('#upload').prop('disabled', false);
    }
});

$('#userfile').change(function () {
    var file_val = $('#userfile').val();
    var entity_val = $('#entity').val();
    if (entity_val == '' || file_val == '') {
        $('#upload').prop('disabled', true);
    } else {
        $('#upload').prop('disabled', false);
    }
});

$('#upload').on('click', function () {
    var file_val = $('#userfile').val();
    var entity_val = $('#entity').val();
    alert(file_val + "" + entity_val);
    if (entity_val == '' || file_val == '') {
        $('#help_upload').modal('show');
    } else {
        $('#help_upload_form').submit();
    }

});

$('#error_msg').each(function () {
    var error_msg = $(this);
    var error_msg_value = error_msg.text();
    if ($.trim(error_msg_value) != '') {
        //alert(error_msg_value);
        $('#invalid_file_extension').modal('show');
        error_msg.text('');
    }
});

$('.delbutton').click(function (e) {
    e.preventDefault();
    var oTable = $('#example').dataTable();
    var row = $(this).closest("tr").get(0);
    oTable.fnDeleteRow(oTable.fnGetPosition(row));
});

$('.upload_data').click(function (e) {
    var entity_id = $(this).attr('data-val');
    $('#file_upload').val($(this).attr('data-val'));

    display_data();
});

function display_data() {
    var entity_id = $('#file_upload').val();

    var post_data = {
        'entity_id': entity_id
    }

    $.ajax({
        type: "POST",
        url: base_url + 'upload_data/upload_data/modal_display',
        data: post_data,
        async: false,
        success: function (data) {
            $('#art').html(data);
            $('#loading').hide();
            $('#mymodal').modal('show');
        }
    });
}

$('.upload_data').on('click', function (e) {
    var uploader = document.getElementById('uploaded_file');
    var entity_id = $(this).attr('data-val');

    var post_data = {
        'entity_id': entity_id
    }
    upclick({
        element: uploader,
        action_params: post_data,
        action: base_url + 'upload_data/upload_data/modal_upload',
        onstart: function (filename) {
            (document).getElementById('loading_edit').style.visibility = 'visible';
        },
        oncomplete: function (response_data) {
            if (response_data == "file_name_size_exceeded") {
                $('#file_name_size_exc').modal('show');
            } else if (response_data == "file_size_exceed") {
                $('#larger').modal('show');
            }
            display_data();
            (document).getElementById('loading_edit').style.visibility = 'hidden';
        }
    });
});

//deleting the file
$('#art').on('click', '.help_entity', function (e) {
    var del_id = $(this).attr('data-id');

    $('#delete_file').modal('show');
    $('#delete_selected').click(function (e) {
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: base_url + 'upload_data/upload_data/modal_delete_file',
            data: {'del_id': del_id},
            success: function (data) {
                $('#loading').hide();
                display_data();
            }
        });
        $('#delete_file').modal('hide');
    });

});

// Help List Page Script Ends here.
