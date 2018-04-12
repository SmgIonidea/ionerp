
//companies_list.js

var base_url = $('#get_base_url').val();

//Function check whether cookie is set
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
            url: base_url + 'nba_sar/companies_list/fetch_program',
            data: post_data,
            success: function (msg) {
                $("#program").html(msg);
                $('#loading').hide();
                if ($.cookie('stud_perm_program') != null) {
                    // set the option to selected that corresponds to what the cookie is set to
                    $('#program option[value="' + $.cookie('stud_perm_program') + '"]').prop('selected', true);
                    //fetch_curriculum();
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
//Function to fetch student performance view pages.
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
            url: base_url + 'nba_sar/companies_list/fetch_details',
            data: post_data,
            success: function (msg) {
                $("#details").html(msg);
                initialize();
                list_companies_details();
                $('#loading').hide();
            }

        });
    } else {
        $("#details").html("");
        $('#loading').hide();
    }
}

//Function is to initialize multiselect and datepicker.
function initialize(){
    //function for add form calendar.
    $("#collaboration_date").datepicker({
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
    $('.sector_type_id').multiselect({
        maxHeight: 200,
        buttonWidth: 220,
        numberDisplayed: 1,
        nSelectedText: 'selected',
        nonSelectedText: "Select Sector"
    });
}
//Function to allow only numeric digits in the field
$("#details").on('keypress blur',".allownumericwithoutdecimal", function (e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        $("#errmsg").html("Digits Only").show().fadeOut("slow");
        $(this).css('border-color', 'red');
        $(this).append('<span>Invalid Value</span>');
        $(this).addClass('num_valid');
        $(this).attr("placeholder", "Only Digits.");
        return false;
    } else {
        $(this).removeClass('num_valid');
        $(this).attr('placeholder', 'Enter Data');
        $(this).css('border-color', '#CCCCCC');
    }
});

// Form validation rules are defined for add form & checked before form is submitted to controller.
$("#company_form").validate({
    rules: {
        sector_type_id: {
            required: true
        }
    },
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

//function to save the company details on click of add_form_submit button.
$('#details').on("click","#add_form_submit",function () {
    var flag = $('#company_form').valid();

    if (flag) {
        var company_name = $('#company_name').val();
        var sector_id = $('#sector_type_id').val();
        var company_first_visit = $('#collaboration_date').val();
        var company_desc = $('#company_description').val();
        var pgm_id = $("#program").val();
        var dept_id = $("#dept").val();
        var category_id = $("#company_type_id").val();
        var contact_name = $("#contact_name").val();
        var contact_no = $("#contact_number").val();
        var mou_data = $("#mou_flag").val();

        if ($('#mou_flag').is(":checked")) {
            mou_data = '1';
        } else {
            mou_data = '0';
        }

        var email = $("#email").val();
        var other_type = $("#other_type").val();
        $("#loading").show();
        var post_data = {
            'company_name': company_name,
            'sector_id': sector_id,
            'company_first_visit': company_first_visit,
            'company_desc': company_desc,
            'pgm_id': pgm_id,
            'dept_id': dept_id,
            'category_id': category_id,
            'contact_name': contact_name,
            'contact_no': contact_no,
            'email': email,
            'other_type': other_type,
            'mou_data': mou_data
        }

        var options = $('#sector_type_id > option:selected');
        if (options.length == 0) {
            select_modal();
            $("#loading").hide();
        } else {
            $.ajax({
                type: "POST",
                url: base_url + 'nba_sar/companies_list/check_insert_data',
                data: post_data,
                datatype: "json",
                success: function (data) {
                    if (data == 1) {
                        $.ajax({
                            type: "POST",
                            url: base_url + 'nba_sar/companies_list/insert_company_data',
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

$('.other_type').hide();
$('#details').on('change','#company_type_id' ,function () {
    if ($('#company_type_id').val() == 140) {
        $('#other_type').attr('required', true);
        $('.other_type').show();
    } else {
        $('.other_type').hide();
    }
});

//load the data into fields onclick on edit symbol.
$('#details').on('click', '.edit_company_details', function () {
    var validator = $('#company_form').validate();
    validator.resetForm();
    $("input,select,textarea").css({
        "color": "#555555", 
        "border-color": "#cccccc"
    });
    $('html,body').animate({
        scrollTop: $(".edit_details").offset().top
    }, 'slow');
    $('#edit_company_id').val($(this).attr('data-id'));
    var company_id = $(this).attr('data-id');
    $('#company_name').val($(this).attr('data-company_name'));
    $('#company_description').val($(this).attr('data-company_desc'));
    $('#company_type_id').val($(this).attr('data-category_id'));
    $('#collaboration_date').val($(this).attr('data-company_first_visit'));
    $('#contact_name').val($(this).attr('data-contact_name'));
    $('#contact_number').val($(this).attr('data-contact_number'));
    $('#email').val($(this).attr('data-email'));
    $('#mou_flag').val($(this).attr('data-flag'));

    if ($(this).attr('data-flag') != 0) {
        $('#mou_flag').attr('checked', 'checked');
    } else {
        $('#mou_flag').attr('checked', false);
    }

    $('#sector_type_id').find('option:selected').prop('selected', false);
    $('#sector_type_id').multiselect('rebuild');

    if ($(this).attr('data-category_id') == 140) {
        $('.other_type').show();
        $('#other_type').attr('required', true);
        $('#other_type').val($(this).attr('data-other_type'));
    } else {
        $('.other_type').hide();
    }

    if ($('#company_type_id').val() == 140) {
        $('#other_type').attr('required', true);
        $('.other_type').show();
    } else {
        $('.other_type').hide();
    }

    var post_data = {
        'company_id': company_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/companies_list/fetch_sector_id',
        data: post_data,
        dataType: "json",
        success: function (data) {
            var size = data.length;
            for (var count = 0; count < size; count++) {
                $("#sector_type_id option[value='" + data[count]['sector_id'] + "']").attr("selected", "selected");
                $('#sector_type_id').multiselect('rebuild');
            }
        }
    });

    var desc_length = $('#company_description').val().length;
    $('#char_span_support').text(desc_length + ' of 2000');
    $("#edit_form_submit").show();
    $("#add_form_submit").hide();
    $("#company_visit_heading").show();
});

//update the company details.
$('#details').on('click', '#edit_form_submit',function () {
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
        var edit_contact_name = $("#contact_name").val();
        var edit_contact_no = $("#contact_number").val();
        var edit_email = $("#email").val();


        if ($('#mou_flag').is(':checked')) {
            var flag = $('#mou_flag').val();
            flag = '1';
        } else {
            var flag = $('#mou_flag').val();
            flag = '0';
        }
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
            'company_type_id': company_type_id,
            'edit_contact_name': edit_contact_name,
            'edit_contact_no': edit_contact_no,
            'edit_email': edit_email,
            'flag': flag
        }

        var options = $('#sector_type_id > option:selected');
        if (options.length == 0) {
            select_modal();
            $("#loading").hide();
        } else {
            $.ajax({
                type: "POST",
                url: base_url + 'nba_sar/companies_list/check_update_data',
                data: post_data,
                datatype: "json",
                success: function (data) {

                    if (data == 1) {
                        $.ajax({
                            type: "POST",
                            url: base_url + 'nba_sar/companies_list/update_company_data',
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
        url: base_url + 'nba_sar/companies_list/company_details_delete_check',
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
        url: base_url + 'nba_sar/companies_list/company_details_delete',
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
    var dept_id = $("#dept").val();
    var post_data = {
        'pgm_id': pgm_id,
        'dept_id': dept_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/companies_list/list_companies_details',
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
    {
        "aoColumns": [

        {
            "sTitle": "Sl No.", 
            "mData": "sl_no"
        },

        {
            "sTitle": industry_title, 
            "mData": "company_name"
        },

        {
            "sTitle": "Category", 
            "mData": "category_id"
        },

        {
            "sTitle": "Sector", 
            "mData": "sector_id"
        },

        {
            "sTitle": "First Visit", 
            "mData": "company_first_visit"
        },

        {
            "sTitle": "No. of times visited", 
            "mData": "no_time_visited"
        },

        {
            "sTitle": "Total no. of students placed", 
            "mData": "student_placed"
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
            $('td:eq(5)', nRow).css("text-align", "right");
            $('td:eq(6)', nRow).css("text-align", "right");
            $('td:eq(7)', nRow).css("text-align", "right");
            return nRow;
        }
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

//function to give warning message if Sector Type is not selected.
function select_modal(msg) {
    var data_options = '{"text":"Select the Sector Type.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//Function to reset number of characters entered in the Add description box
$('#details').on('click', '#reset_company',function () {
    $("#char_span_support").text('0 of 2000');
    var validator = $('#company_form').validate();
    validator.resetForm();
    $("#edit_form_submit").hide();
    $("#add_form_submit").show();
    $("#company_visit_heading").hide();
    $('.sector_type_id').find('option:selected').prop('selected', false);
    $('.sector_type_id').multiselect('rebuild');
    $('#mou_flag').attr('checked', false);

    $('.other_type').hide();
    $("input,select,textarea").css({
        "color": "#555555", 
        "border-color": "#cccccc"
    });
});

//function to show uploaded files in the modal, upload the file and save data.
$('#details').on('click', '.upload_file', function (e) {
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
        action: base_url + 'nba_sar/companies_list/upload',
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
    $.ajax({
        type: 'POST',
        url: base_url + 'nba_sar/companies_list/fetch_files',
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
        url: base_url + 'nba_sar/companies_list/delete_file',
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
            url: base_url + 'nba_sar/companies_list/save_data',
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

//Function is only to allow digits in the field
$.validator.addMethod("onlyDigit", function (value, element) {
    return this.optional(element) || /^[0-9\.\_]+$/i.test(value);
}, "This field must contain only Numbers.");

//Function is only to allow 10 digits in the field.
$.validator.addMethod("count", function (value, element) {
    return this.optional(element) || /^[0-9\.\_]+$/i.test(value);
}, "This field must contain only 10 Digits.");

//File ends here.
