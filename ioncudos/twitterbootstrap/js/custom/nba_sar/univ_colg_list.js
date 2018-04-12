//univ_colg_list.js

var base_url = $('#get_base_url').val();

if ($.cookie('stud_perm_department') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#dept option[value="' + $.cookie('stud_perm_department') + '"]').prop('selected', true);
    fetch_program();
}

//Function to fetch program dropdown list.
function fetch_program() {
    $.cookie('stud_perm_department', $('#dept option:selected').val(), {
        expires: 90, 
        path: '/'
    });
    var department_id = $('#dept').val();
    var post_data = {
        'department_id': department_id
    }
    $('#loading').show();
    if (department_id) {
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/univ_colg_list/fetch_program',
            data: post_data,
            success: function (msg) {
                $("#program").html(msg);
                $('#loading').hide();
                if ($.cookie('stud_perm_program') != null) {
                    // set the option to selected that corresponds to what the cookie is set to
                    $('#program option[value="' + $.cookie('stud_perm_program') + '"]').prop('selected', true);
                    fetch_details();
                }
            }
        });
    } else {
        $("#details").html("");
        $('#program').html('<option>Select Program</option>');
        $('#curriculum').html('<option>Select Curriculum</option>');
        $.cookie('stud_perm_program', '', {
            expires: 90, 
            path: '/'
        });
        $.cookie('stud_perm_curriculum', '', {
            expires: 90, 
            path: '/'
        });
        $('#loading').hide();
    }
}

//Function to fetch University / College view pages.
function fetch_details() {
    $.cookie('stud_perm_program', $('#program option:selected').val(), {
        expires: 90, 
        path: '/'
    });
    var program_id = $('#program').val();
    var post_data = {
        'program_id': program_id
    }
    $('#loading').show();
    if (program_id) {
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/univ_colg_list/fetch_details',
            data: post_data,
            success: function (msg) {
                $("#details").html(msg);
                univ_colg_details();
                $('#loading').hide();
            }

        });
    } else {
        $("#details").html("");
        $('#loading').hide();
    }
}

// Form validation rules are defined for add form & checked before form is submitted to controller.
$("#univ_colg_list_form").validate({
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

//Function to reset form
$('#details').on("click","#reset_details",function () {
    $("#char_span_support").text('0 of 2000');
    var validator = $('#univ_colg_list_form').validate();
    validator.resetForm();
    $("#edit_form").hide();
    $("#add_form").show();
    $("#univ_colg_heading").hide();
});

//function to save the details
$('#details').on("click","#add_form",function () {
    var flag = $('#univ_colg_list_form').valid();
    
    if (flag) {
        var pgm_id = $("#program").val();
        var crclm_id = $("#curriculum").val();
        var dept_id = $("#dept").val();
        var univ_colg_name = $('#univ_colg_name').val();
        var univ_colg_desc = $('#univ_colg_desc').val();
        $("#loading").show();
        var post_data = {
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'dept_id': dept_id,
            'univ_colg_name': univ_colg_name,
            'univ_colg_desc': univ_colg_desc
        }

        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/univ_colg_list/check_details',
            data: post_data,
            dataType: "json",
            success: function (data) {
                if (data == 0) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'nba_sar/univ_colg_list/insert_univ_colg_details',
                        data: post_data,
                        dataType: "json",
                        success: function (data) {
                            success_modal();
                            univ_colg_details();
                            $("#loading").hide();
                            $('#reset_details').trigger("click");
                        }
                    });
                } else {
                    already_exist_msg();
                    $("#loading").hide();
                }
            }
        });
    }
});

//function to display University / College details.
function univ_colg_details() {
    $("#loading").show();
    var pgm_id = $("#program").val();
    var dept_id = $("#dept").val();
    var post_data = {
        'pgm_id': pgm_id,
        'dept_id': dept_id
    }

    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/univ_colg_list/univ_colg_details_list',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            populateTable(data);
            $("#loading").hide();
        }
    });
}

//Function to populate University / College table
function populateTable(data) {
    $('#univ_colg_lists').dataTable().fnDestroy();
    $('#univ_colg_lists').dataTable(
    {
        "aoColumns": [

        {
            "sTitle": "Sl No.", 
            "mData": "sl_no"
        },

        {
            "sTitle": "University / College Name", 
            "mData": "univ_colg_name"
        },
        {
            "sTitle": "Total no. of Students Placed", 
            "mData": "no_stud_placed"
        },

        {
            "sTitle": "Upload", 
            "mData": "upload"
        },

        {
            "sTitle": "Edit", 
            "mData": "edit"
        },

        {
            "sTitle": "Delete", 
            "mData": "delete"
        }
        ], 
        "aaData": data,
        "sPaginationType": "bootstrap",
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $('td:eq(0)', nRow).css("text-align", "right");
            $('td:eq(2)', nRow).css("text-align", "right");
            $('td:eq(3)', nRow).css("text-align", "right");
            return nRow;
        }
    });
}

//load the data into fields onclick on edit symbol.
$('#details').on('click', '.edit_details', function () {
    var validator = $('#univ_colg_list_form').validate();
    validator.resetForm();
    $('html,body').animate({
        scrollTop: $(".edit_data").offset().top
    }, 'slow');
    $('#edit_univ_colg_id').val($(this).attr('data-id'));
    $('#univ_colg_name').val($(this).attr('data-univ_colg_name'));
    $('#univ_colg_desc').val($(this).attr('data-univ_colg_desc'));
    var desc_length = $('#univ_colg_desc').val().length;
    $('#char_span_support').text(desc_length + ' of 2000');
    $("#edit_form").show();
    $("#add_form").hide();
    $("#univ_colg_heading").show();
});

//Function to update University / College details
$('#details').on("click","#edit_form",function () {
    var flag = $('#univ_colg_list_form').valid();
    
    if (flag) {
        var update_univ_colg_id = $('#edit_univ_colg_id').val();
        var update_univ_colg_name = $('#univ_colg_name').val();
        var update_univ_colg_desc = $('#univ_colg_desc').val();
        var pgm_id = $("#program").val();
        $("#loading").show();

        var post_data = {
            'update_univ_colg_id': update_univ_colg_id,
            'update_univ_colg_name': update_univ_colg_name,
            'update_univ_colg_desc': update_univ_colg_desc,
            'pgm_id':pgm_id
        }

        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/univ_colg_list/check_update_data',
            data: post_data,
            datatype: "json",
            success: function (data) {
                if (data == 0) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'nba_sar/univ_colg_list/update_univ_colg_details',
                        data: post_data,
                        datatype: "json",
                        success: function (data) {
                            univ_colg_details();
                            $("#loading").hide();
                            $("#reset_details").trigger("click");
                            if (data == 1) {
                                update_modal();
                            }
                        }
                    });
                } else {
                    $("#loading").hide();
                    already_exist_msg();
                }
            }
        });
    }
});

//function to check University / College can be delete or not.
function delete_univ_colg_details(univ_colg_id) {
    $('#univ_colg_id').val(univ_colg_id);
    $("#loading").show();
    var post_data = {
        'univ_colg_id': univ_colg_id
    }
    
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/univ_colg_list/check_delete_details',
        data: post_data,
        datatype: "json",
        success: function (data) {
            if (data == 1) {
                $("#loading").hide();
                $('#cant_delete').modal('show');
            } else {
                $("#loading").hide();
                $("#delete_file").modal('show');
            }
        }
    });
}

//function to delete company details
$('#delete_selected').click(function () {
    var univ_colg_delete_id = $('#univ_colg_id').val();
    $("#loading").show();
    var post_data = {
        'univ_colg_id': univ_colg_delete_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/univ_colg_list/delete_details',
        data: post_data,
        datatype: "json",
        success: function (data) {
            univ_colg_details();
            $('#delete_file').modal('hide');
            $("#loading").hide();

            if (data == 1) {
                delete_modal();
            }
        }
    });

});

//count number of characters entered in the add description box.
$('.desc-counter').live('keyup', function () {
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

//function to show uploaded files in the modal, upload the file and save data.
$('#details').on('click', '.upload_data', function (e) {
    $('#univ_colg_id').val($(this).attr('data-id'));
    display_upload_modal();
    var univ_colg_id = $('#univ_colg_id').val();
    var post_data = {
        'univ_colg_id': univ_colg_id,
    }
    var uploader = document.getElementById('uploaded_file');
    upclick({
        element: uploader,
        action_params: post_data,
        multiple: true,
        onstart: function (filename) {
            $("#loading").show();
        },
        action: base_url + 'nba_sar/univ_colg_list/upload',
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
    var univ_colg_id = $('#univ_colg_id').val();
    var post_data = {
        'univ_colg_id': univ_colg_id
    }
    $.ajax({
        type: 'POST',
        url: base_url + 'nba_sar/univ_colg_list/fetch_files',
        data: post_data,
        success: function (data) {
            document.getElementById('upload_files').innerHTML = data;
            $('#upload_modal').modal('show');
        }
    });
}

//function to show delete modal. 
$('#upload_files').on('click', '.delete_uploaded_file', function () {
    var delete_id = $(this).attr('data-id');
    $('#upload_id').val(delete_id);
    $('#delete_upload_file').modal('show');
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

//function to delete the uploaded file.
$('#upload_file_delete').click(function (e) {
    var delete_id = $('#upload_id').val();
    $("#loading").show();
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/univ_colg_list/delete_file',
        data: {
            'upload_id': delete_id
        },
        success: function (data) {

            if (data == 1) {
                delete_modal();
            }

            upload_modal();
        }
    });
    $('#delete_upload_file').modal('hide');
    $("#loading").hide();
});

//function to list uploaded files in modal.
function upload_modal() {
    var univ_colg_id = $('#univ_colg_id').val();
    var post_data = {
        'univ_colg_id': univ_colg_id,
    }
    
    $.ajax({
        type: 'POST',
        url: base_url + 'nba_sar/univ_colg_list/fetch_files',
        data: post_data,
        success: function (data) {
            document.getElementById('upload_files').innerHTML = data;
            $('#mymodal').modal('show');
        }
    });
}

//submit the form to save data.
$('#save_desc').on('click', function (e) {
    e.preventDefault();
    $('#upload_form_data').submit();
});

//Save description and date of each file uploaded.
$('#upload_form_data').on('submit', function (e) {
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
            url: base_url + 'nba_sar/univ_colg_list/save_desc_data',
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

//function to give save message on add.
function success_modal(msg) {
    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to give warning message if data is already exits.
function already_exist_msg(msg) {
    var data_options = '{"text":"The entered University / College Name already exists.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
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

//File ends here.