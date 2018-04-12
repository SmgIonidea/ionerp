
//Performance Indicators (PI) and Measures list page, add, edit & delete page and static page

var base_url = $('#get_base_url').val();

//For Performance indicator
var cloneCntr;
var pi_edit_counter = new Array();
var pi_counter = new Array();
pi_counter.push(1);
var pi_add_counter = new Array();
pi_add_counter.push(1);

//For measures
var msr_cloneCntr;
var msr_edit_counter = new Array();
var msr_counter = new Array();
msr_counter.push(1);
var msr_add_counter = new Array();
msr_add_counter.push(1);

//list pi_msr
//Function to fetch help details related to performance indicators and measures
function show_help() {
    $.ajax({
        url: base_url + 'curriculum/pi_and_measures/pi_and_measures_help',
        datatype: "JSON",
        success: function (msg) {
            document.getElementById('help_content').innerHTML = msg;
        }
    });
}

if ($.cookie('remember_curriculum') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#curriculum_list option[value="' + $.cookie('remember_curriculum') + '"]').prop('selected', true);
    select_curriculum();
}

//Function to fetch curriculum details
function select_curriculum() {
    $.cookie('remember_curriculum', $('#curriculum_list option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = $('#curriculum_list').val();
    $('#loading').show();
    var post_data = {
        'curriculum_id': curriculum_id
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/pi_and_measures/select_pi_curriculum',
        data: post_data,
        dataType: 'json',
        success: populate_table
    });
    $('#loading').hide();
}

//Function to fetch and display approved program outcome grid (table), outcome elements & pi and BOS comments
function populate_table(msg) {      
//	    console.log(msg['oe_pi_flag']);
    if ($.trim(msg['oe_pi_flag']) == 1) {
        $('#enableAddButton').show();
        $('#proceedOePi').hide();
        $('#oe_pi_mandatory_optional').html(outcome_element + ' & PIs are <span class="badge badge-important"> mandatory </span> for this Curriculum');
    } else {
        $('#enableAddButton').hide();
        $('#proceedOePi').show();
        $('#oe_pi_mandatory_optional').html(outcome_element + ' & PIs are <span class="badge badge-important"> optional </span> for this Curriculum');
    }

    var m = 'd';
    $('#pi_measures_current_state').html(outcome_element + ' & PIs Current Status : ' + msg["pi_state"]["state_name"]);
    $('#example').dataTable().fnDestroy();

    $('#example').dataTable(
            {
                "aaSorting": [],
                "aoColumns": [
                    {"sTitle": "PO Code", "mData": "sl_no"},
                    {"sTitle": "Approved " + student_outcomes_full + " (" + sos + ") ", "mData": "po_statement"},
                    {"sTitle": "View " + outcome_element + " & PIs", "mData": "po_id2"},
                    {"sTitle": "BoS comments after review", "mData": "cmt_id"},
                    {"sTitle": "Manage " + outcome_element + " & PIs", "mData": "po_id3"},
                ], "aaData": msg["pi_list"],
                "sPaginationType": "bootstrap"}
    );

    if (msg["pi_state"]["state"] == 7 || msg["pi_state"]["state"] == 5) {
        $('#publish').attr("disabled", true);
        $('#skipOePi').attr("disabled", true);
    } else {
        $('#publish').attr("disabled", false);
        $('#skipOePi').attr("disabled", false);
    }
}

//Function to send performance indicator and its corresponding measures of a program outcome for approval
function publish() {
    $('#loading').show();
    var curriculum_id = document.getElementById('curriculum_list').value;

    if (curriculum_id) {
        var post_data = {
            'curriculum_id': curriculum_id,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/pi_and_measures/publish_details',
            data: post_data,
            success: function (msg) {
                window.location.reload(true);
            }
        });
        $('#loading').hide();
    }
}

//Function to fetch curriculum details
function static_select_curriculum() {
    var curriculum_id = document.getElementById('curriculum_list').value;

    var post_data = {
        'curriculum_id': curriculum_id
    }

    $.ajax({type: "POST",
        url: base_url + 'curriculum/pi_and_measures/static_select_pi_curriculum',
        data: post_data,
        dataType: 'json',
        success: static_populate_table
    });
}

//Function to populate the approved program outcome grid (table)
function static_populate_table(msg) {
    $('#example').dataTable().fnDestroy();

    $('#example').dataTable(
            {"aoColumns": [
                    {"sTitle": "Approved "+ student_outcomes_full + "("+sos+")" , "mData": "po_statement"},
                    {"sTitle": "Outcome Elements & PIs", "mData": "po_id2"},
                ], "aaData": msg["pi_list"]}
    );
}

//Function to fetch performance indicators and measures - manage pi & msr page
function fetch_pi_msr() {
    var po_id = document.getElementById('static_po_id').value;

    var post_data = {
        'po_id': po_id
    }

    $.ajax({
        type: "POST",
        data: post_data,
        url: base_url + 'curriculum/pi_and_measures/fetch_manage_pi_measures',
        success: function (msg) {

        }
    });
}

//Function to call modal to display existing measures
$('.view_measures').click(function () {
    var pi_id = $(this).siblings('.pi_class').val();

    if (pi_id) {
        var post_data = {
            'pi_id': pi_id,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/pi_and_measures/fetch_pi_related_measures',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                document.getElementById('added_msr_view_modal').innerHTML = msg;
            }
        });

        $('#myModal_view_measures').modal('show');
    } else {
        $('#myModal_measures_warning').modal('show');
    }
});

//Function to fetch curriculum details and display the grid on load
$(document).ready(function () {
    var table_row;
    var po_id;

    $('.get_id').live('click', function (e) {
        po_id = $(this).attr('id');
        table_row = $(this).closest("tr").get(0);
    });

    //Function to fetch performance indicators and measures of corresponding program outcomes
    $('.get_pi').live('click', function (e) {
        e.preventDefault();

        var post_data = {
            'po_id': po_id,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/pi_and_measures/get_pi',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                document.getElementById('po_pi_msr_list').innerHTML = msg;
            }
        });
    });

    //Function to publish program outcome along with its performance indicators and corresponding measures for approval
    $('#publish').live('click', function () {
        var curriculum_id = document.getElementById('curriculum_list').value;
        $('#loading').show();
        var post_data = {
            'crclm_id': curriculum_id,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/pi_and_measures/fetch_measures_count',
            data: post_data,
            datatype: "JSON",
            success: function (msg) {
                if (msg == 0) {
                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/pi_and_measures/bos_user_name',
                        data: post_data,
                        dataType: "json",
                        success: function (msg) {
                            $('#bos_user').html(msg.bos_user_name);
                            $('#crclm_name_oe_pi').html(msg.crclm_name);
                            $('#myModal_approval').modal('show');
                            $('#loading').hide();
                        }
                    });
                } else {
                    $('#error_table').html(msg);
                    $('#myModal_error').modal('show');
                    $('#loading').hide();
                }
            }
        });
    });

    //Function to publish program outcome with or without performance indicators and measures and skip approval
    $('#skipOePi').live('click', function () {
        var curriculum_id = document.getElementById('curriculum_list').value;
        $('#loading').show();
        var post_data = {
            'crclm_id': curriculum_id,
        }

        $.ajax({type: "POST",
            url: base_url + 'curriculum/pi_and_measures/prog_user_name',
            data: post_data,
            dataType: "json",
            success: function (msg) {
                $('#pgm_owner_user_accept').html(msg.prog_user_name);
                $('#crclm_name_oe_pi_accept').html(msg.crclm_name);
                $('#skip_myModal_approval').modal('show');
                $('#loading').hide();
            }
        });
    });

    /////////////////////////////////////////////////////////
    //Performance Indicators
    /////////////////////////////////////////////////////////
    $('.clone .add_more_pi_counter').each(function () {
        cloneCntr = $(this).val();
    });

    $('.clone .edit_pi').each(function () {
        cloneCntr = $(this).attr('abbr');
        pi_edit_counter.push(String(cloneCntr));
    });

    counter = ++cloneCntr;
    $('#add_more_pi_counter').val(pi_add_counter);
    $('#add_edit_more_pi_counter').val(pi_edit_counter);

    function edit_fixIds(element, counter) {
        $(element).find("[id]").add(element).each(function () {
            this.id = this.id.replace(/\d+$/, "") + counter;
            $(this).attr('abbr', counter);
            this.name = this.id;
        });
        $(element).find('[name="pi_id[]"]').remove();
        $(element).find('[name="pi_statement' + counter + '"]').val('');
    }

    //Function to generate new textarea to add performance indicator statement in the edit page
    $("a#edit_field").click(function () {
        $('#loading').show();
        var pi_ele_count = $('.pi_ele_count').length;
        pi_ele_count++;

        var table = $("#add_pi").clone(true, true);
        table.find("label.error").remove();
        edit_fixIds(table, cloneCntr);
        table.insertBefore("#add_before");

        $('#add_pi' + cloneCntr + ' div div textarea').attr('name', 'pi_statement' + cloneCntr);

        pi_add_counter.push(String(pi_ele_count));
        pi_edit_counter.push(String(cloneCntr));
        $('#add_more_pi_counter').val(pi_add_counter);
        $('#add_edit_more_pi_counter').val(pi_edit_counter);
        cloneCntr++;
        $('#loading').hide();
    });

    //Function to delete performance indicator textarea in add page
    $(".Delete").live('click', function (e) {
        $('#loading').show();
        e.preventDefault();
        rowId = $(this).attr("id").match(/\d+/g);

        if (rowId != 1) {
            $(this).parent().parent().parent().remove();

            var replaced_id = $(this).attr('id').replace('remove_field_', '');
            var pi_counter_index = $.inArray(replaced_id, pi_counter);

            pi_counter.splice(pi_counter_index, 1);
            $('#counter').val(pi_counter);

            //add counter
            var pi_add_replaced_id = $(this).attr('id').replace('remove_field', '');
            var pi_add_counter_index = $.inArray(pi_add_replaced_id, pi_add_counter);
            pi_add_counter.splice(pi_add_counter_index, 1);
            $('#add_more_pi_counter').val(pi_add_counter);

            //edit counter
            var pi_edit_replaced_id = $(this).attr('id').replace('remove_field', '');
            var pi_edit_counter_index = $.inArray(pi_edit_replaced_id, pi_edit_counter);
            pi_edit_counter.splice(pi_edit_counter_index, 1);
            $('#add_edit_more_pi_counter').val(pi_edit_counter);
            $('#loading').hide();
            return false;
        } else {
            $('#OE_modal').modal('show');
            $('#loading').hide();
        }
    });

    //Function to call modal to add measures
    $('.add_measures').click(function () {
        var pi_id = $(this).siblings('.pi_class').val();
        $('#loading').show();
        if (pi_id) {
            var post_data = {
                'pi_id': pi_id
            }

            $.ajax({type: "POST",
                url: base_url + 'curriculum/pi_and_measures/manage_measures',
                data: post_data,
                success: function (msg) {
                    var msr_data = $('.edit_msr').length;
                    $('#myModal_add_measures').modal('show');

                    $('#add_msr_modal').html(msg);
                    $('.msr_clone .edit_msr').each(function () {
                        msr_cloneCntr = $(this).attr('abbr');
                        msr_edit_counter.push(String(msr_cloneCntr));
                        $('#add_edit_more_msr_counter').val(msr_edit_counter);
                        $('#add_more_msr_counter').val(msr_add_counter);
                    });
                    msr_inc_counter = ++msr_cloneCntr;
                    $('#loading').hide();
                }
            });
        } else {
            $('#myModal_measures_warning').modal('show');
            $('#loading').hide();
        }

    });

    /////////////////////////////////////////////////////////
    //Measures
    /////////////////////////////////////////////////////////
    $('.msr_clone .add_more_msr_counter').each(function () {
        msr_cloneCntr = $(this).val();
    });


    $('#add_more_msr_counter').val(msr_add_counter);
    $('#add_edit_more_msr_counter').val(msr_edit_counter);

    function msr_edit_fixIds(msr_element, msr_inc_counter) {
        $(msr_element).find("[id]").add(msr_element).each(function () {
            this.id = this.id.replace(/\d+$/, "") + msr_inc_counter;
            this.name = this.id;
            $(this).attr('abbr', msr_inc_counter);
        });
        $(msr_element).find('[name="msr_id[]"]').remove();
        $(msr_element).find('[name="msr_statement' + msr_inc_counter + '"]').val('');
    }

    //Function to generate new textarea to add performance indicator statement in the edit page
    $("#add_msr_modal").on('click', '#msr_edit_field', function () {
        $('#loading').show();
        var msr_ele_count = $('.msr_ele_count').length;
        msr_ele_count++;

        var table = $("#add_msr").clone(true, true);
        table.find("label.error").remove();
        msr_edit_fixIds(table, msr_cloneCntr);
        table.insertBefore("#msr_add_before");

        $('#add_msr' + msr_cloneCntr + ' div div textarea').attr('name', 'msr_statement' + msr_cloneCntr);
        msr_add_counter.push(String(msr_cloneCntr));
        msr_edit_counter.push(String(msr_cloneCntr));
        $('#add_more_msr_counter').val(msr_add_counter);
        $('#add_edit_more_msr_counter').val(msr_edit_counter);
        msr_cloneCntr++;
        $('#loading').hide();
    });

    //Function to delete performance indicator textarea in add page
    $(".msr_Delete").live('click', function (e) {
        e.preventDefault();
        $('#loading').show();
        rowId = $(this).attr("id").match(/\d+/g);

        if (rowId != 1) {
            $(this).parent().parent().parent().remove();

            var replaced_id = $(this).attr('id').replace('msr_remove_field_', '');
            var msr_counter_index = $.inArray(replaced_id, msr_counter);
            msr_counter.splice(msr_counter_index, 1);
            $('#msr_counter').val(msr_counter);

            //add counter
            var msr_add_replaced_id = $(this).attr('id').replace('msr_remove_field', '');
            var msr_add_counter_index = $.inArray(msr_add_replaced_id, msr_add_counter);

            msr_add_counter.splice(msr_add_counter_index, 1);
            $('#add_more_msr_counter').val(msr_add_counter);

            //edit counter
            var msr_edit_replaced_id = $(this).attr('id').replace('msr_remove_field', '');
            var msr_edit_counter_index = $.inArray(msr_edit_replaced_id, msr_edit_counter);
            msr_edit_counter.splice(msr_edit_counter_index, 1);
            $('#add_edit_more_msr_counter').val(msr_edit_counter);
            $('#loading').hide();
            return false;
        } else {
            $('#loading').hide();
            $('#myModal_parent_textbox').modal('show');
        }
    });

    //performance indicator validation
    $(".edit_pi").validate({
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

    $('.save_performance_indicators').on('click', function (event) {
        $('#pi_edit').validate();
        // adding rules for inputs with class 'comment'
        $('.edit_pi').each(function () {
            $(this).rules("add", {
                loginRegex_one: false,
            });
        });
    });

    $.validator.addMethod("loginRegex_one", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");

    //measures validation
    $('#msr_edit').validate({
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

    $('.save_measures').live('click', function (event) {
        $('#msr_edit').validate();
        // adding rules for inputs with class 'comment'
        $('.edit_msr').each(function () {
            $(this).rules("add", {
                loginRegex_one: false,
            });
        });
    });
});

//To fetch help content related to curriculum
$('.show_help').live('click', function () {
    $.ajax({
        url: base_url + 'curriculum/pi_and_measures/pi_and_measures_help',
        datatype: "JSON",
        success: function (msg) {
            $('#help_content').html(msg);
        }
    });
});

/**********artifacts**********/

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
                    var data_options = '{"text":"Your data has been updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
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

/****************************************************************************************************************************************************************************/

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

function success_modal_delete(msg) {
    var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

function fail_modal(msg) {//$('#myModal_fail').modal('show');				
    $('#loading').hide();
    var data_options = '{"text":"This Data already exist.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
$('#save_competencies').on('click', function () {
    $("#measures_edit_data").validate({
        rules: {
            pi_statement: {
                required: true,
                loginRegex_one: true
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == "pi_statement") {
                error.appendTo('#error_pi_statement');
            } else {
                error.insertAfter(element);
            }
        }
    });
    var flag = $('#measures_edit_data').valid();
    if (flag == true) {
        var pi_statement = $('#pi_statement').val();
        var po_id = $('#static_po_id').val();
        var post_data = {'pi_statement': pi_statement, 'po_id': po_id}

        $.ajax({type: "POST",
            url: base_url + 'curriculum/pi_and_measures/insert_pis',
            data: post_data,
            success: function (msg) {
                success_modal();
                fetch_pis();
                reset_competencies();
            }
        });
    }
});

$('#update_competencies').on('click', function () {
    $("#measures_edit_data").validate({
        rules: {
            pi_statement: {
                required: true,
                loginRegex_one: true
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr('name') == "pi_statement") {
                error.appendTo('#error_pi_statement');
            } else {
                error.insertAfter(element);
            }
        }
    });
    var flag = $('#measures_edit_data').valid();
    if (flag == true) {
        var pi_statement = $('#pi_statement').val();
        var po_id = $('#static_po_id').val();
        var pi_id = $('#static_pi_id').val();
        var post_data = {'pi_statement': pi_statement, 'po_id': po_id, 'pi_id': pi_id}

        $.ajax({type: "POST",
            url: base_url + 'curriculum/pi_and_measures/update_pis',
            data: post_data,
            success: function (msg) {
                fetch_pis();
                success_modal_update();
            }
        });
    }
});
fetch_pis();

function fetch_pis() {
    var po_id = $('#static_po_id').val();
    var post_data = {'po_id': po_id}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/pi_and_measures/fetch_pis',
        data: post_data,
        dataType: 'json',
        success: function (msg) {
            populate_table_data_view(msg);
            reset_competencies();
        }
    });
}

function  populate_table_data_view(msg) {
    $('#example1').dataTable().fnDestroy();
    $('#example1').dataTable(
            {"sSort": true,
                "sPaginate": true,
                "scrollX": true,
                "aoColumns": [
                    {"sTitle": "Sl No.", "mData": "sl_no"},
                    {"sTitle": "Competency Statement", "mData": "pi_statement"},
                    {"sTitle": "Add / Edit PIs", "mData": "add_edit_pi"},
                    {"sTitle": "Edit", "mData": "edit"},
                    {"sTitle": "Delete", "mData": "delete"},
                ], "aaData": msg,
                "sPaginationType": "bootstrap",
            });
}
function reset_competencies() {
    var validator = $('#measures_edit_data').validate();
    validator.resetForm();
    $("#measures_edit_data").trigger('reset');
    $('#update_competencies').hide();
    $('#save_competencies').show();

}
function reset_measures() {
    var validator = $('#pi_edit_data').validate();
    validator.resetForm();
    $("#pi_edit_data").trigger('reset');
    $('#update_pi').hide();
    $('#save_pi').show();

}
$('#reset_competencies').on('click', function () {
    reset_competencies();
});
$('#reset_measures').on('click', function () {
    reset_measures();
});

$(document).ready(function () {

    $('#update_competencies').hide();
    $('.add_edit_pis').live('click', function (e) {
        $('#static_pi_id').val($(this).attr('id'));
        var post_data = {'pi_id': $(this).attr('id')}
        $.ajax({type: "POST",
            url: base_url + 'curriculum/pi_and_measures/fetch_measures_data',
            data: post_data,
            dataType: 'json',
            success: function (msg) {
                $('#pi_static_statement').val(msg['pi']);
                populate_table_view(msg);

            }
        });
        $('#add_edit_pi_modal').modal('show');
    });

    function  populate_table_view(msg) {
        $('#example2').dataTable().fnDestroy();
        $('#example2').dataTable(
                {"sSort": true,
                    "sPaginate": true,
                    "scrollX": true,
                    "aoColumns": [
                        {"sTitle": "Sl No.", "mData": "sl_no"},
                        {"sTitle": "Performance Indicator", "mData": "measures"},
                        {"sTitle": "Edit", "mData": "edit"},
                        {"sTitle": "Delete", "mData": "delete"},
                    ], "aaData": msg['msr'],
                    "sPaginationType": "bootstrap",
                });
    }
    $('.edit_competency_stmt').live('click', function (e) {
        var validator = $('#pi_edit_data').validate();
        validator.resetForm();
        $('#pi_statement').val($(this).attr('data-pi_statement'));
        $('#static_pi_id').val($(this).attr('id'));
        $('#update_competencies').show();
        $('#save_competencies').hide();

    });
    $('.delete_competency_stmt').live('click', function (e) {
        $('#static_pi_id').val($(this).attr('id'));
        $('#confirmation_delete').modal('show');
    });
    $('#delete_competencies').on('click', function () {
        var pi_id = $('#static_pi_id').val();
        var post_data = {'pi_id': pi_id}
        $.ajax({type: "POST",
            url: base_url + 'curriculum/pi_and_measures/delete_competencies',
            data: post_data,
            dataType: 'json',
            success: function (msg) {
                fetch_pis();
                reset_competencies();
                success_modal_delete();
            }
        });
    });

    $('#update_pi').hide();


    $('#save_pi').on('click', function () {
        $("#pi_edit_data").validate({
            rules: {
                pi_stmt: {
                    required: true,
                    loginRegex_one: true
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr('name') == "pi_stmt") {
                    error.appendTo('#error_pi_stmt');
                } else {
                    error.insertAfter(element);
                }
            }
        });
        var flag = $('#pi_edit_data').valid();
        if (flag == true) {
            var pi_statement = $('#pi_stmt').val();
            var pi_id = $('#static_pi_id').val();
            var post_data = {'pi_stmt': pi_statement, 'pi_id': pi_id}

            $.ajax({type: "POST",
                url: base_url + 'curriculum/pi_and_measures/save_measures',
                data: post_data,
                success: function (msg) {
                    var post_data = {'pi_id': pi_id}
                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/pi_and_measures/fetch_measures_data',
                        data: post_data,
                        dataType: 'json',
                        success: function (msg) {
                            populate_table_view(msg);
                            reset_measures();
                            success_modal();
                        }
                    });
                }
            });
        }
    });

    $('.delete_measures_stmt').live('click', function () {
        $('#measure_id').val($(this).attr('id'));
        $('#confirmation_delete_messures').modal();
    });

    $('#delete_competencies_measures').on('click', function () {
        var measure_id = $('#measure_id').val();
        var post_data = {'measure_id': measure_id}
        $.ajax({type: "POST",
            url: base_url + 'curriculum/pi_and_measures/delete_measures',
            data: post_data,
            dataType: 'json',
            success: function (msg) {
                var pi_id = $('#static_pi_id').val();
                var post_data = {'pi_id': pi_id}
                $.ajax({type: "POST",
                    url: base_url + 'curriculum/pi_and_measures/fetch_measures_data',
                    data: post_data,
                    dataType: 'json',
                    success: function (msg) {
                        populate_table_view(msg);
                        reset_measures();
                        success_modal_delete();
                    }
                });

            }
        });
    });

    $('.edit_measures_stmt').live('click', function () {
        $('#pi_stmt').val($(this).attr('data-mrs_statement'));
        $('#measure_edit_id').val($(this).attr('id'));
        $('#save_pi').hide();
        $('#update_pi').show();

    });

    $('#update_pi').on('click', function () {

        $("#pi_edit_data").validate({
            rules: {
                pi_stmt: {
                    required: true,
                    loginRegex_one: true
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr('name') == "pi_stmt") {
                    error.appendTo('#error_pi_stmt');
                } else {
                    error.insertAfter(element);
                }
            }
        });
        var flag = $('#pi_edit_data').valid();
        if (flag == true) {
            var pi_statement = $('#pi_stmt').val();
            var pi_id = $('#static_pi_id').val();
            var measure_edit_id = $('#measure_edit_id').val();
            var post_data = {'pi_stmt': pi_statement, 'pi_id': pi_id, 'measure_edit_id': measure_edit_id}

            $.ajax({type: "POST",
                url: base_url + 'curriculum/pi_and_measures/update_measures',
                data: post_data,
                success: function (msg) {
                    var post_data = {'pi_id': pi_id}
                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/pi_and_measures/fetch_measures_data',
                        data: post_data,
                        dataType: 'json',
                        success: function (msg) {
                            populate_table_view(msg);
                            reset_measures();
                            success_modal_update();
                        }
                    });
                }
            });
        }
    });
    /**Calling the modal on success**/
    function success_modal(msg) {
        var data_options = '{"text":"Your data has been saved successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
        var options = $.parseJSON(data_options);
        noty(options);
    }

});

