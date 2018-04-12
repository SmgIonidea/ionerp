var base_url = $('#get_base_url').val();
$(document).ready(function () {

    if ($.cookie('remember_prog_type') != null) {
        // set the option to selected that corresponds to what the cookie is set to
        $('#program_type option[value="' + $.cookie('remember_prog_type') + '"]').prop('selected', true);
        prog_type();
    }

    function prog_type() {
        $.cookie('remember_prog_type', $('#program_type option:selected').val(), {expires: 90, path: '/'});
        var pro_type_id = $('#program_type').val();
        var post_data = {'pro_type_id': pro_type_id}

        $.ajax({type: "POST",
            url: base_url + 'configuration/graduate_attributes/fetch_data',
            data: post_data,
            dataType: 'json',
            success: [populate_table_view]
        });
    }

    $('#program_type').on('change', function () {
        $.cookie('remember_prog_type', $('#program_type option:selected').val(), {expires: 90, path: '/'});

        var pro_type_id = $('#program_type').val();
        var post_data = {'pro_type_id': pro_type_id}

        $.ajax({type: "POST",
            url: base_url + 'configuration/graduate_attributes/fetch_data',
            data: post_data,
            dataType: 'json',
            success: [populate_table_view]
        });
    });

    $('#example2').on('click', '.delete_pro', function (e) {
        var id = $(this).attr('id');
        var ga_id = $(this).attr('data-id');
        if (id == 0) {
            $('#myModaldelete').data('id', ga_id).modal('show');
        } else {
            $('#cant_delete').data('id', id).modal('show');
        }
    });


    $('#btnYes').click(function (e) {
        id = $('#myModaldelete').data('id');
        e.preventDefault();
        var base_url = $('#get_base_url').val();
        var post_data = {
            'ga_id': id,
        }
        $.ajax({type: "POST",
            url: base_url + 'configuration/graduate_attributes/ga_delete',
            data: post_data,
            datatype: "JSON",
            success: // populate_table_view
                    function (data) {
                        var pro_type_id = $('#program_type').val();
                        var post_data = {'pro_type_id': pro_type_id}

                        $.ajax({type: "POST",
                            url: base_url + 'configuration/graduate_attributes/fetch_data',
                            data: post_data,
                            dataType: 'json',
                            success: [populate_table_view, success_modal_delete]
                        });
                    }
        });
    });
});

/*Function to populate tlo modal*/
function populate_table_view(msg) {
    $('#example2').dataTable().fnDestroy();
    $('#example2').dataTable(
            {
                "aaSorting": [],
                "aoColumns": [
                    {"sTitle": "Sl No.", "mData": "ga_reference", "sClass": "alignRight"},
                    {"sTitle": "Graduate Attribute", "mData": "ga_statement"},
                    {"sTitle": "Graduate Attribute Statement", "mData": "ga_description"},
                    {"sTitle": "Edit", "mData": "edit"},
                    {"sTitle": "Delete", "mData": "Delete"}
                ], "aaData": msg,
                //'sDom': 't',
                //'sDom': '"top"i',
                "sPaginationType": "bootstrap"});
}

/**Calling the modal on success**/
function success_modal(msg) {
    var data_options = '{"text":"Your data has been saved successfully","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
/**Calling the model on successfull update**/
function success_modal_update(msg) {
    var data_options = '{"text":"Your data has been updated successfully","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
/**Calling the model on successfull update**/
function success_modal_delete(msg) {
    var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}