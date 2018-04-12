
//internship_training.js

var base_url = $('#get_base_url').val();

//Function To Initialize the tinymce
function tiny_init() {
    tinymce.init({
        mode: "specific_textareas",
        editor_selector: "description",
        relative_urls: false,
        plugins: [
            //"advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste jbimages",
        ],
        paste_data_images: true,
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
    });
}

//call function to initailize tiny mce
tiny_init();

//validate form fields 
$("#training_form").validate({
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

//Function to check input field should contain only numbers with decimal point.
$.validator.addMethod("onlyNumber", function (value, element) {
    return this.optional(element) || /^[0-9\.]+$/i.test(value);
}, "Enter a valid number.");

//Function to check input field should contain only numbers with decimal point and comma.
$.validator.addMethod("commaNumber", function (value, element) {
    return this.optional(element) || /^[0-9\.\,]+$/i.test(value);
}, "Field must contain only number,decimal point or comma.");

//function to save Internship / Summer Training data
$("#form_submit").click(function () {
    var flag = $("#training_form").valid();
    if (flag) {
        $("#loading").show();
        var dept_id = $("#dept").val();
        var pgm_id = $("#program").val();
        var crclm_id = $("#curriculum").val();
        var title = $("#title").val();
        var batch_members = $("#batch_members").val();
        var guide_id = $("#guide_id").val();
        var location = $("#location").val();
        var stipend = $("#stipend").val();
        var cut_off_percent = $("#cut_off_percent").val();
        var company_id = $("#company_id").val();
        var intrshp_type = $("#intrshp_type").val();
        var from_duration = $("#from_duration").val();
        var to_duration = $("#to_duration").val();
        var status = $("#status").val();
        tinyMCE.triggerSave();
        var str = $('#description').val();
        var str1 = str.replace("<p>", " ");
        var str2 = str1.replace("</p>", " ");
        var description = str2.replace('alt=""', 'alt="image"');
        var post_data = {
            'dept_id': dept_id,
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'title': title,
            'batch_members': batch_members,
            'guide_id': guide_id,
            'location': location,
            'stipend': stipend,
            'cut_off_percent': cut_off_percent,
            'company_id': company_id,
            'intrshp_type': intrshp_type,
            'from_duration': from_duration,
            'to_duration': to_duration,
            'status': status,
            'description': description
        }
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/internship_training/insert_internship_data',
            data: post_data,
            datatype: "json",
            success: function (data) {
                list_internship_details();
                $("#loading").hide();
                if (data == 1) {
                    success_modal();
                }
                $('#reset').trigger("click");
            }
        });
    }
});

//function to display Internship / Summer Training details.
function list_internship_details() {
    $("#loading").show();
    var pgm_id = $("#program").val();
    var crclm_id = $("#curriculum").val();
    var dept_id = $("#dept").val();
    var company = $("#company").val();
    var post_data = {
        'pgm_id': pgm_id,
        'crclm_id': crclm_id,
        'dept_id': dept_id,
        'company': company
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/internship_training/list_internship_training_details',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            populateTable(data);
            $("#loading").hide();
        }
    });
}

//function to update the list of Internship / Summer Training in the view page.
function populateTable(data) {
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
            {"aoColumns": [
                    {"sTitle": "Sl No.", "mData": "sl_no"},
                    {"sTitle": "Title / Subject", "mData": "title"},
                    {"sTitle": "Batch Members", "mData": "batch_members"},
                    {"sTitle": "Guide", "mData": "guide"},
                    {"sTitle": industry_title, "mData": "company"},
                    {"sTitle": "Type", "mData": "type"},
                    {"sTitle": "Duration", "mData": "duration"},
                    {"sTitle": "Status", "mData": "status"},
                    {"sTitle": "Upload", "mData": "upload"},
                    {"sTitle": "Edit", "mData": "edit"},
                    {"sTitle": "Delete", "mData": "delete"}
                ], "aaData": data,
                "sPaginationType": "bootstrap",
                "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                    $('td:eq(0)', nRow).css("text-align", "right");
                    return nRow;
                },
            });
}

//function to load data into fields to edit details
$('#example').on('click', '.edit_internship_details', function () {
    $('#reset').trigger("click");
    $("#update").show();
    $("#form_submit").hide();
    $("#edit_internship").show();
    $("#intrshp_id").val($(this).attr('data-id'));
    $("#title").val($(this).attr('data-title'));
    $("#batch_members").val($(this).attr('data-batch_members'));
    $("#guide_id").val($(this).attr('data-guide_id'));
    $("#location").val($(this).attr('data-location'));
    $("#stipend").val($(this).attr('data-stipend'));
    $("#cut_off_percent").val($(this).attr('data-cut_off_percent'));
    $("#company_id").val($(this).attr('data-company_id'));
    $("#intrshp_type").val($(this).attr('data-intrshp_type'));
    $("#from_duration").val($(this).attr('data-from_duration'));
    $("#to_duration").val($(this).attr('data-to_duration'));
    $("#status").val($(this).attr('data-status'));
    tinyMCE.get('description').setContent($(this).attr('data-description'));
});

//function to update Internship / Summer Training details
$('#update').click(function () {
    var flag = $("#training_form").valid();
    if (flag) {
        $("#loading").show();
        var dept_id = $("#dept").val();
        var pgm_id = $("#program").val();
        var crclm_id = $("#curriculum").val();
        var intrshp_id = $("#intrshp_id").val();
        var title = $("#title").val();
        var batch_members = $("#batch_members").val();
        var guide_id = $("#guide_id").val();
        var location = $("#location").val();
        var stipend = $("#stipend").val();
        var cut_off_percent = $("#cut_off_percent").val();
        var company_id = $("#company_id").val();
        var intrshp_type = $("#intrshp_type").val();
        var from_duration = $("#from_duration").val();
        var to_duration = $("#to_duration").val();
        var status = $("#status").val();
        tinyMCE.triggerSave();
        var str = $('#description').val();
        var str1 = str.replace("<p>", " ");
        var str2 = str1.replace("</p>", " ");
        var description = str2.replace('alt=""', 'alt="image"');
        var post_data = {
            'dept_id': dept_id,
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'intrshp_id': intrshp_id,
            'title': title,
            'batch_members': batch_members,
            'guide_id': guide_id,
            'location': location,
            'stipend': stipend,
            'cut_off_percent': cut_off_percent,
            'company_id': company_id,
            'intrshp_type': intrshp_type,
            'from_duration': from_duration,
            'to_duration': to_duration,
            'status': status,
            'description': description
        }
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/internship_training/update_internship_data',
            data: post_data,
            datatype: "json",
            success: function (data) {
                list_internship_details();
                $("#loading").hide();
                if (data == 1) {
                    update_modal();
                }
                $('#reset').trigger("click");
            }
        });
    }
});

//Function to reset number of characters entered in the Add description box
$('#reset').click(function () {
    $("#update").hide();
    $("#form_submit").show();
    $("#edit_internship").hide();
    var validator = $('#training_form').validate();
    validator.resetForm();
    $("input,select,textarea").css({"color": "#555555", "border-color": "#cccccc"});
});

//function to store delete id.
function storeId(delete_id) {
    $("#delete_id").val(delete_id);
}

//function to delete Internship / Summer Training details
$('#delete_selected').click(function () {
    var delete_id = $("#delete_id").val();
    $("#loading").show();
    var post_data = {
        'intrshp_id': delete_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/internship_training/internship_training_delete',
        data: post_data,
        datatype: "json",
        success: function (data) {
            list_internship_details();
            $('#delete_details').modal('hide');
            $("#loading").hide();
            if (data == 1) {
                delete_modal();
            }
        }
    });
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

//function to show uploaded files in the modal, upload the file and save data.
$('#example').on('click', '.upload_file', function (e) {

    $('#internship_id').val($(this).attr('data-id'));
    display_upload_modal();
    var internship_id = $('#internship_id').val();
    var post_data = {
        'intrshp_id': internship_id,
    }
    var uploader = document.getElementById('uploaded_file');
    upclick({
        element: uploader,
        action_params: post_data,
        multiple: true,
        onstart: function (filename) {
            $("#loading").show();
        },
        action: base_url + 'nba_sar/internship_training/upload',
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
    var internship_id = $('#internship_id').val();
    var post_data = {
        'intershp_id': internship_id,
    }
    $.ajax({type: 'POST',
        url: base_url + 'nba_sar/internship_training/fetch_files',
        data: post_data,
        success: function (data) {
            document.getElementById('upload_files').innerHTML = data;
            $('#upload_modal').modal('show');
        }
    });
}

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
            url: base_url + 'nba_sar/internship_training/save_data',
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
        url: base_url + 'nba_sar/internship_training/delete_file',
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

//function for add form calendar.
$("#from_duration").datepicker({
    format: "dd-mm-yyyy",
}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

//function to focus on add form calender function.
$('#btn').click(function () {
    $(document).ready(function () {
        $("#from_duration").datepicker().focus();
    });
});

//function for add form calendar.
$("#to_duration").datepicker({
    format: "dd-mm-yyyy",
}).on('changeDate', function (ev) {
    $(this).blur();
    $(this).datepicker('hide');
});

//function to focus on add form calender function.
$('#btn1').click(function () {
    $(document).ready(function () {
        $("#to_duration").datepicker().focus();
    });
});
//File ends here. 