var base_url = $('#get_base_url').val();
var cloneCntr;
var counter;
var uniqueCloneCntr = 2;
var global_id;
var peo_counter = new Array();
var peo_edit_counter = new Array();
peo_counter.push(1);
//Program Educational Objective Page
$(document).ready(function () {
    var base_url = $('#get_base_url').val();
    var current_peo_id;
    $('.clone .add_more_peo_counter').each(function () {
        cloneCntr = $(this).val();
    });


    function fixIds(element, counter) {
        $(element).find("[id]").add(element).each(function () {
            this.id = this.id.replace(/\d+$/, "") + counter;
            this.name = this.id;
        });
    }

    $('.clone .edit_peo').each(function () {
        cloneCntr = $(this).attr('abbr');
        peo_edit_counter.push(String(cloneCntr));
    });
    $('#add_more_peo_counter').val(peo_edit_counter);
    counter = ++cloneCntr;

    function edit_fixIds(element, counter) {
        $(element).find("[id]").add(element).each(function () {
            this.id = this.id.replace(/\d+$/, "") + counter;
            $(this).attr('abbr', counter);
        });
        $(element).find('[name="peo_id[]"]').remove();
    }

    //To get the current program educational objective id
    $('.peo_del').live('click', function () {
        current_peo_id = $(this).attr('abbr');
    });

    //Function to generate new textarea to add program educational objective statement in the edit page
    $("a#edit_field").click(function () {
        $('#loading').show();
        var table = $("#add_peo").clone(true, true)
        edit_fixIds(table, cloneCntr);
        table.insertBefore("#add_before");
        $('#peo_reference' + cloneCntr).val('');
        $('#peo_reference' + cloneCntr).attr('name', 'peo_reference' + cloneCntr);
        $('#peo_statement' + cloneCntr).val('');
        $('#add_peo' + cloneCntr + ' div div textarea').attr('name', 'peo_statement' + cloneCntr);
        peo_edit_counter.push(String(cloneCntr));
        $('#add_more_peo_counter').val(peo_edit_counter);
        cloneCntr++;
        $('#loading').hide();

    });

    //Function to insert new textarea for writing program educational objective statements in the add page
    $("a#add_field").click(function () {
        $('#loading').show();
        var table = $("#add_peo").clone(true, true)
        var peo_count = $('#counter').val();
        fixIds(table, uniqueCloneCntr);
        table.insertBefore("#insert_before");
        $('#peo_reference' + uniqueCloneCntr).val('');
        $('#peo_statement' + uniqueCloneCntr).val('');
        $('#char_span_support' + uniqueCloneCntr).val();
        peo_counter.push(String(uniqueCloneCntr));
        $('#counter').val(peo_counter);
        uniqueCloneCntr++;
        $('#loading').hide();
    });

    //Function to delete program educational objective objective textarea in add page
    $('.Delete').click(function () {
        $('#loading').show();
        rowId = $(this).attr("id").match(/\d+/g);
        if (rowId != 1) {
            $(this).parent().parent().parent().remove();
            var replaced_id = $(this).attr('id').replace('remove_field_', '');
            var peo_counter_index = $.inArray(replaced_id, peo_counter);
            peo_counter.splice(peo_counter_index, 1);
            $('#counter').val(peo_counter);

            var peo_edit_replaced_id = $(this).attr('id').replace('remove_field', '');
            var peo_edit_counter_index = $.inArray(peo_edit_replaced_id, peo_edit_counter);
            peo_edit_counter.splice(peo_edit_counter_index, 1);
            $('#add_more_peo_counter').val(peo_edit_counter);
            $('#loading').hide();
            return false;
        } else
            $('#loading').hide();
    });

    //function to delete peo from the list page
    $('.delete_peo').live('click', function () {
        var post_data = {
            'peo_id': current_peo_id,
        }

        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/peo/peo_delete',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                success_modal_delete(msg);
                var crclm = $('#crclm').val();
                var post_data = {'curriculum_id': crclm}
                $.ajax({type: "POST",
                    url: base_url + 'curriculum/peo/peo_list',
                    data: post_data,
                    dataType: 'json',
                    success: function (msg) {
                        populate_table_data(msg);
                    }
                });

            }
        });
    });

    //To fetch help content related to program educational objective
    $('.show_help').live('click', function () {
        $.ajax({
            url: base_url + 'curriculum/peo/peo_help',
            datatype: "JSON",
            success: function (msg) {
                $('#help_content').html(msg);
            }
        });
    });

    $('#crclm').on('change', function () {
        grid();
    });

    //display grid on select of curriculum in the list page
    if ($.cookie('remember_selected_value') != null) {
        // set the option to selected that corresponds to what the cookie is set to
        $('#crclm option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
        grid();
    }

    function grid() {
        $.cookie('remember_selected_value', $('#crclm option:selected').val(), {expires: 90, path: '/'});
        var curriculum_id = $('#crclm').val();

        var post_data = {
            'curriculum_id': curriculum_id,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/peo/peo_list',
            data: post_data,
            dataType: 'json',
            success: populate_table
        });
    }

    $("#edit_peo").on('click', function () {
        var curriculum_id = document.getElementById('crclm').value;
        window.location = base_url + 'curriculum/peo/peo_edit/' + curriculum_id;

    });
    $("#peo_edit_form").validate({
        rules: {
            peo_statement: {
                // maxlength: 20,
                required: true
            },
        },
        errorClass: "help-inline font_color",
        errorElement: "label",
        highlight: function (element, errorClass, validClass) {
            $(element).parent().parent().addClass('error').removeClass('success');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().parent().removeClass('error');
            $(element).parent().parent().addClass('success').removeClass('error');
        }
    });
    $('#example').on('click', '.peo_edit', function () {
        $('#peo_id').val($(this).attr('data-id'));
        $('#peo_state').val($(this).attr('data-state_name'));
        $('#peo_statement').val($(this).attr('data-state'));
        $('#attendees_name').val($(this).attr('data-attendees_name'));
        $('#attendees_notes').val($(this).attr('data-attendees_notes'));
        var crclm_name = $("#crclm").val();
        $("#crclm_name_text").html("<span>Curriculum :</span>" + $("#crclm option[value=" + crclm_name + "]").text());

        var crclm = $("#crclm").val();
        $("#crclm_name_text_1").html("<span>Curriculum :</span>" + $("#crclm option[value=" + crclm + "]").text());

        $('#edit_peo_modal').modal('show');
    });
    $('#peo_update').on('click', function () {
        var peo_id = $('#peo_id').val();
        var peo_ref = $('#peo_state').val();
        var peo_stmt = $('#peo_statement').val();
        var crclm = $('#crclm').val();
        var attendees_name = $('#attendees_name').val();
        var attendees_notes = $('#attendees_notes').val();
        var post_data = {'peo_id': peo_id, 'peo_ref': peo_ref, 'peo_stmt': peo_stmt, 'crclm': crclm, 'attendees_name': attendees_name, 'attendees_notes': attendees_notes}
        $('#peo_edit_form').validate();
        flag = $('#peo_edit_form').valid();
        if (flag == true) {
            $.ajax({
                type: "POST",
                url: base_url + 'curriculum/peo/peo_statement_search',
                data: post_data,
                success: function (msg) {

                    if ($.trim(msg) == 'valid') {
                        $.ajax({type: "POST",
                            url: base_url + 'curriculum/peo/update_peo',
                            data: post_data,
                            success: function (data) {
                                $('#edit_peo_modal').modal('hide');
                                var post_data = {'curriculum_id': crclm}
                                $.ajax({type: "POST",
                                    url: base_url + 'curriculum/peo/peo_list',
                                    data: post_data,
                                    dataType: 'json',
                                    success: [populate_table, success_modal]
                                });
                            }
                        });
                    } else {
                        $('#duplicate_peo').modal('show');
                        $('#loading').hide();
                    }
                }
            });
        }
    });
    function success_modal(msg) {
        var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }
    $("#add_peo_button").on('click', function () {
        var curriculum_id = document.getElementById('crclm').value;

        if (curriculum_id != '') {
            window.location = base_url + 'curriculum/peo/peo_add/' + curriculum_id;
        } else {
            //modal to display warning if curriculum dropdown is not selected
            $('#select_crclm_modal').modal('show');
        }
    });

    $("#add_peo_one").on('click', function () {
        var curriculum_id = document.getElementById('crclm').value;

        if (curriculum_id != '') {
            window.location = base_url + 'curriculum/peo/peo_add/' + curriculum_id;
        } else {
            //modal to display warning if curriculum dropdown is not selected
            $('#select_crclm_modal').modal('show');
        }
    });





    //generates a grid on select of curriculum from the dropdown
    function populate_table(msg) {
        $('#peo_current_state').html(('PEOs Current Status') + ' :- ' + msg["peo_state"]["state_name"]);

        var isempty = jQuery.isEmptyObject(msg);
        var curriculum_id = document.getElementById('crclm').value;

        if (isempty) {
            $("a#edit_peo").attr("href", "#");
        }

        $('#example').dataTable().fnDestroy();
        $('#example').dataTable(
                {"aoColumns": [
                        {"sTitle": "Sl No.", "mData": "peo_reference", "key": "lg_slno"},
                        {"sTitle": "Program Educational Objective(PEO) Statements", "mData": "peo_statement", "key": "lg_peos_lists"},
                        {"sTitle": "Edit", "mData": "Edit", "key": "lg_edit"},
                        {"sTitle": "Delete", "mData": "peo_id", "key": "lg_delete"}
                    ], "aaData": msg["peo_list"],
                    "sPaginationType": "bootstrap"});
        if ($('#crclm').val() != 0) {
            if (msg["peo_state"]["state"] == 5 || msg["peo_state"]["state"] == 7) {
                $('#publish').attr("disabled", true);
                $('#edit_peo').attr("disabled", true);
                $('#add_peo_button').attr("disabled", true);
                $('#add_peo_one').attr("disabled", true);
            } else {
                $('#add_peo_button').attr("disabled", false);
                $('#add_peo_one').attr("disabled", false);
                if (msg["peo_list"][0]["peo_id"] == '') {
                    $('#publish').attr("disabled", true);
                    $('#edit_peo').attr("disabled", true);
                } else {
                    $('#publish').attr("disabled", false);
                    $('#edit_peo').attr("disabled", false);
                }
            }
        } else {
            $('#publish').attr("disabled", true);
            $('#edit_peo').attr("disabled", true);
        }
    }


    //publish operation
    $('#publish').live('click', function () {
        var curriculum_id = document.getElementById('crclm').value;
        $('#loading').show();
        if (curriculum_id) {
            var post_data = {
                'curriculum_id': curriculum_id,
            }

            $.ajax({type: "POST",
                url: base_url + 'curriculum/peo/chairman_user_details',
                data: post_data,
                dataType: "JSON",
                success: function (msg) {
                    $('#chairman_user').html(msg.chairman_user_name);
                    $('#crclm_name_peo').html(msg.crclm_name);
                    $('#loading').hide();
                    $('#my_modal_publish').modal('show');
                }
            });
        } else
            $('#loading').hide();
    });

//function to publish after adding program educational objectives to the grid
    $('.publish_po').live('click', function () {


        $('#loading').show();
        var curriculum_id = document.getElementById('crclm').value;
        var post_data = {
            'curriculum_id': curriculum_id,
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/peo/publish_details',
            data: post_data,
            success: function (msg) {
                $('#loading').hide();
                window.location = base_url + 'curriculum/po/po_index/' + curriculum_id;

            }
        });
    });

    //validation and highlights an invalid element by fading it out and in again - edit page
    $(document).ready(function () {
        $.validator.addMethod("loginRegex", function (value, element) {
            return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\$\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
        }, "Field must contain only letters, spaces, ' or dashes or dot");

        $.validator.addMethod("loginRegex_one", function (value, element) {
            return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\$\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
        }, "Field must contain only letters, spaces, ' or dashes or dot");

        $("#peo_edit").validate({
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
    });

    //validation and highlights an invalid element by fading it out and in again - add page
    $("#peo_add").validate({
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
});

function success_modal_save(msg) {
    var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
function success_modal_delete(msg) {
    var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
function reset_save_list_peo(attendees, notes) {
    $("#peo_add").trigger('reset');
    $('#attendees').val(attendees);
    $('#notes').val(notes);
    var validator = $('#peo_add').validate();
    validator.resetForm();
}

function  populate_table_data(msg) {
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable(
            {"aoColumns": [
                    {"sTitle": "Sl No.", "mData": "peo_reference", "key": "lg_slno"},
                    {"sTitle": "Program Educational Objective(PEO) Statements", "mData": "peo_statement", "key": "lg_peos_lists"},
                    {"sTitle": "Edit", "mData": "Edit", "key": "lg_edit"},
                    {"sTitle": "Delete", "mData": "peo_id", "key": "lg_delete"}
                ], "aaData": msg["peo_list"],
                "sPaginationType": "bootstrap"});
}
$('.peo_save').on('click', function (event) {
    $('#peo_add').validate();
    var flag = $('#peo_add').valid();
    var attendees = $('#attendees').val();
    var notes = $('#notes').val();
    var peo_data = $('#peo_statement1').val();
    var crclm = $('#crclm').val();
    var post_data = {'peo_stmt': peo_data,
        'crclm': crclm}
    if (flag == true) {
        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/peo/peo_search',
            data: post_data,
            success: function (msg) {
                if ($.trim(msg) == 'valid')
                {

                    var values = $("#peo_add").serialize();
                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/peo/peo_insert',
                        data: values,
                        success: function (data) {
                            var post_data = {'curriculum_id': crclm}
                            $.ajax({type: "POST",
                                url: base_url + 'curriculum/peo/peo_list',
                                data: post_data,
                                dataType: 'json',
                                success: function (msg) {
                                    populate_table_data(msg);
                                    reset_save_list_peo(attendees, notes);
                                    success_modal_save(msg);
                                }
                            });

                        }
                    });
                } else
                {
                    $('#duplicate_peo').modal('show');
                    $('#loading').hide();
                }
            }

        });


        /*var values = $("#peo_add").serialize();     
         $.ajax({type: "POST",
         url: base_url + 'curriculum/peo/peo_insert',
         data: values,
         success: function (data) {
         var post_data = {'curriculum_id': crclm}
         $.ajax({type: "POST",
         url: base_url + 'curriculum/peo/peo_list',
         data: post_data,
         dataType: 'json',
         success: function (msg) {
         populate_table_data(msg);
         reset_save_list_peo();
         success_modal_save(msg);
         }
         });
         
         }
         });*/
    }
});
//edit the program educational objective statement and display the grid

$('.peo_update').on('click', function (event) {
    $('#peo_edit').validate();
    // adding rules for inputs with class 'comment'
    $('.edit_peo').each(function () {
        $(this).rules("add",
                {
                    loginRegex_one: true,
                });
    });
});
//edit the program educational objective statement and display the grid
$(document).ready(function () {
    function peo_update() {
        $('#peo_edit').submit();
    }
});

//to set the color of textarea border meant for writing program educational objective statements - add page
//loading image till the email is sent
$('.submit').click(function () {

    $('#loading').show();

});

//Map PEO to Mission Elements
function select_curriculum() {
    $('#loading').show();
    var curriculum_id = document.getElementById('crclm').value;
    if (curriculum_id) {
        var post_data = {
            'curriculum_id': curriculum_id,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/peo/map_table',
            data: post_data,
            success: function (msg)
            {
                $('#loading').hide();
                //document.getElementById('mapping_table').innerHTML = msg;
                $('#peomeList_vw').html(msg);
                $('#my_map_mission').modal('show');
                fetch_peo_me_mapping_comment_notes();
            }
        });
    } else {
        $('#loading').hide();
        //modal to display warning if curriculum dropdown is not selected
        $('#select_crclm').modal('show');
    }

}




$('#map_peo_to_me').live('click', function () {
    $('#loading').show();
    var curriculum_id = document.getElementById('crclm').value;
    if (curriculum_id) {
        var post_data = {
            'curriculum_id': curriculum_id,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/peo/map_table',
            data: post_data,
            success: function (msg)
            {
                $('#loading').hide();
                //document.getElementById('mapping_table').innerHTML = msg;
                $('#peomeList_vw').html(msg);
                $('#my_map_mission').modal('show');
                fetch_peo_me_mapping_comment_notes();
            }
        });
    } else {
        $('#loading').hide();
        //modal to display warning if curriculum dropdown is not selected
        $('#select_crclm').modal('show');
    }
});


//to display po statement to the user
function writetext2(me) {
    document.getElementById('me_display_textbox_id').innerHTML = me;
}

var globalid;

//
$('.check').live("click", function () {
    var id = $(this).attr('value');
    globalid = $(this).attr('id');
    window.id = id;
    var curriculum_id = document.getElementById('crclm').value;
    window.curriculum_id = curriculum_id;
    var post_data = {
        'me': id,
        'crclm_id': curriculum_id,
    }
    if ($(this).is(":checked")) {
        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/peo/add_mapping',
            data: post_data,
            success: function (msg) {
            }
        });
    } else {
        $('#uncheck_mapping_dialog_id').modal('show');
    }
});

//
function cancel_uncheck_mapping_dialog() {
    $('#' + globalid).prop('checked', true);
}

//from modal2
function unmapping() {
    var curriculum_id = document.getElementById('crclm').value;
    var post_data = {
        'me': id,
        'crclm_id': curriculum_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/peo/unmap',
        data: post_data,
        success: function (msg) {
            $('#uncheck_mapping_dialog_id').modal('hide');
        }
    });
}

/**********artifacts***********/

//displaying the modal
$('#artifacts_modal').click(function (e) {
    e.preventDefault();
    display_artifact();
});

//displaying the modal content
function display_artifact() {
    var artifact_value = $('#art_val').val();
    var crclm_id = $('#crclm').val();
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
            }
        });
    } else {
        $('#loading').hide();
        $('#select_crclm').modal('show');
    }
}

//uploading the file 
$('.art_facts,#crclm').on('click change', function (e) {
    var uploader = document.getElementById('uploaded_file');
    var crclm_id = $('#crclm').val();
    var art = $('#art_val').val();
    var val = $(this).attr('uploaded-file');
    var folder_name = $('#crclm option:selected').val();

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

$('.peo_me_maping').live('change', function () {
    (document).getElementById('loading_data').style.visibility = 'visible';
    //(document).getElementById('just').style.visibility = 'visible';
    var id = $(this).attr('value');
    globalid = $(this).attr('id');
    window.id = id;
    var crclm_id = $('#crclm').val();
    var map_level_data = id.split('|');
    var map_level = map_level_data.length;
    var post_data = {'peo_id': id, 'crclm_id': crclm_id}
    $('#just').show();
    if (map_level == 3) {
        //$('#just').show();
        $.ajax({type: "POST",
            url: base_url + 'curriculum/peo/add_mapping_new',
            data: post_data,
            success: function (msg) {
                (document).getElementById('loading_data').style.visibility = 'hidden';
                var curriculum_id = document.getElementById('crclm').value;
                var post_data = {'curriculum_id': curriculum_id, }
                $.ajax({type: "POST",
                    url: base_url + 'curriculum/peo/map_table',
                    data: post_data,
                    success: function (msg)
                    {
                        $('#loading').hide();
                        //document.getElementById('mapping_table').innerHTML = msg;
                        $('#peomeList_vw').html(msg);
                        $('#my_map_mission').modal('show');
                        fetch_peo_me_mapping_comment_notes();
                    }
                });
            }
        });
    } else {
        //$('#just').hide();
        (document).getElementById('loading_data').style.visibility = 'hidden';
        $('#uncheck_mapping').modal('show');
    }
});

$(document).ready(function () {
    $('.comment').live('click', function () {

        //$('a[rel=popover]').not(this).popover('destroy');
        $('a[rel=popover]').popover({
            html: 'true',
            placement: 'top'
        })
    });
    $('.close_btn').live('click', function () {
        $('a[rel=popover]').not(this).popover('destroy');
    });

    $('.comment_just').live('click', function (e) {
        e.preventDefault();
        var comment_map_val = $(this).attr('abbr');
        var comment_array = comment_map_val.split('|');
        var peo_id = comment_array[0];
        var crclm_id = comment_array[1];
        var me_id = comment_array[2];
        var pm_id = comment_array[3];
        var justification = $('#justification').val();
        var post_data = {'peo_id': peo_id, 'crclm_id': crclm_id, 'me_id': me_id, 'pm_id': pm_id}

        $.ajax({type: "POST",
            url: base_url + 'curriculum/peo/fetch_justification',
            data: post_data,
            dataType: 'JSON',
            success: function (msg) {
                if (msg.length > 0) {
                    if (msg[0].justification == null) {
                        $('#justification').text("");
                    } else {
                        $('#justification').text(msg[0].justification);
                    }
                } else {
                    $('#justification').text('');
                }
            }
        });

        //$(this).attr('data-content').popover('show');
        $('a[rel=popover]').not(this).popover('destroy');
        $('a[rel=popover]').popover({
            html: 'true',
            trigger: 'manual',
            placement: 'left'
        })
        $(this).popover('show');
        $('.close_btn').live('click', function () {
            $('a[rel=popover]').not(this).popover('destroy');
        });
    });

    $('.cmt_submit').live('click', function () {
        $('a[rel=popover]').not(this).popover('hide');
        var po_id = $('#po_id').val();
        var clo_id = $('#clo_id').val();
        var crclm_id = $('#crclmid').val();

        var post_data = {
            'po_id': po_id,
            'clo_id': clo_id,
            'crclm_id': crclm_id,
            'status': 0,
        }
        $.ajax({type: "POST",
            url: base_url + 'curriculum/clopomap_review/clo_po_cmt_update',
            data: post_data,
            success: function (msg) {

            }
        });
    });


    $('.save_justification').live('click', function () {
        var data = $('.map_select').val();
        var crclm_id = $('#crclm').val();
        var data1 = data.split('|');
        var me_id = $('#me_id').val();
        var peo_id = $('#peo_id_data').val();
        var pm_id = $('#pm_id').val();
        var justification = $('#justification').val();
        var post_data = {'peo_id': peo_id, 'crclm_id': crclm_id, 'me_id': me_id, 'justification': justification, 'pm_id': pm_id}
        $.ajax({type: "POST",
            url: base_url + 'curriculum/peo/save_justification',
            data: post_data,
            success: function (msg) {
                $('a[rel=popover]').not(this).popover('destroy');
                //  $('a[rel=popover]').not(this).popover('toggle');
                var curriculum_id = $('#crclm').val();
                var post_data = {'curriculum_id': curriculum_id, }
                $.ajax({type: "POST",
                    url: base_url + 'curriculum/peo/map_table',
                    data: post_data,
                    success: function (msg)
                    {
                        $('#loading').hide();
                        //document.getElementById('mapping_table').innerHTML = msg;
                        $('#peomeList_vw').html(msg);
                        $('#my_map_mission').modal('show');
                        fetch_peo_me_mapping_comment_notes();
                    }
                });
            }
        });
    });

    $('.cmt_submit').live('click', function () {
        $('a[rel=popover]').not(this).popover('hide');
    });
});


function unmapping_new() {
    (document).getElementById('loading_data').style.visibility = 'visible';
    var crclm_id = $('#crclm').val();
    var post_data = {
        'peo': id,
        'crclm_id': crclm_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/peo/unmap_new',
        data: post_data,
        success: function (msg) {
            (document).getElementById('loading_data').style.visibility = 'hidden';
            var curriculum_id = document.getElementById('crclm').value;
            var post_data = {'curriculum_id': curriculum_id, }
            $.ajax({type: "POST",
                url: base_url + 'curriculum/peo/map_table',
                data: post_data,
                success: function (msg)
                {
                    $('#loading').hide();
                    //document.getElementById('mapping_table').innerHTML = msg;
                    $('#peomeList_vw').html(msg);
                    $('#my_map_mission').modal('show');
                    fetch_peo_me_mapping_comment_notes();
                }
            });
            $('#uncheck_mapping').modal('hide');//success_modal_update();
        }
    });
}

/* 	$(document).delegate('.res_description_data,.actual_date_data','change  paste click', function() {
 var desc = $('#res_description').val();
 var date = $('#actual_date').val(); 
 var my_id_data = $('#my_id_data').val();  
 var user_id = $('#user_id').val(); 
 post_data =  {'user_id':user_id,'my_id':my_id_data,'desc':desc,'actual_date':date}
 $.ajax({type: 'POST',
 url: base_url+'report/edit_profile/save_description',
 data: post_data,
 success: function(data) {
 
 }}); 
 
 });  */


//function to get the notes (text) entered
// $("textarea#peo_me_comment_box_id").bind("keyup", function() {
$(document).delegate('textarea#peo_me_comment_box_id', 'change  paste  ', function () {
    var curriculum_id = document.getElementById('crclm').value;
    var text_value = $(this).val();

    var post_data = {
        'crclm_id': curriculum_id,
        'text': text_value,
    }

    $.ajax({
        url: base_url + 'curriculum/peo/save_txt',
        type: "POST",
        data: post_data,
        success: function (data) {
            if (!data) {
                alert("unable to save file!");
            }
        }
    });
});

//function to fetch notes (text) related to PO to PEO mapping in a curriculum.
function fetch_peo_me_mapping_comment_notes() {
    var curriculum_id = document.getElementById('crclm').value;
    var post_data = {
        'crclm_id': curriculum_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'curriculum/peo/fetch_txt',
        data: post_data,
        success: function (msg) {
            document.getElementById('peo_me_comment_box_id').innerHTML = msg;
            $('#peo_me_comment_box_id').val(msg);
        }
    });
}
//count number of characters entered in the description box
$('.char-counter').live('keyup', function () {
    var textarea_id = this.id;
    textarea_id = textarea_id.replace("peo_statement", "");

    var max = parseInt($(this).attr('maxlength'));
    var len = $(this).val().length;
    var spanId = 'char_span_support' + textarea_id;
    if (len >= max) {
        $('#' + spanId).css('color', 'red');
        $('#' + spanId).text(' You have reached the limit.');
    } else {
        $('#' + spanId).css('color', '');
        $('#' + spanId).text(len + ' of ' + max + '.');
    }
});

