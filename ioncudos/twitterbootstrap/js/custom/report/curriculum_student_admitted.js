
//curriculum_student_admitted.js

var base_url = $('#get_base_url').val();

//Function to check input field should contain only numbers.
$.validator.addMethod("numbers", function (value, element) {
    return this.optional(element) || /^[0-9]+$/i.test(value);
}, "Field must contain only numbers.");

//validate form fields 
$("#add_form").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).parent().parent().addClass('error');
        //$(element).css({"color": "red", "border-color": "red"});
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
        //$(element).css({"color": "green", "border-color": "green"});
    }
});

//function to check whether given data exists or not and to save the given data. 
$("#submit").click(function () {
    var flag = $("#add_form").valid();
    var pgm_id = $.cookie('stud_perm_program');
    var crclm_id = $.cookie('stud_perm_curriculum');
    var ent_exam = $("#ent_exam").val();
    var caste = $("#caste").val();
    var gender = $("#gender").val();
    var intake = $("#num_intake").val();
    var rank_from = $("#add_rank_from").val();
    var rank_to = $("#add_rank_to").val();
    if (flag) {
        $('#loading').show();
        var post_data = {
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'ent_exam': ent_exam,
            'caste': caste,
            'gender': gender,
            'intake': intake,
            'rank_from': rank_from,
            'rank_to': rank_to
        }
        $.ajax({
            type: "POST",
            url: base_url + 'report/curriculum_student_info/check_insert_data',
            data: post_data,
            datatype: "json",
            success: function (data) {
                if (data == 1) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'report/curriculum_student_info/insert_student_admitted_data',
                        data: post_data,
                        datatype: "json",
                        success: function (data) {
                            display_admitted_modal();
                            $("#reset").trigger("click");
                            $('#loading').hide();
                            if (data == 1) {
                                success_modal();
                            }
                        }
                    });
                } else {
                    $('#loading').hide();
                    warning_modal();
                }
            }
        });
    }
});

//function to display students admitted details list
function display_admitted_modal() {
    var pgm_id = $.cookie('stud_perm_program');
    var crclm_id = $.cookie('stud_perm_curriculum');
    var post_data = {
        'pgm_id': pgm_id,
        'crclm_id': crclm_id
    }
    $.ajax({type: 'POST',
        url: base_url + 'report/curriculum_student_info/stud_admitted_details',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            populateTable(data);
        }
    });
}

//function to populate table with students admitted details. 
function populateTable(data) {
    $('#example1').dataTable().fnDestroy();
    $('#example1').dataTable(
            {"aoColumns": [
                    {"sTitle": "Sl No.", "mData": "sl_no"},
                    {"sTitle": "Entrance Exam", "mData": "entrance_exam"},
                    {"sTitle": "Category", "mData": "caste_id"},
                    {"sTitle": "Gender", "mData": "gender_id"},
                    {"sTitle": "Intake", "mData": "num_intake"},
                    {"sTitle": "Rank range", "mData": "rank_range"},
                    {"sTitle": "Edit", "mData": "edit"},
                    {"sTitle": "Delete", "mData": "delete"}
                ], "aaData": data,
                "sPaginationType": "bootstrap"
            });
}

//function to load data to allow user to edit.
$('#example1').on('click', '.edit_stud_admitted_details', function (e) {
    $('#loading').show();
    $("#update").show();
    $("#submit").hide();
    $("#edit_heading").show();
    var validator = $('#add_form').validate();
    validator.resetForm();
    $("#student_admitted_id").val($(this).attr('data-id'));
    $("#ent_exam").val($(this).attr('data-entrance_exam'));
    $("#caste").val($(this).attr('data-caste'));
    $("#gender").val($(this).attr('data-gender'));
    $("#num_intake").val($(this).attr('data-intake'));
    $("#add_rank_from").val($(this).attr('data-from'));
    $("#add_rank_to").val($(this).attr('data-to'));
    $('#loading').hide();
});

//function to reset the view.
$("#reset").click(function () {
    $("#submit").show();
    $("#update").hide();
    $("#edit_heading").hide();
    var validator = $('#add_form').validate();
    validator.resetForm();
});

//function to check whether given data exists or not and to update the given data. 
$("#update").click(function () {
    var flag = $("#add_form").valid();
    var student_admitted_id = $("#student_admitted_id").val();
    var pgm_id = $.cookie('stud_perm_program');
    var crclm_id = $.cookie('stud_perm_curriculum');
    var ent_exam = $("#ent_exam").val();
    var caste = $("#caste").val();
    var gender = $("#gender").val();
    var intake = $("#num_intake").val();
    var rank_from = $("#add_rank_from").val();
    var rank_to = $("#add_rank_to").val();
    if (flag) {
        $('#loading').show();
        var post_data = {
            'student_admitted_id': student_admitted_id,
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'ent_exam': ent_exam,
            'caste': caste,
            'gender': gender,
            'intake': intake,
            'rank_from': rank_from,
            'rank_to': rank_to
        }
        $.ajax({
            type: "POST",
            url: base_url + 'report/curriculum_student_info/check_update_data',
            data: post_data,
            datatype: "json",
            success: function (data) {
                if (data == 1) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'report/curriculum_student_info/update_student_admitted_data',
                        data: post_data,
                        datatype: "json",
                        success: function (data) {
                            display_admitted_modal();
                            $("#reset").trigger("click");
                            $('#loading').hide();
                            if (data == 1) {
                                update_modal();
                            }
                        }
                    });
                } else {
                    $('#loading').hide();
                    warning_modal();
                }
            }
        });
    }
});

//function to store delte id.
function store_id(admitted_id) {
    $("#delete_id").val(admitted_id);
}

//function to delete students admitted details.
$("#delete_selected").click(function () {
    var delete_id = $("#delete_id").val();
    $('#loading').show();
    var post_data = {
        'admitted_id': delete_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'report/curriculum_student_info/student_admitted_details_delete',
        data: post_data,
        datatype: "json",
        success: function (data) {
            display_admitted_modal();
            $('#delete_admitted_details').modal('hide');
            $('#loading').hide();
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

//function to give warning message if data is already exits.
function warning_modal(msg) {
    var data_options = '{"text":"Intake data already exists.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to close delete modal.
$("#close").click(function () {
    $("#delete_admitted_details").modal('hide');
});