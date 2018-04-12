//Import student data
var base_url = $('#get_base_url').val(); //get base url

var download_file = $('#download_file').val();
var section_name = $('#section_name').val();
function enable_dissable_link() {
    var download_file = $('#download_file').val();
    var section_name = $('#section_name').val();
    if (section_name == "") {
        0

        $('#download_file').bind('click', false);
        $('#download_file').attr("href", "");
        $('#download_file').attr("target", "");
        $('#download_file').attr("title", "Select Section before downloading Template.");
    } else {
        //alert(section_name);
        //var section_name_data = $('#section_name').text();
        var section_name_data = $('#section_name :selected').text();
        var url_address = $('#url_address').val();
        $('#section_name_data').val(section_name_data);
        url_address = url_address + '?section=' + section_name_data;
        $('#download_file').unbind('click', false);
        $('#download_file').attr("href", url_address);
        $('#download_file').attr("target", "_blank");
        $('#download_file').attr("title", "Student Stakeholder Template.");
        $('#section_id').val($('#curriculum_name').val());
        $('#file_uploader').prop('disabled', false);
    }
}
enable_dissable_link();
$('#section_name').on('change', function () {
    enable_dissable_link();
});
$(document).ready(function () {
    $('#discard_dup').hide();
    $('#discard_dup').css('visibility', 'hidden');
    $('#update_dup_data').hide();
    $('#update_dup_data').css('visibility', 'hidden');
    getDepartmentList();
    $('#file_uploader').prop('disabled', true);

});

$('#dept_name').live('change', function () {
    var dept_id = $('#dept_name').val();
    var post_data = {
        'dept_id': dept_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/loadProgramList',
        data: post_data,
        success: function (pgm_list) {
            $('#program_name').html(pgm_list);
            $('#dept_id').val($('#dept_name').val());
        }
    });
});
$('#program_name').live('change', function () {
    var pgm_id = $('#program_name').val();
    var post_data = {
        'pgm_id': pgm_id,
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/loadCurriculumList',
        data: post_data,
        success: function (msg) {
            $('#curriculum_name').html(msg);
            $('#pgm_id').val($('#program_name').val());
        }
    });
});
$('#curriculum_name').live('change', function () {
    var crclm_id = $('#curriculum_name').val();
    $('#crclm_id').val($('#curriculum_name').val());
    var post_data = {
        'crclm_id': crclm_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/loadSectionList',
        data: post_data,
        success: function (msg) {
            $('#section_name').html(msg);
            $('#pgm_id').val($('#program_name').val());
        }
    });

});

$('#curriculum_name').live('change', function () {
    $('#section_id').val($('#curriculum_name').val());


});

function getDepartmentList() {
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/loadDepartmentList',
        data: '',
        success: function (dept_list) {
            $('#dept_name').html(dept_list);
        }
    });
}

$('#file_uploader').click(function () {
    if ($('#Filedata').val()) {
        $('#Filedata').val('');
    }
    $('#Filedata').click();
});

$('#Filedata').live('change', function () {
    $('#student_data').empty();
    $('#student_data_upload_form').validate({
        rules: {
            'dept_name': "required",
            'program_name': "required",
            'curriculum_name': "required",
        },
        messages: {
            'dept_name': {
                required: "Department is required"
            },
            'program_name': {
                required: "Program is required"
            },
            'curriculum_name': {
                required: "Curriculum is required"
            },
        }
    });
    $('#student_data_upload_form').valid();
    $('#student_data_upload_form').submit();
});

$('#student_data_upload_form').live('submit', function (e) {
    e.preventDefault();
    var form_data = new FormData(this);
    var post_url = "";
    if ($('#import_type').val() == 'excel') {
        post_url = base_url + 'survey/import_student_data/excel_to_database';
    } else {
        post_url = base_url + 'survey/import_student_data/to_database';
    }
    $('#loading').show();
    $.ajax({
        type: "POST",
        url: post_url,
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        success: function (msg) {
            $('#loading').hide();
            if ($.trim(msg) == '3') {
                $('#invalid_file').modal('show');
            } else if ($.trim(msg) == '4') {
                $('#empty_file').modal('show');
            } else {
                $('#student_data').append(msg);
            }

        }
    });
});

function insert_into_main_table() {
    $('#student_data_upload_form').validate({
        rules: {
            'dept_name': "required",
            'program_name': "required",
            'curriculum_name': "required",
        },
        messages: {
            'dept_name': {
                required: "Department is required"
            },
            'program_name': {
                required: "Program is required"
            },
            'curriculum_name': {
                required: "Curriculum is required"
            },
        }
    });
    if ($('#student_data_upload_form').valid()) {
        if ($('#Filedata').val()) {
            $('#loading').show();
            var post_data = {
                'dept_id': $('#dept_name').val(),
                'pgm_id': $('#program_name').val(),
                'crclm_id': $('#curriculum_name').val(),
            }
            $.ajax({
                type: "POST",
                url: base_url + 'survey/import_student_data/insert_to_main_table',
                data: post_data,
                success: function (msg) {
                    $('#loading').hide();
                    //$('#student_data').html(msg);
                    if (msg == 0) {
                        $('#remarks_exists').modal('show');
                    } else if (msg == 1) {
                        $('#import_status').modal('show');
                    } else if (msg == 3) {
                        $('#duplicate_data').modal('show');
                        displayDuplicateData();
                    } else if (msg = "dupicate_email") {
                        $('#student_duplicate_email_data').modal('show');
                    } else {
                        $('#file_not_uploaded').modal('show');
                    }
                }
            });
        } else {
            $('#file_not_uploaded').modal('show');
        }
    }
}

function displayDuplicateData() {
    var post_data = {
        'crclm_id': $('#crclm_id').val(),
    }

    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/display_duplicate_student_data',
        data: post_data,
        success: function (msg) {
            $('#student_duplicate_data').html(msg);
            $('#discard_dup').show();
            $('#discard_dup').css('visibility', 'visible');
            $('#update_dup_data').show();
            $('#update_dup_data').css('visibility', 'visible');
        }
    });
}
function update_duplicate_data() {

    var dept_id = $('#dept_id').val();
    var pgm_id = $('#pgm_id').val();
    var crclm_id = $('#crclm_id').val();
    var post_data = {
        'dept_id': dept_id,
        'pgm_id': pgm_id,
        'crclm_id': crclm_id,
        //'section_id' : section_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/update_duplicate_student_data',
        data: post_data,
        success: function (msg) {
            if (msg == 1) {
                $('#update_status').modal('show');
                //	drop_temp_table();
            } else if (msg == 0) {
                $('#error_display').modal('show');
            } else {
                $('#error_display').modal('show');
            }
        }
    });
}

function clearFields() {
    $('#student_data').empty();
    $('#student_duplicate_data').empty();
    //$('#student_data_upload_form')[0].reset();
    $('#Filedata').val('');
    drop_temp_table();
    /* //window.location.href = base_url+"survey/import_student_data/";
     //window.location = base_url + 'survey/import_student_data/'; */
}
//drop temporary table
function drop_temp_table() {
    var crclm_id = $('#crclm_id').val();
    var post_data = {
        'crclm_id': crclm_id
    }

    $.ajax({
        type: "POST",
        url: base_url + 'survey/import_student_data/drop_temp_table',
        data: post_data,
        success: function (msg) {
            window.location = base_url + 'survey/import_student_data/';
        }
    });
    //window.location = base_url + 'survey/import_student_data/';
}

function discard_entry() {
    window.location.href = base_url + "survey/import_student_data/";
}
