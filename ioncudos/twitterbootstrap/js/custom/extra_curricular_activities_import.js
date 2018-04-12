var uploader = document.getElementById('uploade_activity_data');
var activity_id = $('#activity_id').val();
upclick({
    element: uploader,
    action: base_url + 'Extra_curricular_activities/Extra_curricular_activities/to_database' + '/' + activity_id,
    oncomplete: function (response_data) {        
        if ($.trim(response_data) == '0') {
            //$('#incorrect_file_name').modal('show');
            $('#modal_title').html('Invalid file name');
            $('#modal_body').html('Unable to display the file details because the file name is invalid.');
            $('#message_modal').modal('show');
        } else if ($.trim(response_data) == '2') {
            //$('#incorrect_file_header').modal('show');
            $('#modal_title').html('Invalid file header');
            $('#modal_body').html('Unable to display the file details because the file headers are invalid or might be trying to upload incorrect file.');
            $('#message_modal').modal('show');
        } else if ($.trim(response_data) == '3') {
            //$('#csv_file_empty').modal('show');
            $('#modal_title').html('Warning');
            $('#modal_body').html('PO activity template is empty.');
            $('#message_modal').modal('show');
        } else {
            //display data in temp_generate_table
            display_uploaded_data();
        }
    }
});

function display_uploaded_data() {

    $.ajax({
        type: "POST",
        url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/get_temp_table_data',
        data: {
            activity_id: $('#activity_id').val()
        },
        success: function (dataa) {
            var data = JSON.parse(dataa);
            var heading = data.heading;
            $('#imprt_file_data').html(data.table);
            inline_edit(heading);
        }
    });
}
function my_validation(td) {
    var table;
    var flag = true;
    var $error_msg = '';

    $(td).each(function () {

        var $input = $(this).find('.inline-input-val').val();
        var $td_index = $(this).index();

        if (typeof table === 'undefined') {
            $table = $(this).closest('table');
        }

        var $header = $table.find('th').eq($td_index).text();
        var $total_mark = $header.substring($header.indexOf('(') + 1, $header.indexOf(')'));
        var $question = $header.substring(0, $header.indexOf('('));

        //validate total mark
        if ($total_mark && parseFloat($input) > parseFloat($total_mark)) {
            $error_msg += "<p>Invalid input for \"" + $question + "\" ,It should not be greater than " + $total_mark + "</p>";
            flag = false;
        }
    });

    if (flag) {
        return true;
    } else {
        
        $('#modal_title').text('Error');
        $('#modal_body').html($error_msg);
        $('#message_modal').modal('show');
    }

}
function inline_edit(heading) {
    $('#table_imported_data').Tabledit({
        url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/update_temp_table_data',
        hideIdentifier: true,
        restoreButton: false,
        deleteButton: true,
        Button: false,
        elementValidator: my_validation,
        columns: {
            identifier: [0, "temp_id"],
            editable: heading,
        },
        buttons: {
            edit: {
                class: '',
                html: '<span class="icon-pencil"></span>',
                action: 'edit'
            },
            delete: {
                class: '',
                html: '<span class="icon-remove"></span>',
                action: 'delete'
            }
        },
        onSuccess: function (data, textStatus, jqXHR) {
            $('#modal_title').text(data.key);
            $('#inline_edit_modal_body').html(data.message);
            $('#inline_edit_message_modal').modal('show');
        }

    });
}
(function ($) {
    $('#discard_po_activity_data').on('click',function(){
        $.ajax({
            type: "POST",
            url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/drop_temp_table_data',
            data: {
                activity_id: $('#activity_id').val()
            },
            success: function (data) {
                window.location.href = base_url + 'Extra_curricular_activities/Extra_curricular_activities/index';
            }
        });
    });
    
    $('#accept_po_activity_data').on('click',function(){
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: base_url + 'Extra_curricular_activities/Extra_curricular_activities/store_temp_data',
            data: {
                activity_id: $('#activity_id').val(),
                crclm_id:$('#crclm_id').val(),
                term_id:$('#term_id').val(),
            },
            success: function (data) {
                if (data.key == 'Success') {
                    data=JSON.parse(data);
                    $('#modal_title_import').text(data.key);
                    $('#modal_body_import').html(data.message);
                    $('#message_modal_import').modal({backdrop: 'static', keyboard: false})
                            .one('click', '#close_message_modal_import', function () {
                                window.location.href = base_url + 'Extra_curricular_activities/Extra_curricular_activities/index';
                            });
                } else {
                    console.log('error msg',data);
                    data=JSON.parse(data);
                    $('#modal_title').text(data.key);
                    $('#modal_body').html(data.message);
                    $('#message_modal').modal('show');
                }
                 $('#loading').hide();

            }
        });
    });

})(jQuery);

//Code added by Mritunjay B S
//Date : 28/12/2016.
$('#close_message_modal').on('click',function(){
    var link = $(this).attr('abbr_href');
    $('#message_modal').modal('hide');
    window.location = link;
});
