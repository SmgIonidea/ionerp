// SLO List Script Starts From Here.

var base_url = $('#get_base_url').val();
var po_counter = new Array();
po_counter.push(1);
$('#publish').on('click', function (e) {
    e.preventDefault();
    var curriculum_id = document.getElementById('curriculum').value;
    var term_id = document.getElementById('term').value;
    var topic_id = document.getElementById('topic').value;
    var course_id = document.getElementById('course').value;

    if (curriculum_id && term_id && topic_id && course_id) {
        var post_data = {
            'crclm_id': curriculum_id,
            'crs_id': course_id
        }
        $.ajax({type: "GET",
            url: base_url + 'curriculum/clo/fetch_course_owner' + '/' + curriculum_id + '/' + course_id,
            data: post_data,
            dataType: "JSON",
            processData: false,
            success: function (result) {
                $('#course_owner_name').html(result.course_owner_name);
                $('#crclm_name').html(result.crclm_name);
            }
        });
        $('#myModal4').modal('show');
    }
});

function publish() {
    var curriculum_id = document.getElementById('curriculum').value;
    var term_id = document.getElementById('term').value;
    var topic_id = document.getElementById('topic').value;
    var course_id = document.getElementById('course').value;
    var publish_path = base_url + 'curriculum/tlo/publish_details';

    var post_data = {
        'crclm_id': curriculum_id,
        'topic_id': topic_id,
        'course_id': course_id,
        'term_id': term_id,
    }

    $.ajax({type: "POST",
        url: publish_path,
        data: post_data,
        success: function (msg) {
            window.location.reload(true);
        }
    });
}

if ($.cookie('remember_term') != null) {
    // set the option to selected that corresponds to what the cookie is set to
    $('#curriculum option[value="' + $.cookie('remember_term') + '"]').prop('selected', true);
    select_term();
}

function select_term() {
    $.cookie('remember_term', $('#curriculum option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = $('#curriculum').val();
    var term_drop_down_fill_path = base_url + 'curriculum/tlo_list/select_term';
    var post_data = {
        'crclm_id': curriculum_id
    }

    $.ajax({type: "POST",
        url: term_drop_down_fill_path,
        data: post_data,
        success: function (msg) {
            $('#term').html(msg);
            if ($.cookie('remember_course') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#term option[value="' + $.cookie('remember_course') + '"]').prop('selected', true);
                select_course();
            }
        }
    });
}

function select_course() {
    $.cookie('remember_course', $('#term option:selected').val(), {expires: 90, path: '/'});
    var term_id = $('#term').val();
    var course_drop_down_fill = base_url + 'curriculum/tlo_list/select_course';
    var post_data = {
        'term_id': term_id
    }
    $.ajax({type: "POST",
        url: course_drop_down_fill,
        data: post_data,
        success: function (msg) {
            $('#course').html(msg);
            if ($.cookie('remember_topic') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#course option[value="' + $.cookie('remember_topic') + '"]').prop('selected', true);
                select_topic();
            }
        }
    });
}

function select_topic() {
    $.cookie('remember_topic', $('#course option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = document.getElementById('curriculum').value;
    var term_id = document.getElementById('term').value;
    var course_id = document.getElementById('course').value;
    var topic_drop_down_fill = base_url + 'curriculum/tlo_list/select_topic';
    var post_data = {
        'crclm_id': curriculum_id,
        'term_id': term_id,
        'crs_id': course_id,
    }
    $.ajax({type: "POST",
        url: topic_drop_down_fill,
        data: post_data,
        success: function (msg) {
            document.getElementById('topic').innerHTML = msg;
            if ($.cookie('remember_selected_value') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#topic option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
                GetSelectedValue();
            }
        }
    });
}

function GetSelectedValue() {
    $.cookie('remember_selected_value', $('#topic option:selected').val(), {expires: 90, path: '/'});

    var curriculum_id = document.getElementById('curriculum').value;
    var term_id = document.getElementById('term').value;
    var course_id = document.getElementById('course').value;
    var topic_id = document.getElementById('topic').value;

    $('#curriculum_id').val(curriculum_id);
    $('#term_id').val(term_id);
    $('#course_id').val(course_id);
    $('#topic_id').val(topic_id);

    if (curriculum_id && term_id && course_id && topic_id) {
        var edit_tlo_path = base_url + 'curriculum/tlo/edit_tlo';
        var show_topic_path = base_url + 'curriculum/tlo_list/show_topic';

        var post_data = {
            'crclm_id': curriculum_id,
            'term_id': term_id,
            'course_id': course_id,
            'topic_id': topic_id,
        }
        $.ajax({type: "POST",
            url: show_topic_path,
            data: post_data,
            dataType: 'JSON',
            success: populate_table_show_table
        });
    }
}

function populate_table_show_table(msg) {
    $('#example_id').dataTable().fnDestroy();
    $('#example_id').dataTable(
            {
                "aaSorting": [],
                "aoColumns": [
                    {"sTitle": entity_tlo + " Code", "mData": "tlo_code"},
                    {"sTitle": entity_tlo_full, "mData": "tlo_statement"},
                    {"sTitle": "Bloom\'s Level", "mData": "bloom_level"},
                    {"sTitle": "Delete", "mData": "delete"}
                ], "aaData": msg["tlo_list"],
                "sPaginationType": "bootstrap"});
    if (msg["topic_state"]["state_id"] == 5 || msg["topic_state"]["state_id"] == 4 || msg["topic_state"]["state_id"] == 2) {
        var boolean_value = true;
    } else {
        var boolean_value = false;
    }
    $('#publish').attr("disabled", boolean_value);
    $('#bulk_edit').attr("disabled", boolean_value);

}

function select_value() {
    $.ajax({type: "POST",
        data: post_data,
        success: function (msg) {

        }
    });
}

var topic_id;
var table_row;
$('.get_tlo_id').live("click", function () {
    tlo_id = $(this).attr('id');
    table_row = $(this).closest("tr").get(0);
});

function delete_tlo() {
    var delete_tlo_path = 'curriculum/tlo/delete_tlo';
    $.ajax({type: "POST",
        url: base_url + delete_tlo_path + "/" + tlo_id,
        success: function (msg) {
            var oTable = $('#example').dataTable();
            oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
        }
    });
}

$('.delbutton').click(function (e) {
    e.preventDefault();
    var oTable = $('#example').dataTable();
    var row = $(this).closest("tr").get(0);
    oTable.fnDeleteRow(oTable.fnGetPosition(row));
});
//SLO List Script Ends Here.

// SLO Add script starts from here.
function fixIds(elem, cntr) {
    $(elem).find("[id]").add(elem).each(function () {
        this.id = this.id.replace(/\d+$/, "") + cntr;
        this.name = this.id;
    });
}

function show_help() {
    $.ajax({
        url: base_url + 'curriculum/tlo/tlo_help',
        datatype: "JSON",
        success: function (msg) {
            document.getElementById('help1').innerHTML = msg;
        }
    });
}

var cloneCntr = 2;
$("a#add_field").click(function () {
    var table = $("#add_tlo").clone(true, true)
    fixIds(table, cloneCntr);
    table.insertBefore("#insert_before");
    $('#tlo_statement' + cloneCntr).val('');
    $('#action_verb_display_' + cloneCntr).html('Note : Select Bloom\'s Level to view its respective Action Verbs');
    $('#bloom_id_div' + cloneCntr + ' div select').attr('name', 'level_new[]');
    po_counter.push(cloneCntr);
    $('#counter').val(po_counter);
    cloneCntr++;
})

$('.Delete').click(function () {
    rowId = $(this).attr("id").match(/\d+/g);
    if (rowId != 1)
    {
        $(this).parent().parent().parent().parent().parent().parent().remove();
        var replaced_id = $(this).attr('id').replace('remove_field_', '');
        var po_counter_index = $.inArray(parseInt(replaced_id), po_counter);
        po_counter.splice(po_counter_index, 1);
        $('#counter').val(po_counter);
        return false;
    }
});

$(document).ready(function () {
    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");


    $("#tlo_add_form").validate({
        highlight: function (element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function (element) {
            element.text('').addClass('valid')
                    .closest('.control-group').removeClass('error').addClass('success');
        }
    });

    $('.save').on('click', function (event) {
        tinyMCE.triggerSave();
        var str = $('#tlo1').val();
        var str1 = str.replace("<p>", " ");
        var str2 = str1.replace("</p>", " ");
        var res = str.replace('alt=""', 'alt="image"');
        var dataval = res;
        $('#loading').show();

        if (dataval == "") {
            $('#tiny_mce').html("This field is required.");
        } else {
            $('#tiny_mce').html(" ");
        }

        $('#tlo_add_form').validate();
        var flag = $('#tlo_add_form').valid();
        var crclm_id = $('#crclm_id').val();
        var term_id = $('#term_id').val();
        var course_id = $('#course_id').val();
        var topic_id = $('#topic_id').val();
        var bloom_level = $('#bloom_level_1').val();
        var delivery_methods = $('#delivery_methods1').val();
        var delivery_approach = $('#delivery_approach').val();
        var bloom_domain_id = new Array;
        var bloom_level = $("#bloom_level_1").val();
        var bloom_level_1 = $("#bloom_level_2").val();
        var bloom_level_2 = $("#bloom_level_3").val();
        var bloom_field_id = $('#bloom_filed_id').val();        
        bloom_domain_id.push($("#bld_id_1").val());
        bloom_domain_id.push($("#bld_id_2").val());
        bloom_domain_id.push($("#bld_id_3").val());
        var clo_bl_flag = $('#clo_bl_flag').val();
           if(clo_bl_flag == 1){
        var i=1;
     $('input[name="bloom_filed_id[]').each(function() {
                id = '#'+ $(this).val();
               var values = $(id).val();                 
            if(values === null){  
                flag1 = 0; return false;

            }else{
                flag1 = 1 ;  
                $('#error_placeholder_bl'+i).html("");  

            }
            i++;
          });
         }else{

                 flag1 = 1;
         }
        
        if (flag == true && dataval != "" && flag1 ==  1) {
            var post_data = {'tlo': dataval, 'crclm_id': crclm_id, 'term_id': term_id, 'course_id': course_id, 'topic_id': topic_id, 'bloom_level': bloom_level, 'delivery_methods': delivery_methods, 'delivery_approach': delivery_approach, 'bloom_domain_id': bloom_domain_id, 'bloom_level_1': bloom_level_1, 'bloom_level_2': bloom_level_2}
            $.ajax({type: "POST",
                url: base_url + 'curriculum/tlo/tlo_insert_new',
                data: post_data,
                dataType: 'json',
                success: function (data) {
                    post_data = {'crclm_id': crclm_id, 'term_id': term_id, 'course_id': course_id, 'topic_id': topic_id}

                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/tlo/fetch_list_new',
                        data: post_data,
                        dataType: 'json',
                        success: function (msg) {
                            populate_table(msg);
                            $('#tlo_add_form').trigger('reset');
                            $("#bloom_level_1_actionverbs").html("");
                            $("#bloom_level_2_actionverbs").html("");
                            $("#bloom_level_3_actionverbs").html("");
                            $('#bloom_level_1').find('option:selected').prop('selected', false);
                            $('#bloom_level_1').multiselect('rebuild');
                            $('#bloom_level_2').find('option:selected').prop('selected', false);
                            $('#bloom_level_2').multiselect('rebuild');
                            $('#bloom_level_3').find('option:selected').prop('selected', false);
                            $('#bloom_level_3').multiselect('rebuild');
                            $('#loading').hide();
                        }
                    });
                }
            });
        }else {      
            if(clo_bl_flag == 1){

              var j=1;
                $('input[name="bloom_filed_id[]').each(function() {
                      id = '#'+ $(this).val();
                     var values = $(id).val();                        
                  if(values === null){                                  
                      $('#error_placeholder_bl'+j).html("<span style='color:red'> This field is required. </span>");                  
                  }else{
                       $('#error_placeholder_bl'+j).html("");  
                  }
                  j++;
                });      
            }
        }
        $('#loading').hide();
    });
});

$('.submit1').on('click', function () {
    $('#tiny_mce').html(" ");
    $('#bloom_error').html(" ");
});
$('#tlo1').on('click', function () {

})
//To fetch help content related to tlo
$('.show_help').live('click', function () {
    $.ajax({
        url: base_url + 'curriculum/tlo_list/tlo_help',
        datatype: "JSON",
        success: function (msg) {
            $('#help_content').html(msg);
        }
    });
});
//SLO Add script Ends here.

// SLO Lesson Schedule Script functions starts from here //
$(document).ready(function () {
    var cloneCntr = 2;
    var cloneCntr_ques = 2;
    var question_counter = new Array();
    var assignment_question_counter = new Array();
    question_counter.push(1);
    assignment_question_counter.push(1);

    $(".add_assignment_question").click(function () {
        var assignment_question_block1 = '<div class="" id="add_me1' + cloneCntr + '"><div class="control-group input-append">';
        var assignment_question_block2 = '<div class="row-fluid"><div class="span12"><div class="control-group"><label class="control-label" for="assignment_question_1">Assignment:</label><div class="controls"><textarea class="required" style="margin: 0px; width: 787px; height: 40px;" name="assignment_question_' + cloneCntr + '" id ="assignment_question_' + cloneCntr + '"></textarea><a id="remove_field1" class="delete_assignment_question" href="#"><i class="icon-remove pull-right"></i> </a> </div></div></div></div>';
        var assignment_question_block = assignment_question_block2;
        var newAssignQuestion = $(assignment_question_block);
        $('#assignment_question_insert').append(newAssignQuestion);
        assignment_question_counter.push(cloneCntr);
        $('#assignment_counter').val(assignment_question_counter);
        cloneCntr++;
    });

    $('.delete_assignment_question').live('click', function () {
        $(this).parent().parent().parent().parent().remove();
        var replaced_id = $(this).attr('id').replace('question_btn_', '');

        var assignment_question_counter_index = $.inArray(parseInt(replaced_id), assignment_question_counter);
        assignment_question_counter.splice(assignment_question_counter_index, 1);
        $('#assignment_counter').val(assignment_question_counter);
        return false;
    });

    function fixIds(elem, cntr) {
        $(elem).find("[id]").add(elem).each(function () {

            this.id = this.id.replace(/\d+$/, "") + cntr;
            this.name = this.id;
        });
    }

    //Function to insert new textarea for adding program outcomes
    $("#add_question").click(function () {
        var table = $("#question_details").clone(true, true);
        fixIds(table, cloneCntr_ques);
        table.insertBefore("#add_before");
        $('#review_question_' + cloneCntr_ques).val('');
        $('#tlo_ids_' + cloneCntr_ques).val('');
        question_counter.push(cloneCntr_ques);
        $('#questions_counter').val(question_counter);
        cloneCntr_ques++;
    });

    //Function to delete unwanted textarea
    $('.Delete_question').live('click', function () {
        var question_count = $('#questions_counter').val();
        var question_count_one = $('#add_more_book_counter').val();
        rowId = $(this).attr("id").match(/\d+/g);
        if (rowId != 1) {
            $(this).parent().parent().parent().parent().remove();
            $('#add_more_book_counter').val(question_count_one - 1);
            var replaced_id = $(this).attr('id').replace('remove_field', '');
            var question_counter_index = $.inArray(parseInt(replaced_id), question_counter);
            question_counter.splice(question_counter_index, 1);
            $('#questions_counter').val(question_counter);
            return false;
        }

    });

});

// Form validation rules are defined & checked before form is submitted to controller.	

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\,\!\.\%\&\-\s\:\;\(\/)']+$/i.test(value);
}, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");


$("#lesson_schedule_add_form").validate({
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

$.validator.addMethod('noSpecialChars', function (value, element) {
    return this.optional(element) || /^(^[A-Za-z\s]+([\._]?[A-Za-z\s\?\.\,\:]+))+$/.test(value);
},
        'Verify you have a valid entry.'
        );

$.validator.addMethod('noSpecialChars1', function (value, element) {
    return this.optional(element) || /^(^[A-Za-z0-9]+([\._]?[A-Za-z]+))+$/.test(value);
},
        'Verify you have a valid entry.'
        );

$.validator.addMethod("onlyDigit", function (value, element) {
    return this.optional(element) || /^[0-9\.\_]+$/i.test(value);
}, "This field must contain only Numbers.");

$('.add_details').on('click', function (event) {
    $('#lesson_schedule_add_form').validate();
    // adding rules for inputs with class 'comment'
    $(".portion_per_hour").each(function () {
        $(this).rules("add",
                {
                    noSpecialChars: true
                });
    });
    $('.review_question').each(function () {
        $(this).rules("add",
                {
                    noSpecialChars: true
                });
    });
    $('.assignment_question').each(function () {
        $(this).rules("add",
                {
                    noSpecialChars: true
                });
    });

});
// function to move lesson schedule page

$('.review_question').on('click', function () {
    var crclm_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var crs_id = $('#course').val();
    var topic_id = $('#topic').val();
    if (crclm_id != '' && term_id != '' && crs_id != '' && topic_id != '') {
        window.location = base_url + 'curriculum/tlo_list/add_lesson_schedule/' + crclm_id + '/' + term_id + '/' + crs_id + '/' + topic_id;
    } else {
        $('#myModal_alert_select_drop_down').modal('show');
    }
});

// SLO status roll back
$('#tlo_state_roll_back').on('click', function () {
    var crclm_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var course_id = $('#course').val();
    var topic_id = $('#topic').val();

    if (crclm_id != '' && term_id != '' && course_id != '' && topic_id != '') {
        $('#roll_back_crclm_id').val(crclm_id);
        $('#roll_back_term_id').val(term_id);
        $('#roll_back_course_id').val(course_id);
        $('#roll_back_topic_id').val(topic_id);
        $('#tlo_roll_back').modal('show');

    } else {
        $('#select_warning').modal('show');
    }

});

$('#button_roll_back').on('click', function () {
    var roll_back_crclm_id = $('#roll_back_crclm_id').val();
    var roll_back_term_id = $('#roll_back_term_id').val();
    var roll_back_course_id = $('#roll_back_course_id').val();
    var roll_back_topic_id = $('#roll_back_topic_id').val();

    var post_data = {'crclm_id': roll_back_crclm_id,
        'term_id': roll_back_term_id,
        'course_id': roll_back_course_id,
        'topic_id': roll_back_topic_id}

    $.ajax({type: "POST",
        url: base_url + 'curriculum/tlo/tlo_status_roll_back',
        data: post_data,
        dataType: 'json',
        success: function (msg) {

            if (msg != true) {
                $('#tlo_roll_back_failed').modal('show');

            } else {
                location.reload();
            }

        }
    });

});

/*************************************************************************************************************************************************************/
/**Calling the modal on success**/
function success_modal1(msg) {
    var data_options = '{"text":"Your data has been Deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}
function success_modal(msg) {
    var data_options = '{"text":"Your data has been Updated successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

$('.delete_TLO').on('click', function () {

    var tlo_id = $(this).attr('id');
    var tlo_dm_id = $(this).attr('data-dm');
    post_data = {'tlo_id': tlo_id, 'tlo_dm_id': tlo_dm_id}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/tlo/delete_tlo_new',
        data: post_data,
        dataType: 'json',
        success: function (msg) {
            success_modal();
            location.reload();
        }
    });
});

var crclm_id = $('#crclm_id').val();
var term_id = $('#term_id').val();
var course_id = $('#course_id').val();
var topic_id = $('#topic_id').val();
post_data = {'crclm_id': crclm_id, 'term_id': term_id, 'course_id': course_id, 'topic_id': topic_id}

$.ajax({type: "POST",
    url: base_url + 'curriculum/tlo/fetch_list_new',
    data: post_data,
    dataType: 'json',
    success: populate_table
});


/**To Populate DataTable for tab my_training and development**/
function populate_table(msg) {
    $('#example_view').dataTable().fnDestroy();
    $('#example_view').dataTable(
            {"aSort" : true,
                "sPaginate": true,
                "aoColumns": [
                    {"sTitle": entity_tlo_singular + " Code", "mData": "Tlo_code", "sType": "natural"},
                    {"sTitle": entity_tlo_full, "mData": "tlo_statement"},
                    {"sTitle": "Bloom's Level", "mData": "level"},
                    {"sTitle": "Delivery Method", "mData": "delivery_mtd_name"},
                    {"sTitle": "Delivery Approach", "mData": "delivery_approach"},
                    {"sTitle": "Edit", "mData": "edit"},
                    {"sTitle": "Delete", "mData": "Delete"},
                ],
                "sPaginationType": "bootstrap",
                "aaData": msg,
            });
}

$('#example_view').on('click', '.delete_TLO1', function (e) {
    $('#loading').show();
    var tlo_id = $(this).attr('id');
    var tlo_dm_id = $(this).attr('data-dm');
    $('#myModal5').data('data', tlo_dm_id);
    $('#myModal5').data('id', tlo_id, 'data', tlo_dm_id).modal('show');
    $('#loading').hide();
});

$('#example_view').on('click', '.edit_tlo', function (e) {
    tinymce.remove();
    var tlo_code = $(this).attr('data-tlo_code');
    var tlo_statement = $(this).attr('data-tlo_stmt');
    var delivery_mtd_name = $(this).attr('data-delivery_mtd_name');
    var level = $(this).attr('data-level');
    var crclm_dm_id = $(this).attr('data-crclm_dm_id');
    var tlo_id = $(this).attr('data-tlo_id');
    var tlo_dm_id = $(this).attr('data-dm');
    var data_dlm = $(this).attr('data-dlm');
    var bloom_actionverbs = $(this).attr('data-bloom_actionverbs');
    $('#edit_bloom_level_1').find('option:selected').prop('selected', false);
    $('#edit_bloom_level_1').multiselect('rebuild');
    var bld_id = $("#bld_id_1").val();
    var select = 1;
    fetch_dropdown(tlo_id, bld_id, select);

    $('#edit_bloom_level_2').find('option:selected').prop('selected', false);
    $('#edit_bloom_level_2').multiselect('rebuild');
    var bld_id = $("#bld_id_2").val();
    var select = 2;
    fetch_dropdown(tlo_id, bld_id, select);

    $('#edit_bloom_level_3').find('option:selected').prop('selected', false);
    $('#edit_bloom_level_3').multiselect('rebuild');
    var bld_id = $("#bld_id_3").val();
    var select = 3;
    fetch_dropdown(tlo_id, bld_id, select);

    $('#dlm').val(crclm_dm_id);
    $('#dlm').attr("selected", 'selected');
    $('#bloom_level2').val(level).attr("selected", true);

    $('#tlo_code_edit').val(tlo_code);
    $('#tlo_stmt_edit').val(tlo_statement);
    $('#dmn').val(delivery_mtd_name);
    $('#delivery_approach_edit').val(data_dlm);
    $("#action_verb_display_2").html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Bloom\'s Level Action Verbs : </b>' + bloom_actionverbs);

    $('#myModal_edit').data('data', tlo_dm_id);
    $('#myModal_edit').data('data1', data_dlm);
    $('#myModal_edit').data('id', tlo_id).modal('show');

    tiny_init();
});

/**Function to Delete the my_training Data**/
$('#btnYest').on('click', function () {
    $('#loading').show();
    var tlo_id = $('#myModal5').data('id');
    var tlo_dm_id = $('#myModal5').data('data');
    post_data = {'tlo_id': tlo_id, 'tlo_dm_id': tlo_dm_id}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/tlo/delete_tlo_new',
        data: post_data,
        dataType: 'json',
        success: function (msg) {
            success_modal1();
            var crclm_id = $('#crclm_id').val();
            var term_id = $('#term_id').val();
            var course_id = $('#course_id').val();
            var topic_id = $('#topic_id').val();
            post_data = {'crclm_id': crclm_id, 'term_id': term_id, 'course_id': course_id, 'topic_id': topic_id}

            $.ajax({type: "POST",
                url: base_url + 'curriculum/tlo/fetch_list_new',
                data: post_data,
                dataType: 'json',
                success: function (msg) {
                    $('#loading').hide();
                    populate_table(msg);
                }
            });
        }
    });
});

/**Function to Delete the my_training Data**/
$('.save_edit_tlo').on('click', function () {
    $('#loading').show();
    var dataval_e = document.getElementById('tlo_stmt_edit');
    tinyMCE.triggerSave();
    var str = tinymce.get("tlo_stmt_edit").getContent();
    var str1 = str.replace("<p>", " ");
    var str2 = str1.replace("</p>", " ");
    var res = str2.replace('alt=""', 'alt="image"');
    var dataval = res;
    var dataval2 = document.getElementById('delivery_methods2');
    var clo_bl_flag = $('#clo_bl_flag').val(); 
    if (dataval_e || dataval2) {
        if (dataval == "") {
            $('#tiny_mce_edit').html("This field is required.");
        } else {
            $('#tiny_mce_edit').html(" ");
        }
        if (dataval == "") {
            dataval_e.focus();
            dataval_e.style.borderColor = "#FF0000";
            $('#loading').hide();
            return false;
        } else {
        }
        dataval_e.style.borderColor = "#1eb486";
    }
    var tlo_code = $('#tlo_code_edit').val();
    var tlo_id = $('#myModal_edit').data('id');
    // added by Bhagya
    tinyMCE.triggerSave();
    var str = tinymce.get("tlo_stmt_edit").getContent();
/*     var str1 = str.replace("<p>", " ");
    var tlo_description = str1.replace("</p>", " "); */
	   var tlo_description = str;

    var tlo_dm_id = $('#myModal_edit').data('data');
    var delivery_mtd_id = $('#dlm').val();
    var level = $('#edit_bloom_level_1').val();
    var level_1 = $('#edit_bloom_level_2').val();
    var level_2 = $('#edit_bloom_level_3').val();
    var dlma = $('#delivery_approach_edit').val();
    var bloom_domain_id = new Array;
    bloom_domain_id.push($("#bld_id_1").val());
    bloom_domain_id.push($("#bld_id_2").val());
    bloom_domain_id.push($("#bld_id_3").val());
    if(clo_bl_flag == 1){
        var i=1;
    
     $('input[name="bloom_filed_edit_id[]').each(function() {
           id = '#'+ $(this).val();
                var values = $(id).val();                 
             if(values === null){  
                      $('#error_placeholder_edit_bl'+i).html(" ");  
                 flag1 = 0; return false;

             }else{
                 flag1 = 1 ;    
                  $('#error_placeholder_edit_bl'+i).html(" ");  
             }
             i++;
           });
          }else{

                  flag1 = 1;
                   $('#error_placeholder_edit_bl'+i).html(" ");  
          }    
    if (flag1 == 1) {
    post_data = {'tlo_code': tlo_code, 'tlo_description': tlo_description, 'tlo_id': tlo_id, 'delivery_mtd_id': delivery_mtd_id, 'tlo_dm_id': tlo_dm_id, 'dlma': dlma, 'level': level, 'bloom_domain_id': bloom_domain_id, 'level_1': level_1, 'level_2': level_2}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/tlo/tlo_edit_new',
        data: post_data,
        dataType: 'json',
        success: function (msg) {
            success_modal();
            $('#myModal_edit').modal('hide');
            $('#loading').hide();
            var crclm_id = $('#crclm_id').val();
            var term_id = $('#term_id').val();
            var course_id = $('#course_id').val();
            var topic_id = $('#topic_id').val();
            post_data = {'crclm_id': crclm_id, 'term_id': term_id, 'course_id': course_id, 'topic_id': topic_id}

            $.ajax({type: "POST",
                url: base_url + 'curriculum/tlo/fetch_list_new',
                data: post_data,
                dataType: 'json',
                success: populate_table
            });
        }
    });
   }else {
        $('#loading').hide();
        if(clo_bl_flag == 1){            
          var j=1;
            $('input[name="bloom_filed_edit_id[]').each(function() {
                  id = '#'+ $(this).val();
                 var values = $(id).val();                          
              if(values === null){                     
                  $('#error_placeholder_edit_bl'+j).html("<span style='color:red'> This field is required. </span>");                  
              }else{
                   $('#error_placeholder_edit_bl'+j).html(" ");  
              }
              j++;
            });      
        }
  }
});

//Tiny MCE script
tinymce.init({
    mode: "specific_textareas",
    editor_selector: "tlo",
    relative_urls: false,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste jbimages",
    ],
    paste_data_images: true,
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
});

function tiny_init() {

    tinymce.init({
        mode: "specific_textareas",
        editor_selector: "tlo",
        relative_urls: false,
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste jbimages",
        ],
        paste_data_images: true,
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
    });
}

//function to select mapped bloom's level 
function fetch_dropdown(tlo_id, bld_id, select) {
    $.ajax({
        type: 'POST',
        url: base_url + 'curriculum/tlo/mapped_bloom_levels',
        data: {'id': tlo_id, 'bld_id': bld_id},
        dataType: 'json',
        success: function (data) {
            var size = data.mapped_bloom_levels.length;
            var count = 0;

            if (select == 1) {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'curriculum/tlo/fetch_bloom_level_data',
                    data: {'id': tlo_id, 'bld_id': bld_id},
                    success: function (data) {
                        $("#edit_bloom_level_1_actionverbs").html(data);
                    }
                });
                for (count = 0; count < size; count++) {
                    $("#edit_bloom_level_1 option[value='" + data.mapped_bloom_levels[count]['bloom_id'] + "']").attr("selected", "selected");
                }
                $('#edit_bloom_level_1').multiselect('rebuild');
            }

            if (select == 2) {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'curriculum/tlo/fetch_bloom_level_data',
                    data: {'id': tlo_id, 'bld_id': bld_id},
                    success: function (data) {
                        $("#edit_bloom_level_2_actionverbs").html(data)
                    }
                });
                for (count = 0; count < size; count++) {
                    $("#edit_bloom_level_2 option[value='" + data.mapped_bloom_levels[count]['bloom_id'] + "']").attr("selected", "selected");
                }
                $('#edit_bloom_level_2').multiselect('rebuild');
            }

            if (select == 3) {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'curriculum/tlo/fetch_bloom_level_data',
                    data: {'id': tlo_id, 'bld_id': bld_id},
                    success: function (data) {
                        $("#edit_bloom_level_3_actionverbs").html(data)
                    }
                });
                for (count = 0; count < size; count++) {
                    $("#edit_bloom_level_3 option[value='" + data.mapped_bloom_levels[count]['bloom_id'] + "']").attr("selected", "selected");
                }
                $('#edit_bloom_level_3').multiselect('rebuild');
            }
        }
    });
}
