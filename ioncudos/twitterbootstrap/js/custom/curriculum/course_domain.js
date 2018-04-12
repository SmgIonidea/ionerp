
/*
 * Modification History:
 * Date				Modified By				Description
 * 09-29-2015		Bhagyalaxmi        	Added dataTable
 
 //List view JS functions.
 */
var base_url = $('#get_base_url').val();

$("#hint a").tooltip();
$(document).ready(function () {
    var table_row;
    var data_val;
    $('.get_id').live('click', function (e) {
        data_val = $(this).attr('id');
        table_row = $(this).closest("tr").get(0);
    });

    /* Function is used to delete course domain by sending the course domain id to controller.
     * @param - course domain id.
     * @returns- updated course domain list view.
     */
    $('.delete_course_domain').click(function (e) {
        $('#loading').show();
        e.preventDefault();
        var post_data = {
            'crs_domain_id': data_val,
        }
        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/course_domain/course_domain_delete',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg == -1) {
                    $('#cannot_delete').modal('show');
                } else {
                    var oTable = $('#example').dataTable();
                    oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
                }
                $('#loading').hide();
            }
        });
    });
});


//Add view JS functions.

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\_\-\s\'\ / \. \&\,]+$/i.test(value);
}, "Field must contain only letters, spaces, underscore ' or dashes.");

$.validator.addMethod("noSpecialChars2", function (value, element) {
    return this.optional(element) || /^[a-zA-Z 0-9\-\ \/\&]+$/i.test(value);
}, "This field must contain only letters, numbers, spaces and dashes.");

// Form validation rules are defined & checked before form is submitted to controller.		
$("#add_form_id").validate({
    rules: {
        crs_domain_name: {
            noSpecialChars2: true,
        },
    },
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

//Function to call the uniqueness checks for course domain name to controller		
$('.add_form_submit').on('click', function (e) {
    $('#loading').show();
    e.preventDefault();
    var flag;
    flag = $('#add_form_id').valid();
    var data_val = $("#crs_domain_name").val();
    var data_val_descr = $("#crs_domain_description").val();
    var post_data = {
        'crs_domain_name': data_val,
        'crs_domain_description': data_val_descr
    }
    if (flag == true) {
        $.ajax({type: "POST",
            url: base_url + 'curriculum/course_domain/unique_course_domain',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {

                if ($.trim(msg) == 'valid')
                {
                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/course_domain/insert',
                        data: post_data,
                        datatype: "JSON",
                        success: function (msg) {
                            $.ajax({
                                type: "POST",
                                url: base_url + 'curriculum/course_domain/crclm_dm_table_generate',
                                data: post_data,
                                dataType: 'json',
                                success: [populate_table, success_modal, reset_course, $('#loading').hide()]
                            });

                        }

                    });

                } else
                {
                    $('#loading').hide();
                    $('#add_warning_dialog').modal('show');
                }
            }
        });
    } else {
        $('#loading').hide();
    }
});
function reset_course() {
    $("#add_form_id").trigger('reset');
}
$('#example').on('click', '.edit_tr', function (e) {
    $('#loading').show();
    var id = $(this).attr('id');
    var crs_domain_name = $(this).attr('id-name');
    var crs_domain_description = $(this).attr('id-descr');
    $('#crs_domain_id_edit').val(id);
    $('#crs_domain_name_edit').val(crs_domain_name);
    $('#crs_domain_description_edit').val(crs_domain_description);
    $('#edit').modal('show');
    $('#loading').hide();
});

//Edit view JS functions.

/**Calling the modal on success**/
function success_modal(msg) {
    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
/**Calling the model on successfull update**/
function success_modal_update(msg) {
    var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\_\-\s\'\ / \. \&\,]+$/i.test(value);
}, "Field must contain only letters, spaces, underscore ' or dashes.");

$.validator.addMethod("noSpecialChars2", function (value, element) {
    return this.optional(element) || /^[a-zA-Z 0-9\-\s]+$/i.test(value);
}, "This field must contain only letters, numbers, spaces and dashes.");

// Form validation rules are defined & checked before form is submitted to controller.		
$("#edit_form_id").validate({
    rules: {
        crs_domain_name_edit: {
            noSpecialChars2: true,
        },
    },
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});


//Function to call the uniqueness checks for course domain name to controller
$('.edit_form_submit').on('click', function (e) {
    $('#loading').show();
    e.preventDefault();
    var flag;
    flag = $('#edit_form_id').valid();
    var data_val = document.getElementById("crs_domain_name_edit").value;
    var data_val1 = document.getElementById("crs_domain_id_edit").value;
    var data_val2 = $('#crs_domain_description_edit').val();

    var post_data = {
        'crs_domain_name': data_val,
        'crs_domain_id': data_val1,
        'crs_domain_description': data_val2
    }
    if (flag)
    {
        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/course_domain/unique_course_domain_edit',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if ($.trim(msg) == 'valid') {
                    $('#edit').modal('hide');
                    $.ajax({
                        type: "POST",
                        url: base_url + 'curriculum/course_domain/update',
                        data: post_data,
                        dataType: 'json',
                        success: [populate_table, success_modal_update, $('#loading').hide()]
                    });

                } else
                {
                    $('#loading').hide();
                    $('#edit_warning_dialog').modal('show');
                }
            }
        });
    } else {
        $('#loading').hide();
    }
});

var post_data = {
    'crclm_id': '1'
}
$.ajax({
    type: "POST",
    url: base_url + 'curriculum/course_domain/crclm_dm_table_generate',
    data: post_data,
    dataType: 'json',
    success: populate_table
});
//generates a grid on select of curriculum from the dropdown
function populate_table(msg) {
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable({
        "aoColumns": [
            {"sTitle": "Sl No.", "mData": "sl_no", "sClass": "alignRight"},
            {"sTitle": "Course Domain Name", "mData": "delivery_mtd_name"},
            {"sTitle": "Course Domain Description", "mData": "delivery_mtd_desc"},
            {"sTitle": "Edit", "mData": "crclm_dm_edit"},
            {"sTitle": "Delete", "mData": "crclm_dm_delete"}
        ], "aaData": msg["crclm_dm_details"],
        "sPaginationType": "bootstrap"
    });
}
