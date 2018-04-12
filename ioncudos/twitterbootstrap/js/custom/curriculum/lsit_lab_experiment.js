
//Lab Experiment

// List page related .js
var base_url = $('#get_base_url').val();
var expt_id;
var table_row;

var course_id = document.getElementById('course');

$('.get_topic_id').live("click", function () {
    expt_id = $(this).attr('id');
    table_row = $(this).closest("tr").get(0);
});

function delete_experiment() {
    $('#loading').show();
    $.ajax({type: "POST",
        url: base_url + 'curriculum/lab_experiment/delete_experiment' + '/' + expt_id,
        success: function (msg) {
            var oTable = $('#example').dataTable();
            oTable.fnDeleteRow(oTable.fnGetPosition(table_row));
            $('#loading').hide();
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
    var term_list_path = base_url + 'curriculum/lab_experiment/select_term';
    var data_val = $('#curriculum').val();
    var post_data = {
        'crclm_id': data_val
    }
    $.ajax({type: "POST",
        url: term_list_path,
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
    var select_course_path = base_url + 'curriculum/lab_experiment/select_course';
    var data_val = $('#term').val();

    var post_data = {
        'term_id': data_val
    }

    $.ajax({type: "POST",
        url: select_course_path,
        data: post_data,
        success: function (msg) {
            $('#course').html(msg);
            if ($.cookie('remember_category') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#course option[value="' + $.cookie('remember_category') + '"]').prop('selected', true);
                select_category();
            }
        }
    });
}

//Function to fetch the grid details
function select_category() {
    $.cookie('remember_category', $('#course option:selected').val(), {expires: 90, path: '/'});
    var category_path = base_url + 'curriculum/lab_experiment/show_category';
    var crclm_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var course_id = $('#course').val();
    var category = $('#category').val();
    $('#curriculum_id').val(crclm_id);
    $('#term_id').val(term_id);
    $('#course_id').val(course_id);
    $('#category').val(category);
    var post_data = {
        'crclm_id': crclm_id,
    };
    $.ajax({type: "POST",
        url: category_path,
        data: post_data,
        success: function (msg) {
            $('#category').html(msg);
            if ($.cookie('remember_selected_value') != null) {
                // set the option to selected that corresponds to what the cookie is set to
                $('#category option[value="' + $.cookie('remember_selected_value') + '"]').prop('selected', true);
                GetSelectedValue();
            }
            //GetSelectedValue();
        }
    });

}

function GetSelectedValue() {
    $.cookie('remember_selected_value', $('#category option:selected').val(), {expires: 90, path: '/'});
    var curriculum_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var course_id = $('#course').val();
    var category_id = $('#category').val();

    var expt_path = base_url + 'curriculum/lab_experiment/category_wise_show_topic';
    var post_data = {
        'crclm_id': curriculum_id,
        'term_id': term_id,
        'course_id': course_id,
        'category_id': category_id
    };
    $.ajax(
            {type: "POST",
                url: expt_path,
                data: post_data,
                dataType: 'json',
                success: populate_table
            });
}

function populate_table(msg) {
    var m = 'd';
    $('#example').dataTable().fnDestroy();
    $('#example').dataTable({
        "aoColumns": [
            {"sTitle": "Expt. / Job No.", "sClass": "", "mData": "topic_title"},
            {"sTitle": "Experiment / Job Details", "sClass": "", "mData": "topic_content"},
            {"sTitle": "No. of Session/s per batch (estimate)", "sClass": "", "mData": "num_of_sessions"},
            {"sTitle": "Marks / Experiment", "sClass": "", "mData": "marks_expt"},
            {"sTitle": "Correlation of Experiment with theory", "sClass": "", "mData": "correlation_with_theory"},
            {"sTitle": "Edit", "sClass": "right", "mData": "edit_topic"},
            {"sTitle": "Delete", "sClass": "center", "mData": "delete_topic"},
            {"sTitle": "Add / Edit " + entity_tlo + "", "sClass": "center", "mData": "tlo_add"},
            {"sTitle": "View "+ entity_tlo + "", "sClass": "center", "mData": "view_tlo"},
            {"sTitle": "Manage Lesson Schedule", "sClass": "center", "mData": "Lesson_Schedule"},
            {"sTitle": entity_tlo + " to CO Mapping", "sClass": "center", "mData": "Proceed"}
        ], "aaData": msg["topic_data"],
        "sPaginationType": "bootstrap"});
}

$('#example').on('click', '.proceed_to_mapping', function (e) {
    var curriculum_id = $(this).attr('data-crclm_id');
    var term_id = $(this).attr('data-term_id');
    var topic_id = $(this).attr('data-topic_id');
    var course_id = $(this).attr('data-course_id');
    $("#curriculum_edit").val(curriculum_id);
    $("#term_edit").val(term_id);
    $("#topic_edit").val(topic_id);
    $("#course_edit").val(course_id);

    $('#Warning_modal').modal('show');
    //$(location).attr('href','./tlo_clo_map/map_tlo_clo/'+curriculum_id+'/'+term_id+'/'+course_id+'/'+topic_id); 
});

$('#proceed_tlo_co').on('click', function () {
    var curriculum_id = document.getElementById('curriculum_edit').value;
    var term_id = document.getElementById('term_edit').value;
    var topic_id = document.getElementById('topic_edit').value;
    var course_id = document.getElementById('course_edit').value;

    var post_data = {'topic_id': topic_id}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/topic/search_topics',
        data: post_data,
        dataType: "JSON",
        success: function (result) {
            if (result['count_ls'].length != 0 || result['topic_state']['state_id'] > 1) {
                if (result['topic_state']['state_id'] == 1 || result['topic_state']['state_id'] == 2) {
                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/topic/proceed_tlo_co',
                        data: post_data,
                        dataType: "JSON",
                        success: function (result) {}});
                }
                $(location).attr('href', './tlo_clo_map/map_tlo_clo/' + curriculum_id + '/' + term_id + '/' + course_id + '/' + topic_id + '/' + 1);
            } else {
                $('#double_confirm').modal('show');
            }
        }
    });
});
$('#proceed_tlo_co_confirm').on('click', function () {
    var topic_id = $('#topic_edit').val();
    var curriculum_id = document.getElementById('curriculum_edit').value;
    var term_id = document.getElementById('term_edit').value;
    var course_id = document.getElementById('course_edit').value;
    var post_data = {'topic_id': topic_id}
    $.ajax({type: "POST",
        url: base_url + 'curriculum/topic/proceed_tlo_co',
        data: post_data,
        dataType: "JSON",
        success: function (result) {
            $.ajax({type: "POST",
                url: base_url + 'curriculum/topic/search_topics',
                data: post_data,
                dataType: "JSON",
                success: function (result) {
                    if (result['count_ls'].length != 0 || result['topic_state']['state_id'] > 1) {
                        $(location).attr('href', './tlo_clo_map/map_tlo_clo/' + curriculum_id + '/' + term_id + '/' + course_id + '/' + topic_id + '/' + 1);
                    }
                }
            });
        }
    });
});

//Function to fetch tlo details of corresponding experiments
$('.get_tlo_details').live('click', function (e) {
    e.preventDefault();
    data_rowId = $(this).attr('id');
    var category_id = $('#category').val();
    var post_data = {
        'expt_id': data_rowId,
        'category_id': category_id
    }
    $.ajax({type: "POST",
        url: base_url + 'curriculum/lab_experiment/get_tlo_details',
        data: post_data,
        datatype: "JSON",
        success: function (msg) {
            document.getElementById('expt_tlo_list').innerHTML = msg;
        }
    });
});

function form_submit() {
    var crclm_val = document.getElementById('curriculum').value;
    var term_val = document.getElementById('term').value;
    var course_val = document.getElementById('course').value;
    var category_val = document.getElementById('category').value;
    $('#loading').show();
    if (crclm_val && term_val && course_val && category_val) {
        $('#list_form_id').submit();
        $('#loading').hide();
    } else {
        $('#myModal_submit').modal('show');
        $('#loading').hide();
    }
}

//lab experiment related books
$('.lab_books').on('click', function () {
    var crclm_id = $('#curriculum').val();
    var term_id = $('#term').val();
    var crs_id = $('#course').val();
    var ref_12 = 'ref_12';

    if (crclm_id && term_id && crs_id) {
        var post_data = {
            'crclm_id': crclm_id,
            'term_id': term_id,
            'crs_id': crs_id
        }

        //redirect to topics add / edit books page to add text books
        window.location = base_url + 'curriculum/topic/add_books_evaluation/' + crclm_id + '/' + term_id + '/' + crs_id + '/' + ref_12 + '/' + 1;
    } else {
        $('#myModal_submit').modal('show');
    }
});

