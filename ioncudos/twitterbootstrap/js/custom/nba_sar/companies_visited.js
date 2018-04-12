
//companies_visited.js

var base_url = $('#get_base_url').val();

// Form validation rules are defined for add form & checked before form is submitted to controller.
$("#company_form").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).addClass('success');
    }
});

//function for add form calendar.
$("#collaboration_date").datepicker({
    format: "dd-mm-yyyy",
}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

//function for edit form calendar.
$("#edit_collaboration_date").datepicker({
    format: "dd-mm-yyyy",
}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

//function to focus on add form calender function.
$('#btn').click(function () {
    $(document).ready(function () {
        $("#collaboration_date").datepicker().focus();
    });
});

//focus on the edit form calender function.
$('#btn1').click(function () {
    $(document).ready(function () {
        $("#edit_collaboration_date").datepicker().focus();
    });
});

//function to save the company details on click of add_form_submit button.
$('#add_form_submit').click(function () {
    var flag = $('#company_form').valid();

    if (flag) {
        var company_name = $('#company_name').val();
        var sector_type_id = $('#sector_type_id').val();
        var collaboration_date = $('#collaboration_date').val();
        var company_description = $('#company_description').val();
        var pgm_id = $("#program").val();
        var crclm_id = $("#curriculum").val();
        var dept_id = $("#dept").val();
        var company_type_id = $("#company_type_id").val();
        $("#loading").show();
        var post_data = {
            'company_name': company_name,
            'sector_type_id': sector_type_id,
            'collaboration_date': collaboration_date,
            'company_description': company_description,
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'dept_id': dept_id,
            'company_type_id': company_type_id
        }
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/companies_visited/check_insert_data',
            data: post_data,
            datatype: "json",
            success: function (data) {

                if (data == 1) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'nba_sar/companies_visited/insert_company_data',
                        data: post_data,
                        datatype: "json",
                        success: function (data) {
                            list_companies_details();
                            $("#loading").hide();

                            if (data == 1) {
                                success_modal();
                            }
                            $('#reset_company').trigger("click");

                        }
                    });
                } else {
                    $("#loading").hide();
                    warning_modal();
                }
            }
        });
    }
})

//count number of characters entered in the add description box.
$('.char-counter').live('keyup', function () {
    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support';

    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).css('color', '');
        $('#' + spanId).text(len + ' of ' + max + '.');
    }
});

//load the data into edit modal onclick on edit symbol.
$('#companies_data').on('click', '.edit_company_details', function () {
    var validator = $('#company_form').validate();
    validator.resetForm();
    $("input,select,textarea").css({"color": "#555555", "border-color": "#cccccc"});
    $('#edit_company_id').val($(this).attr('data-id'));
    $('#company_name').val($(this).attr('data-company_name'));
    $('#company_description').val($(this).attr('data-description'));
    $('#sector_type_id').val($(this).attr('data-sector_type_id'));
    $('#company_type_id').val($(this).attr('data-company_type_id'));
    $('#collaboration_date').val($(this).attr('data-collaboration_date'));
    var desc_length = $('#company_description').val().length;
    $('#char_span_support').text(desc_length + ' of 2000');
    $("#edit_form_submit").show();
    $("#add_form_submit").hide();
    $("#company_visit_heading").show();
});

//update the company details.
$('#edit_form_submit').click(function () {
    var flag = $('#company_form').valid();

    if (flag) {
        var edit_company_id = $('#edit_company_id').val();
        var edit_company_name = $('#company_name').val();
        var edit_sector_type_id = $('#sector_type_id').val();
        var edit_collaboration_date = $('#collaboration_date').val();
        var edit_company_description = $('#company_description').val();
        var pgm_id = $("#program").val();
        var crclm_id = $("#curriculum").val();
        var dept_id = $("#dept").val();
        var company_type_id = $("#company_type_id").val();
        $("#loading").show();
        var post_data = {
            'edit_company_id': edit_company_id,
            'edit_company_name': edit_company_name,
            'edit_sector_type_id': edit_sector_type_id,
            'edit_collaboration_date': edit_collaboration_date,
            'edit_company_description': edit_company_description,
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'dept_id': dept_id,
            'company_type_id': company_type_id
        }
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/companies_visited/check_update_data',
            data: post_data,
            datatype: "json",
            success: function (data) {
                if (data == 1) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'nba_sar/companies_visited/update_company_data',
                        data: post_data,
                        datatype: "json",
                        success: function (data) {
                            list_companies_details();
                            $("#edit_modal").modal('hide');
                            $("#loading").hide();
                            $("#reset_company").trigger("click");
                            if (data == 1) {
                                update_modal();
                            }
                        }
                    });
                } else {
                    $("#loading").hide();
                    warning_modal();
                }
            }
        });
    }
})

//function to check company can be delete or not.
function delete_check(delete_company_id) {
    $("#delete_company_id").val(delete_company_id);
    $("#loading").show();
    var post_data = {
        'company_id': delete_company_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/companies_visited/company_details_delete_check',
        data: post_data,
        datatype: "json",
        success: function (data) {

            if (data == 1) {
                $("#loading").hide();
                $('#cant_delete').modal('show');
            } else {
                $("#loading").hide();
                $("#delete_company_details").modal('show');
            }
        }
    });
}

//function to delete company details
$('#delete_company_selected').click(function () {
    var delete_id = $("#delete_company_id").val();
    $("#loading").show();
    var post_data = {
        'company_id': delete_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/companies_visited/company_details_delete',
        data: post_data,
        datatype: "json",
        success: function (data) {
            list_companies_details();
            $('#delete_company_details').modal('hide');
            $("#loading").hide();

            if (data == 1) {
                delete_modal();
            }
        }
    });
});

//function to display companies details.
function list_companies_details() {
    $("#loading").show();
    var pgm_id = $("#program").val();
    var crclm_id = $("#curriculum").val();
    var dept_id = $("#dept").val();
    var post_data = {
        'pgm_id': pgm_id,
        'crclm_id': crclm_id,
        'dept_id': dept_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/companies_visited/list_companies_details',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            populateTable(data);
            $("#loading").hide();
        }
    });
}

//function to update the list of companies display in the view page.
function populateTable(data) {
    $('#companies_data').dataTable().fnDestroy();
    $('#companies_data').dataTable(
            {"aoColumns": [
                    {"sTitle": "Sl No.", "mData": "sl_no"},
                    {"sTitle": "Company / Industry", "mData": "company_name"},
                    {"sTitle": "Company / Industry Type", "mData": "company_type"},
                    {"sTitle": "Sector Type", "mData": "mt_details_name"},
                    {"sTitle": "Description", "mData": "description"},
                    {"sTitle": "Collaboration Date", "mData": "collaboration_date"},
                    {"sTitle": "Total no. of students placed", "mData": "tot_stud_intake"},
                    {"sTitle": "No. of times visited", "mData": "num_time_visited"},
                    {"sTitle": "Upload", "mData": "upload"},
                    {"sTitle": "Edit", "mData": "edit"},
                    {"sTitle": "Delete", "mData": "delete"}
                ], "aaData": data,
                "sPaginationType": "bootstrap",
                "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                    $('td:eq(0)', nRow).css("text-align", "right");
                    $('td:eq(5)', nRow).css("text-align", "right");
                    $('td:eq(6)', nRow).css("text-align", "right");
                    $('td:eq(7)', nRow).css("text-align", "right");
                    return nRow;
                },
            });
}

//function to give save message on add.
function success_modal(msg) {
    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to give update message on edit.
function update_modal(msg) {
    var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to give delete message on delete.
function delete_modal(msg) {
    var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to give warning message if data is already exits.
function warning_modal(msg) {
    var data_options = '{"text":"Company / Industry details already exists.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//Function to reset number of characters entered in the Add description box
$('#reset_company').click(function () {
    $("#char_span_support").text('0 of 2000');
    var validator = $('#company_form').validate();
    validator.resetForm();
    $("#edit_form_submit").hide();
    $("#add_form_submit").show();
    $("#company_visit_heading").hide();
    $("input,select,textarea").css({"color": "#555555", "border-color": "#cccccc"});
});

//function to show uploaded files in the modal, upload the file and save data.
$('#companies_data').on('click', '.upload_file', function (e) {

    $('#company_id').val($(this).attr('data-id'));
    display_upload_modal();
    var company_id = $('#company_id').val();
    var post_data = {
        'company_id': company_id,
    }
    var uploader = document.getElementById('uploaded_file');
    upclick({
        element: uploader,
        action_params: post_data,
        multiple: true,
        onstart: function (filename) {
            $("#loading").show();
        },
        action: base_url + 'nba_sar/companies_visited/upload',
        oncomplete: function (response_data) {

            if (response_data == "file_name_size_exceeded") {
                $('#file_name_size_exc').modal('show');
            } else if (response_data == "file_size_exceed") {
                $('#larger').modal('show');
            }

            display_upload_modal();
            $("#loading").hide();
        }
    });
});

//function to list uploaded files in modal.
function display_upload_modal() {
    var company_id = $('#company_id').val();
    var post_data = {
        'company_id': company_id,
    }
    $.ajax({type: 'POST',
        url: base_url + 'nba_sar/companies_visited/fetch_files',
        data: post_data,
        success: function (data) {
            document.getElementById('upload_files').innerHTML = data;
            $('#upload_modal').modal('show');
        }
    });
}

//function to show delete modal. 
$('#upload_files').on('click', '.delete_file', function (e) {
    var delete_id = $(this).attr('data-id');
    $('#upload_id').val(delete_id);
    $('#delete_upload_file').modal('show');
});

//function to delete the uploaded file.
$('#delete_file').click(function (e) {
    var delete_id = $('#upload_id').val();
    $("#loading").show();
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/companies_visited/delete_file',
        data: {
            'upload_id': delete_id
        },
        success: function (data) {

            if (data == 1) {
                delete_modal();
            }

            display_upload_modal();
        }
    });
    $('#delete_upload_file').modal('hide');
    $("#loading").hide();
});

//function for upload file calender
$('body').on('focus', '.std_date', function () {
    $("#actual_date").datepicker({
        format: "dd-mm-yyyy",
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });
    $(this).datepicker({
        format: "dd-mm-yyyy",
        //endDate:'-1d'
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });
});

//function to focus on input on click on calender icon.
$('#upload_files').on('click', '.std_date_1', function () {
    $(this).siblings('input').focus();
});

//submit the form to save data.
$('#save_upload_desc').live('click', function (e) {
    e.preventDefault();
    $('#myform').submit();
});

//Save description and date of each file uploaded.
$('#upload_form').on('submit', function (e) {
    e.preventDefault();
    var form_data = new FormData(this);
    var form_val = new Array();
    $("#loading").show();

    $('.save_form_data').each(function () {
        //values fetched will be inserted into the array
        form_val.push($(this).val());
    });

    //check whether any file exists or not
    if (form_val.length > 0) {
        //if file exists
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/companies_visited/save_data',
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (msg) {

                if (msg == 1) {
                    //display success message on save
                    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                } else {
                    //display error message - if description and date could not be saved
                    var data_options = '{"text":"Your data could not be saved.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
                    var options = $.parseJSON(data_options);
                    noty(options);
                }
            }
        });
    } else {
        //display error message if file does not exist and user tries to click save button
        var data_options = '{"text":"File needs to be uploaded.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }

    $("#loading").hide();
});

//function to list Total and number of Student intake of company visited
$('#companies_data').on('click', '.view_details', function (e) {
    var company_id = $(this).attr('data-id');
    $("#loading").show();
    var post_data = {
        'company_id': company_id,
    }
    $.ajax({type: 'POST',
        url: base_url + 'nba_sar/companies_visited/stud_intake_details',
        data: post_data,
        success: function (data) {
            document.getElementById('placement_details').innerHTML = data;
            $("#loading").hide();
            $("#view_companies_student_intake").modal('show');
        }
    });
});

//function to close modal
$(".modal_close").click(function () {
    var id = $(this).attr('data-id');
    $(id).modal('hide');
});

//file ends here.
