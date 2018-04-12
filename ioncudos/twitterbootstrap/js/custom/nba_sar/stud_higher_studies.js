
//stud_higher_studies.js

var base_url = $('#get_base_url').val();

//validate form fields 
$("#higher_studies_form").validate({
    errorClass: "help-inline font_color",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('success');
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

//function to display students higher studies details list
function display_higher_studies_modal() {
    var pgm_id = $.cookie('stud_perm_program');
    var crclm_id = $.cookie('stud_perm_curriculum');
    var post_data = {
        'pgm_id': pgm_id,
        'crclm_id': crclm_id
    }
    $.ajax({type: 'POST',
        url: base_url + 'nba_sar/curriculum_student_info/stud_higher_studies_details',
        data: post_data,
        dataType: 'json',
        success: function (data) {
            populate_table(data);
        }
    });
}

//function to populate table with students higher studies details. 
function populate_table(data) {
    $('#example_1').dataTable().fnDestroy();
    $('#example_1').dataTable(
            {"aoColumns": [
                    {"sTitle": "Sl No.", "mData": "sl_no"},
                    {"sTitle": "Entrance Exam", "mData": "entrance_exam"},
                    {"sTitle": "Category", "mData": "caste_id"},
                    {"sTitle": "Gender", "mData": "gender_id"},
                    {"sTitle": "No. of Students", "mData": "num_stud"},
                    {"sTitle": "Opening - Closing Score / Rank", "mData": "score"},
                    {"sTitle": "Edit", "mData": "edit"},
                    {"sTitle": "Delete", "mData": "delete"}
                ], "aaData": data,
                "sPaginationType": "bootstrap",
                "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                    $('td:eq(0)', nRow).css("text-align", "right");
                    $('td:eq(4)', nRow).css("text-align", "right");
                    $('td:eq(5)', nRow).css("text-align", "right");
                    return nRow;
                },
            });
}

//function to check whether given data exists or not and to save the given data. 
$("#submit_higher_studies").click(function () {
    var flag = $("#higher_studies_form").valid();
    var pgm_id = $.cookie('stud_perm_program');
    var crclm_id = $.cookie('stud_perm_curriculum');
    var ent_exam = $("#ent_exam_higher_studies").val();
    var caste = $("#caste_higher_studies").val();
    var gender = $("#gender_higher_studies").val();
    var num_stud = $("#num_stud").val();
	var opening_score = $("#opening_score").val();	
	var closing_score = $("#closing_score").val();

    if (flag) {
        $('#loading').show();
        var post_data = {
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'ent_exam': ent_exam,
            'caste': caste,
            'gender': gender,
            'num_stud': num_stud,
			'opening_score':opening_score,
			'closing_score':closing_score			
        }
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/curriculum_student_info/check_higher_studies_save',
            data: post_data,
            datatype: "json",
            success: function (data) {
                if (data == 1) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'nba_sar/curriculum_student_info/insert_higher_studies_data',
                        data: post_data,
                        datatype: "json",
                        success: function (data) {
                            display_higher_studies_modal();
                            $("#reset_higher_studies").trigger("click");
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

//function to load data to allow user to edit.
$('#example_1').on('click', '.edit_higher_studies', function (e) {
    $('#loading').show();
    $("#update_higher_studies").show();
    $("#submit_higher_studies").hide();
    $("#higher_studies_heading").show();
    var validator = $('#higher_studies_form').validate();
    validator.resetForm();
    $("#higher_studies_edit").val($(this).attr('data-id'));
    $("#ent_exam_higher_studies").val($(this).attr('data-entrance_exam'));
    $("#caste_higher_studies").val($(this).attr('data-caste'));
    $("#gender_higher_studies").val($(this).attr('data-gender'));
    $("#num_stud").val($(this).attr('data-num_stud'));
	$("#opening_score").val($(this).attr('data-opening_score'));
	$("#closing_score").val($(this).attr('data-closing_score'));
	
    $('#loading').hide();
});

//function to reset the view.
$("#reset_higher_studies").click(function () {
    $("#submit_higher_studies").show();
    $("#update_higher_studies").hide();
    $("#higher_studies_heading").hide();
    var validator = $('#higher_studies_form').validate();
    validator.resetForm();
});

//function to check whether given data exists or not and to update the given data. 
$("#update_higher_studies").click(function () {
    var flag = $("#higher_studies_form").valid();
    var higher_studies_edit = $("#higher_studies_edit").val();
    var pgm_id = $.cookie('stud_perm_program');
    var crclm_id = $.cookie('stud_perm_curriculum');
    var ent_exam = $("#ent_exam_higher_studies").val();
    var caste = $("#caste_higher_studies").val();
    var gender = $("#gender_higher_studies").val();
    var num_stud = $("#num_stud").val();
	var opening_score = $("#opening_score").val();	
	var closing_score = $("#closing_score").val();
    if (flag) {
        $('#loading').show();
        var post_data = {
            'higher_studies_edit': higher_studies_edit,
            'pgm_id': pgm_id,
            'crclm_id': crclm_id,
            'ent_exam': ent_exam,
            'caste': caste,
            'gender': gender,
            'num_stud': num_stud,
			'opening_score':opening_score,
			'closing_score':closing_score	
        }
        $.ajax({
            type: "POST",
            url: base_url + 'nba_sar/curriculum_student_info/check_higher_studies_edit',
            data: post_data,
            datatype: "json",
            success: function (data) {

                if (data == 1) {
                    $.ajax({
                        type: "POST",
                        url: base_url + 'nba_sar/curriculum_student_info/update_higher_studies_data',
                        data: post_data,
                        datatype: "json",
                        success: function (data) {
                            display_higher_studies_modal();
                            $("#reset_higher_studies").trigger("click");
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
function store_higher_studies_id(higher_studies_id) {
    $("#higher_studies_del").val(higher_studies_id);
}

//function to delete students admitted details.
$("#higher_studies_delete_selected").click(function () {
    var higher_studies_id = $("#higher_studies_del").val();
    $('#loading').show();
    var post_data = {
        'higher_studies_id': higher_studies_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'nba_sar/curriculum_student_info/higher_studies_details_delete',
        data: post_data,
        datatype: "json",
        success: function (data) {
            display_higher_studies_modal();
            $('#delete_higher_studies_details').modal('hide');
            $('#loading').hide();

            if (data == 1) {
                delete_modal_higher_studies();
            }
        }
    });
});

//function to close delete modal.
$("#close_higher_studies").click(function () {
    $("#delete_higher_studies_details").modal('hide');
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
function delete_modal_higher_studies(msg) {
    var data_options = '{"text":"Your data has been deleted successfully.","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//function to give warning message if data is already exits.
function warning_modal(msg) {
    var data_options = '{"text":"Higher Study details already exists.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
    var options = $.parseJSON(data_options);
    noty(options);
}

//file ends here