
//Program Outcomes

var base_url = $('#get_base_url').val();
var cloneCntr = 2;
var po_counter = new Array();
po_counter.push(1);
var crclm_id;

//static program outcomes
//Function to fetch curriculum details for static page
function static_select_curriculum() {
    var curriculum_id = document.getElementById('curriculum_list').value;

    var post_data = {
        'crclm_id': curriculum_id
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/po/static_select_po_curriculum',
        data: post_data,
        dataType: 'json',
        success: static_populate_table
    });
}



//Function to populate program outcome table for static page
function static_populate_table(msg) {
    $('#example').dataTable().fnDestroy();

    $('#example').dataTable(
            {
                "aaSorting": [[2, "asc"]],
                "aoColumns": [
                    {"sTitle": "Program Outcome (PO) Statement", "mData": "po_statement"},
                    {"sTitle": "Program Outcome (PO) Type", "mData": "po_type"},
                ], "aaData": msg["po_list"]});
}

//list program outcomes
//Function to fetch help content related to program outcomes
$('.show_help').on('click', function () {
    $.ajax({
        url: base_url + 'curriculum/po/po_help',
        datatype: "JSON",
        success: function (msg) {
            document.getElementById('help_content').innerHTML = msg;
        }
    });
});

if ($.cookie('remember_curriculum') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#curriculum_list option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
    select_curriculum();
}

//Function to fetch curriculum details
function select_curriculum() {
    $.cookie('remember_curriculum', $('#curriculum_list option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = $('#curriculum_list').val();

    var post_data = {
        'crclm_id': curriculum_id
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/po/select_po_curriculum',
        data: post_data,
        dataType: 'json',
        success: populate_table
    });
}

//Function to populate the table
function populate_table(msg) {
    $('#po_current_state').html(student_outcomes + ' Current Status : ' + msg["po_state_data"]["state_name"]);
    //$.cookie('remember_curriculum', $('#curriculum_list option:selected').val(), { expires: 90, path: '/'});

    var m = 'd';
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
            {
                "aaSorting": [],
                "aoColumns": [
                    {"sTitle": so + " Reference", "mData": "sl_no"},
                    {"sTitle": student_outcome + " Statements", "mData": "po_statement"},
                    {"sTitle": so + " Type", "mData": "po_type"},
                    {"sTitle": "BoS Approval Details ", "mData": "po_cmt"},
                    {"sTitle": "Edit", "mData": "po_id"},
                    {"sTitle": "Delete", "mData": "po_id1"}
                ], "aaData": msg["po_list"],
                "sPaginationType": "bootstrap"}
    );

    if ($('#curriculum_list').val() != 0) {

        if (msg["po_state_data"]["state"] == 7) {
            $('#add_po_top').attr('disabled', false);
            $('#add_po_bottom').attr('disabled', false);
            $('#publish').attr('disabled', true);

        } else if (msg["po_state_data"]["state"] == 5) {
            $('#add_po_top').attr('disabled', true);
            $('#add_po_bottom').attr('disabled', true);
            $('#publish').attr('disabled', true);
        } else if (msg["po_state_data"]["state"] == 0) {
            $('#publish').attr('disabled', true);
        } else {
            $('#publish').attr('disabled', false);
        }
    } else {
        $('#publish').attr('disabled', true);
    }
}

$("#add_po_bottom").on('click', function () {
    var curriculum_id = document.getElementById('curriculum_list').value;
    crclm_id = curriculum_id;
    if (curriculum_id != '') {
        window.location = base_url + 'curriculum/po/add_po/' + curriculum_id;
    } else {
        //modal to display warning if curriculum dropdown is not selected
        $('#select_crclm_modal').modal('show');
    }
});

$("#add_po_top").on('click', function () {
    var curriculum_id = document.getElementById('curriculum_list').value;
    crclm_id = curriculum_id;

    if (curriculum_id != '') {
        window.location = base_url + 'curriculum/po/add_po/' + curriculum_id;
    } else {
        //modal to display warning if curriculum dropdown is not selected
        $('#select_crclm_modal').modal('show');
    }
});



//Function contains instructions for program outcomes list, add, edit and approval pages
$(document).ready(function () {
    var table_row;
    var po_id;

    $('.get_id').live('click', function (e) {
        po_id = $(this).attr('id');
        table_row = $(this).closest("tr").get(0);
    });

    //Function to invoke modal on publish
    $('#publish').live('click', function () {
        var curriculum_id = document.getElementById('curriculum_list').value;
        $('#loading').show();
        if (curriculum_id) {
            var post_data = {
                'curriculum_id': curriculum_id
            }

            $.ajax({type: "POST",
                url: base_url + 'curriculum/po/bos_user_name',
                data: post_data,
                dataType: "JSON",
                success: function (msg) {
                    $('#bos_user').html(msg.bos_user_name);
                    $('#crclm_name_po').html(msg.crclm_name);
                    $('#loading').hide();
                    $('#myModal_confirmation').modal('show');
                }
            });
        } else {
            $('#loading').hide();
        }
    });

    //Function to delete program outcomes
    $('.delete_po').click(function (e) {
        e.preventDefault();
        $('#loading').show();
        var post_data = {
            'po_id': po_id,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/po/po_delete',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                var oTable = $('#example').dataTable();
                oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
                $('#loading').hide();
            }
        });
    });

    //small comment box opens for user to write comments
    $('.comment').live('click', function () {
        $('a[rel=popover]').not(this).popover('destroy');
        $('a[rel=popover]').popover({
            html: 'true',
            trigger: 'manual',
            placement: 'top'
        })
        $(this).popover('show');
        $('.close_btn').live('click', function () {
            $('a[rel=popover]').not(this).popover('destroy');
        });

    });

    //To invoke modal for publishing program outcomes
    $('.bos_publish_data').live('click', function () {
        var curriculum_id = document.getElementById('crclm_id_rework').value;

        if (curriculum_id) {
            var post_data = {
                'curriculum_id': curriculum_id,
            }

            $.ajax({type: "POST",
                url: base_url + 'curriculum/po/chairman_user_name',
                data: post_data,
                dataType: "JSON",
                success: function (msg) {
                    $('#chairman_user_accept').html(msg.chairman_user_name);
                    $('#crclm_name_accept').html(msg.crclm_name);
                    $('#myModal_publish').modal('show');
                }
            });
        }
    });

    $('.comment_submit').live('click', function () {
        $('a[rel=popover]').not(this).popover('hide');

        var bos_po_id = document.getElementById('po_id').value;
        var curriculum_id = document.getElementById('crclm_id').value;
        var po_comment = document.getElementById('po_cmt').value;

        var post_data = {
            'po_id': bos_po_id,
            'crclm_id': curriculum_id,
            'po_cmt': po_comment,
        }
        if (po_comment != '') {
            $.ajax({type: "POST",
                url: base_url + 'curriculum/po/po_comment_insert',
                data: post_data,
                success: function (msg) {
                }
            });
        }
    });
});

//Function to publish program outcomes
function po_creator_publish() {
    $('#loading').show();
    var curriculum_id = document.getElementById('curriculum_list').value;

    var post_data = {
        'crclm_id': curriculum_id,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/po/po_creator_publish_details',
        data: post_data,
        success: function (msg) {
            $('#loading').hide();
            window.location.reload(true);
        }
    });
}

//Add program outcomes
//Function to keep count of textarea that has been added or deleted
function fixIds(elem, cntr) {
    $(elem).find("[id]").add(elem).each(function () {
        this.id = this.id.replace(/\d+$/, "") + cntr;
        this.name = this.id;

    });
}

//Function to insert new textarea for adding program outcomes

$("#add_field").click(function () {
    //var table = $("#add_me").clone(true, true);
    var count_vlaue = $('#po_type_counter').val();
    var curr_id = $('#crclm_id').val();
    $('#loading').show();
    po_count_val = {
        'po_count': count_vlaue,
        'crclm_id': curr_id
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/po/po_types',
        data: po_count_val,
        async: false,
        success: function (po_type) {
            ++count_vlaue;
            $('#po_type_counter').val(count_vlaue);
            $("#add_before").append(po_type);
            $('#po_statement_' + count_vlaue).val('');
            $('#po_statement_' + count_vlaue).focus();
            po_counter.push(count_vlaue);
            $('#counter').val(po_counter);
            $('#loading').hide();
        }
    });
    $('.ga_data ').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '110px',
        nonSelectedText: 'Map GA',
        templates: {
            button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });
    $('.po_types').multiselect({
        //buttonWidth: '150px',
        nonSelectedText: 'Select GA',
        templates: {
            button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });
});

//Function to delete unwanted textarea
$('.Delete').live('click', function () {
    rowId = $(this).attr("id").match(/\d+/g);
    if (rowId != 1) {
        $(this).parent().parent().parent().remove();

        var replaced_id = $(this).attr('id').replace('remove_field', '');
        var po_counter_index = $.inArray(parseInt(replaced_id), po_counter);
        po_counter.splice(po_counter_index, 1);
        $('#counter').val(po_counter);

        return false;
    }
});

//Approval program outcome
//Function to invoke modal for sending program outcomes for rework
function rework() {
    var curriculum_id = document.getElementById('crclm_id_rework').value;

    if (curriculum_id) {
        var post_data = {
            'curriculum_id': curriculum_id,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/po/chairman_user_name',
            data: post_data,
            dataType: "JSON",
            success: function (msg) {
                $('#chairman_user_name').html(msg.chairman_user_name);
                $('#crclm_name_workflow').html(msg.crclm_name);
                $('#myModal_rework').modal('show');
            }
        });
    }
}

//Function to send program outcome details for rework
function send_rework() {
    $('#loading').show();
    var curriculum_id = document.getElementById('crclm_id_rework').value;

    var post_data = {
        'crclm_id': curriculum_id,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/po/po_rework_dashboard_insert',
        data: post_data,
        success: function (msg) {
            document.getElementById("rework").disabled = true;
            document.location.href = base_url + 'dashboard/dashboard';
        }
    });
}

//Function to publish program outcome details
function publish() {

    $('#loading').show();
    var curriculum_id = document.getElementById('curriculum_list').value;

    var post_data = {
        'crclm_id': curriculum_id,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/po/bos_publish_details',
        data: post_data,
        success: function (msg) {
            // added by Bhagya S S
            $('#loading').hide();
            $(location).attr('href', base_url + 'curriculum/map_po_peo/map_po_peo_index/' + curriculum_id);
        }
    });
}

function publish_others() {

    $('#loading').show();
    var curriculum_id = document.getElementById('curriculum_list').value;

    var post_data = {
        'crclm_id': curriculum_id,
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/po/bos_publish_details',
        data: post_data,
        success: function (msg) {
            // added by Bhagya S S
            $('#loading').hide();
            document.location.href = base_url + 'dashboard/dashboard';
            // $(location).attr('href', base_url + 'curriculum/map_po_peo/map_po_peo_index/' + curriculum_id);
        }
    });
}

//Program Outcome Script Ends Here

$.validator.addMethod("loginRegex1", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\-\s]+$/i.test(value);
}, "Field must contain only letters, numbers, spaces, underscore,  ' or dashes.");
$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");

// Form validation rules are defined & checked before form is submitted to controller.		
$("#add_form_id").validate({
    rules: {
        'po_statement[]': {
            loginRegex: true,
        },
    },
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).css({"color": "red", "border-color": "red"});
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).css({"color": "green", "border-color": "green"});
    }
});

$('.add_form_submit').on('click', function (e) {
    $('#loading').show();
    $('#topic_add_form').validate();
    // adding rules for inputs with class 'comment'
    $('.po_stmt').each(function () {
        $(this).rules("add",
                {
                    loginRegex: true
                });
    });

    $('.po_name').each(function () {
        $(this).rules("add",
                {
                    loginRegex1: true
                });
    });
    $('#loading').hide();
});

/* 	Author 	: 	Mritunjay 
 Date	:	17/3/2014
 Function to create grid for po add*/
$(document).ready(function () {
    $('div #accreditation_type').on('change', '.accreditation', function () {
        if ($(this).attr('id') == 'custom') {
            location.reload();
            //$('#example_wrapper').();
            $("#example_custom").show("fast");
        } else {
            $("#example_custom").hide();
            $('#add_before').empty();
            po_counter = [];
            var atype_id = $(this).attr('abbr');
            var crclm_id = $('#crclm_id').val();

            accredit_type = {
                'atype_id': atype_id,
                'crclm_id': crclm_id
            }
            $('#loading').show();
            $.ajax({type: "POST",
                url: base_url + 'curriculum/po/accredit_po_grid',
                data: accredit_type,
                async: false,
                success: function (accredit_grid) {
                    $('#add_me').html(accredit_grid);
                    var count = $('.append_class').val();

                    $('#po_type_counter').val(count);
                    var i;

                    for (i = 1; i <= count; i++) {
                        po_counter.push(i);
                    }
                    $('#counter').val(po_counter);
                    $('#loading').hide();
                }
            });

            $('.ga_data').multiselect({
                includeSelectAllOption: true,
                buttonWidth: '110px',
                nonSelectedText: 'Map GA',
                templates: {
                    button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
                }
            });

            $('.po_types').multiselect({
                //	buttonWidth: '150px',
                nonSelectedText: 'Select GA',
                templates: {
                    button: '<button type="button" class="multiselect btn btn-link po_multi dropdown-toggle" data-toggle="dropdown"></button>'
                }
            });

        }
    });
});

function submit_mapping_form() {
    document.getElementById('crclm_id').value = document.getElementById('curriculum_list').value;
    $('#frm').submit();
}

//function to get the notes (text) entered
$(".po_comment_enter").live("keyup", function () {
    var crclm_id = $('#curriculum_list').val();
    var po_id = $(this).attr("abbr");
    var po_comment = $(this).val();

    var post_data = {
        'crclm_id': crclm_id,
        'po_id': po_id,
        'po_comment': po_comment
    }
    $.ajax({
        url: base_url + 'curriculum/po/save_po_comment',
        type: "POST",
        data: post_data,
        success: function (data) {
            if (!data) {
                alert("unable to save file!");
            }
        }
    });

});

//function to save the notes (text)

// multiselect function

$(function () {

    var $form = $("#add_form_id");
    var validator = $form.data('validator');
    validator.settings.ignore = ':hidden:not(".ga_data"):hidden:not(".po_types")';

    $('.ga_data ').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '110px',
        nonSelectedText: 'Map GA',
        templates: {
            button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });

    $('.po_types').multiselect({
        buttonWidth: '110px',
        templates: {
            button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });

    $('#example-filterBehavior').multiselect({
        includeSelectAllOption: true

    });

    $('.ga_data').click(function () {
        var selected = $('.ga_data  option:selected');
        var message = "";
        selected.each(function () {
            message += $(this).text() + " " + $(this).val() + "\n";
        });

    });
});

/***********artifacts***********/

//displaying the modal
$('#artifacts_modal').click(function (e) {
    e.preventDefault();
    display_artifact();
});

//displaying the modal content
function display_artifact() {
    var artifact_value = $('#art_val').val();
    var crclm_id = $('#curriculum_list').val();
    $('#loading').show();
    if (crclm_id != '') {
        var post_data = {
            'art_val': artifact_value,
            'crclm': crclm_id
        }

        $.ajax({
            type: "POST",
            url: base_url + 'upload_artifacts/artifacts/modal_display',
            data: post_data,
            async: false,
            success: function (data) {
                $('#art').html(data);
                $('#loading').hide();
                $('#mymodal').modal('show');

                var base_url = $('#get_base_url').val();
                var url = base_url + "locale/" + $.cookie("locale_lang") + "-keywords.json";

                $.getJSON(url, function (data) {
                    $("*[data-key]").each(function () {
                        $(this).text(data[$(this).data('key')]);
                    });
                });
            }
        });
    } else {
        $('#loading').hide();
        $('#select_crclm').modal('show');
    }
}

//uploading the file 
$('.art_facts,#curriculum_list').on('click change', function (e) {
    var uploader = document.getElementById('uploaded_file');
    var crclm_id = $('#curriculum_list').val();
    var art = $('#art_val').val();
    var val = $(this).attr('uploaded-file');
    var folder_name = $('#curriculum_list option:selected').val();

    var post_data = {
        'crclm': crclm_id,
        'art_val': art,
        'crclm': folder_name
    }
    upclick({
        element: uploader,
        action_params: post_data,
        action: base_url + 'upload_artifacts/artifacts/modal_upload',
        onstart: function (filename) {
            (document).getElementById('loading_edit').style.visibility = 'visible';
        },
        oncomplete: function (response_data) {
            if (response_data == "file_name_size_exceeded") {
                $('#file_name_size_exc').modal('show');
            } else if (response_data == "file_size_exceed") {
                $('#larger').modal('show');
            }
            display_artifact();
            (document).getElementById('loading_edit').style.visibility = 'hidden';
        }
    });
});

//deleting the file
$('#art').on('click', '.artifact_entity', function (e) {
    var del_id = $(this).attr('data-id');

    $('#delete_file').modal('show');
    $('#delete_selected').click(function (e) {
        $('#loading').show();
        $.ajax({
            type: "POST",
            url: base_url + 'upload_artifacts/artifacts/modal_delete_file',
            data: {'artifact_id': del_id},
            success: function (data) {
                $('#loading').hide();
                display_artifact();
            }
        });
        $('#delete_file').modal('hide');
    });

});

$('body').on('focus', '.std_date', function () {
    $("#af_actual_date").datepicker({
        format: "yyyy-mm-dd",
        //endDate:'-1d'
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });
    $(this).datepicker({
        format: "yyyy-mm-dd",
        //endDate:'-1d'
    }).on('changeDate', function (ev) {
        $(this).blur();
        $(this).datepicker('hide');
    });
});

$('#art').on('click', '.std_date_1', function () {
    $(this).siblings('input').focus();
});

//on save artifact description and date
$('#save_artifact').live('click', function (e) {
    e.preventDefault();
    $('#myform').submit();
});

$('#myform').on('submit', function (e) {
    e.preventDefault();
    var form_data = new FormData(this);
    var form_val = new Array();

    $('.save_form_data').each(function () {
        //values fetched will be inserted into the array
        form_val.push($(this).val());
    });

    //check whether file any file exists or not
    if (form_val.length > 0) {
        //if file exists
        $.ajax({
            type: "POST",
            url: base_url + 'upload_artifacts/artifacts/save_artifact',
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function (msg) {
                if ($.trim(msg) == 1) {
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
});

//message to display animated notification instead of modal
$('.noty').click(function () {
    var options = $.parseJSON($(this).attr('data-noty-options'));
    noty(options);
});
//count number of characters entered in the description box
$('#po_statement_1').live('keyup', function () {
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
