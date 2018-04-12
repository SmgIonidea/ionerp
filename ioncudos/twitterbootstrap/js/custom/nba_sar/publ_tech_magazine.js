//prof_societies_chapter.js

if ($.cookie('stud_perm_department') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#dept option[value="' + $.cookie('stud_perm_department') + '"]').prop('selected', true);
    fetch_details();
}

//Function to fetch student performance view pages.
function fetch_details() {
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
            url: base_url + 'nba_sar/publ_tech_magazine/fetch_details',
            data: post_data,
            success: function (msg) {
                $("#details").html(msg);
                initialize();
                pub_mag_details();
                $('#loading').hide();
            }
        });
    } else {
        $("#details").html("");
        $('#loading').hide();
    }
}

//function to display companies details.
function pub_mag_details() {
    $("#loading").show();

    var pgm_id = $("#program").val();
    var dept_id = $("#dept").val();
    var post_data = {
        'pgm_id': pgm_id,
        'dept_id': dept_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/publ_tech_magazine/pub_mag_details',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            populateTable(data);
            $("#loading").hide();
        }
    });
}

function populateTable(data) {
    $('#companies_data').dataTable().fnDestroy();
    $('#companies_data').dataTable(
    {
        "aoColumns": [

        {
            "sTitle": "Sl No.", 
            "mData": "sl_no"
        },

        {
            "sTitle": "Technical Magazine / Newsletter", 
            "mData": "publ_name"
        },

        {
            "sTitle": "Date of Publication", 
            "mData": "year"
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
            return nRow;
        },
    });
}

//function to save the details
$('#details').on("click","#add_form",function () {
    var flag = $('#publication_form').valid();

    if (flag) {
        var prof_name = $('#publ_name').val();
        var prof_year = $('#collaboration_date').val();
        var prof_desc = $('#publ_desc').val();
        var publ_type = $('#publ_type_id').val();
        var dept_id = $("#dept").val();

        $("#loading").show();

        var post_data = {
            'prof_name': prof_name,
            'prof_year': prof_year,
            'prof_desc': prof_desc,
            'publ_type': publ_type,
            'dept_id': dept_id
        }

        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/publ_tech_magazine/check_prof_data',
            data: post_data,
            datatype: "json",
            success: function (data) {
                if (data == 1) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'nba_sar/publ_tech_magazine/insert_prof_data',
                        data: post_data,
                        datatype: "json",
                        success: function (data) {
                            pub_mag_details();
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

});

function delete_function(delete_prof_id, delete_publ_type) {
    $("#delete_prof_id").val(delete_prof_id);
    $('#edit_publ_type').val(delete_publ_type);
    $("#loading").show();
    $("#delete_company_details").modal('show');
    $("#loading").hide();
}

//function to delete company details
$('#delete_company_selected').click(function () {
    var delete_id = $("#delete_prof_id").val();
    var dept_id = $("#dept").val();
    var publ_type_id = $('#edit_publ_type').val();
    $("#loading").show();
    var post_data = {
        'prof_id': delete_id,
        'dept_id': dept_id,
        'publ_type_id': publ_type_id
    }
    
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/publ_tech_magazine/prof_details_delete',
        data: post_data,
        datatype: "json",
        success: function (data) {
            pub_mag_details();
            $('#delete_company_details').modal('hide');
            $("#loading").hide();

            if (data == 1) {
                delete_modal();
            }
        }
    });
});

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
$('#details').on('click', '.edit_company_details', function () {
    var validator = $('#publication_form').validate();
    validator.resetForm();
    $("input,select,textarea").css({
        "color": "#555555", 
        "border-color": "#cccccc"
    });
    $('html,body').animate({
        scrollTop: $(".edit_details").offset().top
    }, 'slow');

    $('#edit_publ_id').val($(this).attr('data-id'));
    $('#publ_name').val($(this).attr('data-company_name'));
    $('#publ_desc').val($(this).attr('data-company_desc'));
    $('#collaboration_date').val($(this).attr('data-company_first_visit'));
    $('#publ_type_id').val($(this).attr('data-publ_type_id'));

    var desc_length = $('#publ_desc').val().length;
    $('#char_span_support').text(desc_length + ' of 2000');
    $("#edit_form").show();
    $("#add_form").hide();
    $("#company_visit_heading").show();


});

//Function to update Technical Magazines / Newsletter details
$('#details').on('click', '#edit_form', function () {
    var flag = $('#publication_form').valid();

    if (flag) {
        var edit_publ_id = $('#edit_publ_id').val();
        var edit_publ_name = $('#publ_name').val();
        var edit_publ_year = $('#collaboration_date').val();
        var edit_publ_desc = $('#publ_desc').val();
        var publ_type_id = $('#publ_type_id').val();


        var dept_id = $("#dept").val();
        $("#loading").show();

        var post_data = {
            'edit_publ_id': edit_publ_id,
            'edit_publ_name': edit_publ_name,
            'edit_publ_year': edit_publ_year,
            'edit_publ_desc': edit_publ_desc,
            'publ_type_id': publ_type_id,
            'dept_id': dept_id,
        }

        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/publ_tech_magazine/check_update_data',
            data: post_data,
            datatype: "json",
            success: function (data) {
                if (data == 1) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'nba_sar/publ_tech_magazine/update_pub_mag_data',
                        data: post_data,
                        datatype: "json",
                        success: function (data) {
                            pub_mag_details();
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

//function to show uploaded files in the modal, upload the file and save data.
$('#details').on('click', '.upload_file', function (e) {
    $('#edit_publ_id').val($(this).attr('data-id'));
    display_upload_modal();
    var publ_id = $('#edit_publ_id').val();
    var post_data = {
        'publ_id': publ_id,
    }
    var uploader = document.getElementById('uploaded_file');
    upclick({
        element: uploader,
        action_params: post_data,
        multiple: true,
        onstart: function (filename) {
            $("#loading").show();
        },
        action: base_url + 'nba_sar/publ_tech_magazine/upload',
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
    var publ_id = $('#edit_publ_id').val();
    var post_data = {
        'publ_id': publ_id,
    }
    $.ajax({
        type: 'POST',
        url: base_url + 'nba_sar/publ_tech_magazine/fetch_files',
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
        url: base_url + 'nba_sar/publ_tech_magazine/delete_file',
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
            url: base_url + 'nba_sar/publ_tech_magazine/save_data',
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

//function to close modal
$(".modal_close").click(function () {
    var id = $(this).attr('data-id');
    $(id).modal('hide');
});

////Function to reset form
$('#details').on("click","#reset_company",function () {
    $("#char_span_support").text('0 of 2000');
    var validator = $('#publication_form').validate();
    validator.resetForm();
    $("#edit_form").hide();
    $("#add_form").show();
    $("#company_visit_heading").hide();

    $("input,select,textarea").css({
        "color": "#555555", 
        "border-color": "#cccccc"
    });
});

//Function is to initialize date picker.
function initialize(){
    $("#collaboration_date").datepicker({
        format: "dd-mm-yyyy",
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });
}

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
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });
});

//function to focus on input on click on calender icon.
$('#upload_files').on('click', '.std_date_1', function () {
    $(this).siblings('input').focus();
});

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
    var data_options = '{"text":"Publication details already exists.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}